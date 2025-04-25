<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ItemController extends Controller
{
    protected $rules = [
        'nama_barang' => 'required|string|max:255',
        'kode_barang' => 'required|string|max:255|unique:items,kode_barang',
        'merek_barang' => 'required|string|max:255',
        'tahun_pengadaan' => 'required|numeric|digits:4',
        'gambar_barang' => 'required|file|mimes:jpg,jpeg|max:2048',
        'deskripsi_barang' => 'required|string',
        'jumlah_barang' => 'required|integer|min:0',
        'lokasi_barang' => 'required|string|max:255'
    ];

    public function index(): JsonResponse
    {
        try {
            $items = Item::all();
            return response()->json([
                'message' => 'Items retrieved successfully',
                'data' => $items
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

            // Handle file upload
            if ($request->hasFile('gambar_barang')) {
                $file = $request->file('gambar_barang');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/gambar_barang', $filename);
                $validatedData['gambar_barang'] = $filename;
            }

            // Set jumlah_tersedia equal to jumlah_barang
            $validatedData['jumlah_tersedia'] = $validatedData['jumlah_barang'];

            $item = Item::create($validatedData);

            return response()->json([
                'message' => 'Item created successfully',
                'data' => [
                    'id' => $item->id,
                    'nama_barang' => $item->nama_barang,
                    'kode_barang' => $item->kode_barang,
                    'merek_barang' => $item->merek_barang,
                    'tahun_pengadaan' => $item->tahun_pengadaan,
                    'gambar_barang' => $item->gambar_barang,
                    'deskripsi_barang' => $item->deskripsi_barang,
                    'jumlah_barang' => $item->jumlah_barang,
                    'jumlah_tersedia' => $item->jumlah_tersedia,
                    'lokasi_barang' => $item->lokasi_barang,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at
                ]
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
            $item = Item::findOrFail($id);
            return response()->json([
                'message' => 'Item retrieved successfully',
                'data' => $item
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

            $item->update([
                'nama_barang' => $validatedData['nama_barang'],
                'kode_barang' => $validatedData['kode_barang'],
                'merk_barang' => $validatedData['merk_barang']
            ]);

            return response()->json([
                'message' => 'Item updated successfully',
                'data' => $item
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
}
