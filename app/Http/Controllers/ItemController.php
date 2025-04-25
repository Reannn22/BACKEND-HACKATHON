<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    protected $rules = [
        'nama_barang' => 'required|string',
        'kode_barang' => 'required|string|unique:items',
        'merek_barang' => 'required|string',
        'tahun_pengadaan' => 'required|string',
        'deskripsi_barang' => 'nullable|string',
        'jumlah_barang' => 'required|integer|min:0',
        'jumlah_tersedia' => 'nullable|integer|min:0',
        'lokasi_barang' => 'nullable|string',
        'id_kategori' => 'required|integer',  // Added this line
        'is_dibawa' => 'required|in:true,false,1,0',  // Accept string "true"/"false" or 1/0
        'berat_barang' => 'required|string',
        'warna_barang' => 'required|string',
        'foto_barang.*' => 'required|file|mimes:jpg,jpeg,png|max:2048'
    ];

    private function formatWeight($weight)
    {
        if ($weight >= 1000) {
            return ($weight / 1000) . ' kg';
        }
        return $weight . ' g';
    }

    private function formatItemResponse($item)
    {
        return [
            'id' => $item->id,
            'nama_barang' => $item->nama_barang,
            'kode_barang' => $item->kode_barang,
            'merek_barang' => $item->merek_barang,
            'tahun_pengadaan' => $item->tahun_pengadaan,
            'foto_barang' => $item->foto_barang ? $item->foto_barang->map(function($foto) {
                return [
                    'id' => $foto->id,
                    'foto_path' => asset('storage/foto_barang/' . $foto->foto_path)
                ];
            }) : [],
            'deskripsi_barang' => $item->deskripsi_barang,
            'jumlah_barang' => $item->jumlah_barang,
            'jumlah_tersedia' => $item->jumlah_tersedia,
            'lokasi_barang' => $item->lokasi_barang,
            'nama_kategori' => $item->category ? $item->category->nama_kategori : null,
            'is_dibawa' => $item->is_dibawa,
            'berat_barang' => $this->formatWeight($item->berat_barang),
            'warna_barang' => $item->warna_barang,
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at
        ];
    }

    private function formatResponse($item)
    {
        $item->load('foto_barang');
        return $item;
    }

    public function index(): JsonResponse
    {
        try {
            $items = Item::with('category')->get();
            $formattedItems = $items->map(function($item) {
                return $this->formatItemResponse($item);
            })->filter();

            return response()->json([
                'message' => 'Items retrieved successfully',
                'data' => array_values($formattedItems->toArray())
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            // Debug request
            \Log::info('Request files:', ['files' => $request->allFiles()]);

            $validatedData = $request->validate($this->rules);

            // Set defaults and convert values
            $validatedData['is_dibawa'] = filter_var($request->is_dibawa, FILTER_VALIDATE_BOOLEAN) ?
                'Bisa dibawa pulang' : 'Tidak bisa dibawa pulang';
            $validatedData['berat_barang'] = (int) $request->berat_barang;
            $validatedData['jumlah_tersedia'] = $validatedData['jumlah_barang'];
            $validatedData['deskripsi_barang'] = $request->input('deskripsi_barang', '');
            $validatedData['lokasi_barang'] = $request->input('lokasi_barang', '');

            \DB::beginTransaction();
            try {
                $item = Item::create($validatedData);

                if ($request->hasFile('foto_barang')) {
                    \Log::info('Processing foto_barang files');
                    $photos = $request->file('foto_barang');
                    if (!is_array($photos)) {
                        $photos = [$photos];
                    }

                    foreach ($photos as $photo) {
                        if ($photo->isValid()) {
                            $filename = time() . '_' . uniqid() . '_' . $photo->getClientOriginalName();
                            $photo->storeAs('public/foto_barang', $filename);

                            \Log::info('Creating foto record', ['filename' => $filename]);
                            $item->foto_barang()->create([
                                'foto_path' => $filename
                            ]);
                        } else {
                            \Log::error('Invalid file uploaded', ['file' => $photo]);
                        }
                    }
                } else {
                    \Log::info('No foto_barang files found in request');
                }

                \DB::commit();
            } catch (\Exception $e) {
                \DB::rollback();
                \Log::error('Error in transaction', ['error' => $e->getMessage()]);
                throw $e;
            }

            $item->load(['category', 'foto_barang']);

            // Debug final item
            \Log::info('Final item data:', ['item' => $item->toArray()]);

            return response()->json([
                'message' => 'Item created successfully',
                'data' => $this->formatItemResponse($item)
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $item = Item::with('category')->findOrFail($id);
            return response()->json([
                'message' => 'Item retrieved successfully',
                'data' => $this->formatItemResponse($item)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Item not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $item = Item::findOrFail($id);
            $validatedData = $request->validate($this->rules);

            if ($request->hasFile('gambar_barang')) {
                // Delete old image if exists
                if ($item->gambar_barang) {
                    Storage::delete('public/gambar_barang/' . $item->gambar_barang);
                }

                $file = $request->file('gambar_barang');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/gambar_barang', $filename);
                $validatedData['gambar_barang'] = $filename;
            }

            $validatedData['jumlah_tersedia'] = $validatedData['jumlah_barang'];
            $item->update($validatedData);
            $item->load('category');

            return response()->json([
                'message' => 'Item updated successfully',
                'data' => $this->formatItemResponse($item)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update item',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $item = Item::findOrFail($id);
            $item->delete();
            return response()->json([
                'message' => 'Item deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete item',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    public function deleteAll(): JsonResponse
    {
        try {
            \DB::beginTransaction();
            try {
                // Disable foreign key checks
                \DB::statement('SET FOREIGN_KEY_CHECKS=0');

                // Delete all records from both tables
                \DB::table('foto_barang')->truncate();
                Item::truncate();

                // Re-enable foreign key checks
                \DB::statement('SET FOREIGN_KEY_CHECKS=1');

                \DB::commit();

                return response()->json([
                    'message' => 'All items deleted successfully'
                ], 200);
            } catch (\Exception $e) {
                \DB::rollback();
                // Make sure foreign key checks are re-enabled even if there's an error
                \DB::statement('SET FOREIGN_KEY_CHECKS=1');
                throw $e;
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete all items',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
