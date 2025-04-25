<?php

namespace App\Http\Controllers;

use App\Models\ItemReview;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ReviewController extends Controller
{
    protected $rules = [
        'id_item' => 'required|exists:items,id',
        'nama_pengguna' => 'required|string|max:255',
        'rating' => 'required|integer|min:1|max:5',
        'komentar' => 'required|string|max:255',
        'foto_ulasan.*' => 'nullable|file|mimes:jpg,jpeg,png|max:2048'
    ];

    public function index(): JsonResponse
    {
        try {
            $reviews = ItemReview::with('item')->get();
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
            
            $review = ItemReview::create([
                'id_item' => $request->id_item,
                'nama_pengguna' => $request->nama_pengguna,
                'rating' => $request->rating,
                'komentar' => $request->komentar
            ]);

            if ($request->hasFile('foto_ulasan')) {
                foreach($request->file('foto_ulasan') as $photo) {
                    $filename = time() . '_' . $photo->getClientOriginalName();
                    $photo->storeAs('public/foto_ulasan', $filename);
                    
                    $review->fotos()->create([
                        'foto_path' => $filename
                    ]);
                }
            }

            $review->load('fotos');
            return response()->json([
                'message' => 'Review created successfully',
                'data' => $review
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

    public function show($id): JsonResponse
    {
        try {
            $review = ItemReview::with('item')->findOrFail($id);
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
}
