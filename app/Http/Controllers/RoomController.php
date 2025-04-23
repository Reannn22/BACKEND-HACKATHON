<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class RoomController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $rooms = Room::all();
            return response()->json([
                'message' => 'Rooms retrieved successfully',
                'data' => $rooms
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve rooms',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nama_room' => 'required|string|max:255|unique:rooms,nama_room'
            ]);

            $room = Room::create([
                'nama_room' => $request->nama_room
            ]);

            return response()->json([
                'message' => 'Room created successfully',
                'data' => [
                    'id' => $room->id,
                    'nama_room' => $room->nama_room,
                    'created_at' => $room->created_at,
                    'updated_at' => $room->updated_at
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create room',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $room = Room::findOrFail($id);
            return response()->json([
                'message' => 'Room retrieved successfully',
                'data' => $room
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Room not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $room = Room::findOrFail($id);
            $request->validate([
                'nama_room' => 'required|string|unique:rooms,nama_room,'.$id
            ]);

            $room->update($request->all());
            return response()->json([
                'message' => 'Room updated successfully',
                'data' => $room
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update room',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $room = Room::findOrFail($id);
            $room->delete();
            return response()->json([
                'message' => 'Room deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete room',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }
}
