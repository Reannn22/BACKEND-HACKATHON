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
        'kondisi_barang' => 'required|string|in:baru,baik,rusak ringan,rusak berat',
        'status_barang' => 'required|string|in:aktif,non-aktif,dipinjam,dalam perbaikan',
        'harga_perolehan' => 'required|numeric|min:0',
        // Removed id_admin from validation rules
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
        // Force fresh loading of photos
        $item->loadMissing(['category', 'location', 'admin', 'foto_barang']);

        $photos = $item->foto_barang()->orderBy('id', 'asc')->get();

        return [
            'id' => $item->id,
            'nama_barang' => $item->nama_barang,
            'kode_barang' => $item->kode_barang,
            'merek_barang' => $item->merek_barang,
            'tahun_pengadaan' => $item->tahun_pengadaan,
            'deskripsi_barang' => $item->deskripsi_barang,
            'jumlah_barang' => $item->jumlah_barang,
            'jumlah_tersedia' => $item->jumlah_tersedia,
            'berat_barang' => $this->formatWeight($item->berat_barang),
            'warna_barang' => $item->warna_barang,
            'kondisi_barang' => $item->kondisi_barang,
            'harga_perolehan' => $item->harga_perolehan,
            'status_barang' => $item->status_barang,
            'is_dibawa' => $item->is_dibawa,
            'foto_barang' => $photos->map(function($foto) {
                return [
                    'id' => $foto->id,
                    'foto_path' => asset('storage/foto_barang/' . $foto->foto_path)
                ];
            })->values()->all(),
            'kategori' => $item->category ? [
                'id' => $item->category->id,
                'nama_kategori' => $item->category->nama_kategori
            ] : null,
            'lokasi' => $item->location ? [
                'id' => $item->location->id,
                'nama_lokasi' => $item->location->nama_lokasi
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
            'created_at' => $item->created_at,
            'updated_at' => $item->updated_at
        ];
    }

    public function index(Request $request): JsonResponse
    {
        try {
            $query = Item::with(['category', 'location', 'admin', 'foto_barang']);

            // Name filter
            if ($request->has('search')) {
                $search = $request->search;
                $query->where('nama_barang', 'LIKE', "%{$search}%");
            }

            // Code filter
            if ($request->has('kode')) {
                $kode = $request->kode;
                $query->where('kode_barang', $kode);
            }

            // Brand filter
            if ($request->has('merek')) {
                $merek = $request->merek;
                $query->where('merek_barang', 'LIKE', "%{$merek}%");
            }

            // Year filter
            if ($request->has('tahun')) {
                $tahun = $request->tahun;
                $query->where('tahun_pengadaan', $tahun);
            }

            // Category filter - case insensitive and partial match
            if ($request->has('kategori')) {
                $kategori = $request->kategori;
                $query->whereHas('category', function($q) use ($kategori) {
                    // Handle both "Elektronik" and "Elektonik" spellings
                    $q->where('nama_kategori', 'LIKE', "%{$kategori}%")
                      ->orWhere(\DB::raw('LOWER(nama_kategori)'), 'LIKE', '%' . strtolower($kategori) . '%');
                });
            }

            // Location filter using case-insensitive search
            if ($request->has('lokasi')) {
                $lokasi = $request->lokasi;
                $query->whereHas('location', function($q) use ($lokasi) {
                    $q->whereRaw('LOWER(nama_lokasi) LIKE ?', ['%' . strtolower($lokasi) . '%']);
                });
            }

            // Status filter
            if ($request->has('status')) {
                $status = $request->status;
                $query->where('status_barang', $status);
            }

            // Condition filter
            if ($request->has('kondisi')) {
                $kondisi = $request->kondisi;
                $query->where('kondisi_barang', $kondisi);
            }

            // Is Dibawa filter
            if ($request->has('is_dibawa')) {
                $isDibawa = $request->is_dibawa;
                $query->where('is_dibawa', $isDibawa);
            }

            // Total quantity filter
            if ($request->has('jumlah_total')) {
                $jumlahTotal = $request->jumlah_total;
                $query->where('jumlah_barang', $jumlahTotal);
            }

            // Available quantity filter
            if ($request->has('jumlah_tersedia')) {
                $jumlahTersedia = $request->jumlah_tersedia;
                $query->where('jumlah_tersedia', $jumlahTersedia);
            }

            // Price range filter
            if ($request->has('harga_min') || $request->has('harga_max')) {
                if ($request->has('harga_min')) {
                    $query->where('harga_perolehan', '>=', $request->harga_min);
                }
                if ($request->has('harga_max')) {
                    $query->where('harga_perolehan', '<=', $request->harga_max);
                }
            }

            // Weight filter
            if ($request->has('berat_barang')) {
                $beratBarang = $request->berat_barang;
                $query->where('berat_barang', $beratBarang);
            }

            // Date filter
            if ($request->has('date')) {
                $date = $request->date;
                $query->whereDate('created_at', $date);
            }

            // Date range filter
            if ($request->has('date_from')) {
                $query->whereDate('created_at', '>=', $request->date_from);
            }
            if ($request->has('date_to')) {
                $query->whereDate('created_at', '<=', $request->date_to);
            }

            // Total quantity sorting
            if ($request->has('jumlah_total')) {
                $query->orderBy('jumlah_barang', 'desc');
            }

            $items = $query->get();
            $formattedItems = $items->map(function($item) {
                return $this->formatItemResponse($item);
            });

            return response()->json([
                'message' => 'Items retrieved successfully',
                'data' => $formattedItems->values()->all()
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
            // Validate data first
            $validatedData = $request->validate($this->rules);
            $validatedData['jumlah_tersedia'] = $validatedData['jumlah_barang'];
            $validatedData['id_admin'] = auth()->id();

            // Reset if needed
            if (Item::count() === 0) {
                \DB::statement('SET FOREIGN_KEY_CHECKS=0');
                \DB::table('foto_barang')->truncate();
                \DB::table('items')->truncate();
                \DB::statement('SET FOREIGN_KEY_CHECKS=1');
            }

            // Create item
            $item = Item::create($validatedData);

            // Handle photo uploads
            if ($request->hasFile('foto_barang')) {
                $files = $request->file('foto_barang');
                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $photo) {
                    if ($photo->isValid()) {
                        $filename = time() . '_' . uniqid() . '_' . $photo->getClientOriginalName();
                        $photo->storeAs('public/foto_barang', $filename);
                        $item->foto_barang()->create(['foto_path' => $filename]);
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
            \DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return response()->json([
                'message' => 'Failed to create item',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            // Get item with eager loaded relationships
            $item = Item::with(['category', 'location', 'admin'])
                ->findOrFail($id);

            // Force fresh load of photos
            $item->load(['foto_barang' => function($query) {
                $query->orderBy('id', 'asc');
            }]);

            // Format and return response
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
            \DB::beginTransaction();

            $item = Item::with('foto_barang')->findOrFail($id);

            // Check if foto_barang exists before trying to delete photos
            if ($item->foto_barang && $item->foto_barang->count() > 0) {
                foreach ($item->foto_barang as $foto) {
                    Storage::delete('public/foto_barang/' . $foto->foto_path);
                }
            }

            // Delete the item
            $item->delete();

            \DB::commit();

            return response()->json([
                'message' => 'Item deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'message' => 'Failed to delete item',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    public function deleteAll(): JsonResponse
    {
        try {
            // Start database transaction
            \DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Delete all items and photos
            \DB::table('foto_barang')->delete();
            Item::query()->delete();

            // Reset auto-increment
            \DB::statement('ALTER TABLE items AUTO_INCREMENT = 1');
            \DB::statement('ALTER TABLE foto_barang AUTO_INCREMENT = 1');

            \DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return response()->json([
                'message' => 'All items deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            \DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return response()->json([
                'message' => 'Failed to delete all items',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getByCategory(string $nama_kategori): JsonResponse
    {
        try {
            $items = Item::whereHas('category', function($query) use ($nama_kategori) {
                $query->where('nama_kategori', 'LIKE', "%{$nama_kategori}%");
            })->with(['category', 'location', 'admin', 'foto_barang'])->get();

            $formattedItems = $items->map(function($item) {
                return $this->formatItemResponse($item);
            });

            return response()->json([
                'message' => 'Items retrieved successfully',
                'data' => $formattedItems->values()->all()
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve items',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
