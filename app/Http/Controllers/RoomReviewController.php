<?php

namespace App\Http\Controllers;

use App\Models\RoomReview;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class RoomReviewController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $roomReviews = RoomReview::all();
            return response()->json([
                'message' => 'Room reviews retrieved successfully',
                'data' => $roomReviews
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve room reviews',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nama_room_review' => 'required|string|max:255|unique:room_reviews,nama_room_review'
            ]);

            $roomReview = RoomReview::create([
                'nama_room_review' => $request->nama_room_review
            ]);

            return response()->json([
                'message' => 'Room review created successfully',
                'data' => [
                    'id' => $roomReview->id,
                    'nama_room_review' => $roomReview->nama_room_review,
                    'created_at' => $roomReview->created_at,
                    'updated_at' => $roomReview->updated_at
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create room review',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $roomReview = RoomReview::findOrFail($id);
            return response()->json([
                'message' => 'Room review retrieved successfully',
                'data' => $roomReview
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Room review not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $roomReview = RoomReview::findOrFail($id);
            $request->validate([
                'nama_room_review' => 'required|string|unique:room_reviews,nama_room_review,'.$id
            ]);

            $roomReview->update($request->all());
            return response()->json([
                'message' => 'Room review updated successfully',
                'data' => $roomReview
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update room review',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $roomReview = RoomReview::findOrFail($id);
            $roomReview->delete();
            return response()->json([
                'message' => 'Room review deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete room review',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }
}
