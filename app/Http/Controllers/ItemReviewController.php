<?php

namespace App\Http\Controllers;

use App\Models\ItemReview;
use App\Models\FotoUlasan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ItemReviewController extends Controller
{
    protected $rules = [
        'id_item' => 'required|exists:items,id',
        'nama_pengguna' => 'required|string|max:255',
        'rating' => 'required|integer|min:1|max:5',
        'komentar' => 'required|string|max:255',
        'foto_ulasan.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048'
    ];

    private function formatResponse($review)
    {
        return [
            'id' => $review->id,
            'id_item' => $review->id_item,
            'nama_pengguna' => $review->nama_pengguna,
            'foto_ulasan' => $review->fotos->map(fn($foto) => [
                'id' => $foto->id,
                'foto_path' => asset('storage/foto_ulasan/' . $foto->foto_path)
            ]),
            'rating' => $review->rating,
            'komentar' => $review->komentar,
            'created_at' => $review->created_at,
            'updated_at' => $review->updated_at
        ];
    }

    public function index(): JsonResponse
    {
        try {
            $reviews = ItemReview::with(['item', 'fotos'])->get();
            return response()->json([
                'message' => 'Reviews retrieved successfully',
                'data' => $reviews
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate($this->rules);

            // Check if this is the first review
            if (ItemReview::count() === 0) {
                // Reset the auto-increment to 1 with correct table name
                \DB::statement('ALTER TABLE reviews AUTO_INCREMENT = 1');
            }

            $review = ItemReview::create([
                'id_item' => $request->id_item,
                'nama_pengguna' => $request->nama_pengguna,
                'rating' => $request->rating,
                'komentar' => $request->komentar
            ]);

            if ($request->hasFile('foto_ulasan')) {
                $files = $request->file('foto_ulasan');
                if (!is_array($files)) {
                    $files = [$files];
                }

                foreach ($files as $photo) {
                    if ($photo->isValid()) {
                        $filename = time() . '_' . uniqid() . '_' . $photo->getClientOriginalName();
                        $path = $photo->storeAs('public/foto_ulasan', $filename);

                        $review->fotos()->create([
                            'foto_path' => $filename
                        ]);
                    }
                }
            }

            $review->refresh();
            $review->load('fotos');

            return response()->json([
                'message' => 'Review created successfully',
                'data' => $this->formatResponse($review)
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $review = ItemReview::findOrFail($id);
            return response()->json([
                'message' => 'Review retrieved successfully',
                'data' => $review
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Review not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $review = ItemReview::findOrFail($id);
            $request->validate($this->rules);

            $review->update($request->all());
            return response()->json([
                'message' => 'Review updated successfully',
                'data' => $review
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update review',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $review = ItemReview::findOrFail($id);
            $review->delete();
            return response()->json([
                'message' => 'Review deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete review',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    public function deleteAll(): JsonResponse
    {
        try {
            // Disable foreign key checks
            \DB::statement('SET FOREIGN_KEY_CHECKS=0');

            // Delete all photos first
            FotoUlasan::truncate();

            // Delete all reviews
            ItemReview::truncate();

            // Reset auto-increment with correct table names
            \DB::statement('ALTER TABLE reviews AUTO_INCREMENT = 1');
            \DB::statement('ALTER TABLE foto_ulasans AUTO_INCREMENT = 1');

            // Re-enable foreign key checks
            \DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return response()->json([
                'message' => 'All reviews deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            // Make sure to re-enable foreign key checks even if there's an error
            \DB::statement('SET FOREIGN_KEY_CHECKS=1');

            return response()->json([
                'message' => 'Failed to delete all reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
