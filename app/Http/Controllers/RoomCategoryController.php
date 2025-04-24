<?php

namespace App\Http\Controllers;

use App\Models\RoomCategory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class RoomCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $categories = RoomCategory::all();
            return response()->json([
                'message' => 'Room categories retrieved successfully',
                'data' => $categories
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve room categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nama_kategori' => 'required|string|max:255|unique:room_categories,nama_kategori'
            ]);

            $category = RoomCategory::create([
                'nama_kategori' => $request->nama_kategori
            ]);

            return response()->json([
                'message' => 'Room category created successfully',
                'data' => [
                    'id' => $category->id,
                    'nama_kategori' => $category->nama_kategori,
                    'created_at' => $category->created_at,
                    'updated_at' => $category->updated_at
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create room category',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $category = RoomCategory::findOrFail($id);
            return response()->json([
                'message' => 'Room category retrieved successfully',
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Room category not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $category = RoomCategory::findOrFail($id);
            $request->validate([
                'nama_kategori' => 'required|string|unique:room_categories,nama_kategori,'.$id
            ]);

            $category->update($request->all());
            return response()->json([
                'message' => 'Room category updated successfully',
                'data' => $category
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update room category',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $category = RoomCategory::findOrFail($id);
            $category->delete();
            return response()->json([
                'message' => 'Room category deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete room category',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    public function deleteAll(): JsonResponse
    {
        try {
            $count = RoomCategory::count();
            RoomCategory::truncate();

            return response()->json([
                'message' => $count . ' room categories have been deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete room categories',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
