<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ItemController extends Controller
{
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
            $request->validate([
                'nama_item' => 'required|string|max:255|unique:items,nama_item'
            ]);

            $item = Item::create([
                'nama_item' => $request->nama_item
            ]);

            return response()->json([
                'message' => 'Item created successfully',
                'data' => [
                    'id' => $item->id,
                    'nama_item' => $item->nama_item,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at
                ]
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
            $request->validate([
                'nama_item' => 'required|string|unique:items,nama_item,'.$id
            ]);

            $item->update($request->all());
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
