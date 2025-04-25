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
        'nama_barang' => 'required|string|max:255',
        'kode_barang' => 'required|string|unique:items,kode_barang',
        'merek_barang' => 'required|string',
        'tahun_pengadaan' => 'required|integer',
        'deskripsi_barang' => 'required|string',
        'jumlah_barang' => 'required|integer|min:0',
        'id_kategori' => 'required|exists:categories,id',
        'id_lokasi' => 'required|exists:locations,id',
        'is_dibawa' => 'required|string',
        'berat_barang' => 'required|numeric',
        'warna_barang' => 'required|string',
        'id_admin' => 'required|exists:users,id'
        // Removed jumlah_tersedia from validation rules since it will be set automatically
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
            'deskripsi_barang' => $item->deskripsi_barang,
            'jumlah_barang' => $item->jumlah_barang,
            'jumlah_tersedia' => $item->jumlah_tersedia,
            'kategori' => $item->category ? [
                'id' => $item->category->id,
                'nama_kategori' => $item->category->nama_kategori
            ] : null,
            'lokasi' => $item->location ? [
                'id' => $item->location->id,
                'nama_lokasi' => $item->location->nama_lokasi,
                'gedung' => $item->location->gedung,
                'ruangan' => $item->location->ruangan
            ] : null,
            'admin' => $item->admin ? [
                'id' => $item->admin->id,
                'name' => $item->admin->name,
                'email' => $item->admin->email,
                'role' => $item->admin->role,
                'no_hp' => $item->admin->no_hp,
                'created_at' => $item->admin->created_at,
                'updated_at' => $item->admin->updated_at
            ] : null,
            'is_dibawa' => $item->is_dibawa,
            'berat_barang' => $this->formatWeight($item->berat_barang),
            'warna_barang' => $item->warna_barang,
            'foto_barang' => $item->foto_barang->map(function($foto) {
                return [
                    'id' => $foto->id,
                    'foto_path' => asset('storage/foto_barang/' . $foto->foto_path)
                ];
            }),
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at
        ];
    }

    public function index(): JsonResponse
    {
        try {
            $items = Item::with(['category', 'location', 'admin', 'foto_barang'])->get();
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
            $validatedData = $request->validate($this->rules);

            if (Item::count() === 0) {
                \DB::statement('ALTER TABLE items AUTO_INCREMENT = 1');
            }

            $validatedData['jumlah_tersedia'] = $validatedData['jumlah_barang'];

            $item = Item::create($validatedData);

            // Handle photo uploads if present
            if ($request->hasFile('foto_barang')) {
                $files = $request->file('foto_barang');
                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $photo) {
                    if ($photo->isValid()) {
                        $filename = time() . '_' . uniqid() . '_' . $photo->getClientOriginalName();
                        $path = $photo->storeAs('public/foto_barang', $filename);

                        $item->foto_barang()->create([
                            'foto_path' => $filename
                        ]);
                    }
                }
            }

            // Load relationships
            $item->load(['category', 'location', 'foto_barang', 'admin']);

            return response()->json([
                'message' => 'Item created successfully',
                'data' => $this->formatItemResponse($item)
            ], 201);
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
            $item = Item::with(['category', 'location', 'admin', 'foto_barang'])->findOrFail($id);
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
            $item->load(['category', 'location', 'admin', 'foto_barang']);

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
