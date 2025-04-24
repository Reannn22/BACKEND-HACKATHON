<?php

namespace App\Http\Controllers;

use App\Models\RoomDetail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class RoomDetailController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $roomDetails = RoomDetail::all();
            return response()->json([
                'message' => 'Room details retrieved successfully',
                'data' => $roomDetails
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve room details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nama_room_detail' => 'required|string|max:255|unique:rooms_detail,nama_room_detail'
            ]);

            $roomDetail = RoomDetail::create([
                'nama_room_detail' => $request->nama_room_detail
            ]);

            return response()->json([
                'message' => 'Room detail created successfully',
                'data' => [
                    'id' => $roomDetail->id,
                    'nama_room_detail' => $roomDetail->nama_room_detail,
                    'created_at' => $roomDetail->created_at,
                    'updated_at' => $roomDetail->updated_at
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create room detail',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $roomDetail = RoomDetail::findOrFail($id);
            return response()->json([
                'message' => 'Room detail retrieved successfully',
                'data' => $roomDetail
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Room detail not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $roomDetail = RoomDetail::findOrFail($id);
            $request->validate([
                'nama_room_detail' => 'required|string|unique:rooms_detail,nama_room_detail,'.$id
            ]);

            $roomDetail->update($request->all());
            return response()->json([
                'message' => 'Room detail updated successfully',
                'data' => $roomDetail
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update room detail',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $roomDetail = RoomDetail::findOrFail($id);
            $roomDetail->delete();
            return response()->json([
                'message' => 'Room detail deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete room detail',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }
}
