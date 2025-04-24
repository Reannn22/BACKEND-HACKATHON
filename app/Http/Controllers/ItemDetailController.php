<?php

namespace App\Http\Controllers;

use App\Models\ItemDetail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ItemDetailController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $itemDetails = ItemDetail::all();
            return response()->json([
                'message' => 'Item details retrieved successfully',
                'data' => $itemDetails
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve item details',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nama_item_detail' => 'required|string|max:255|unique:items_detail,nama_item_detail'
            ]);

            $itemDetail = ItemDetail::create([
                'nama_item_detail' => $request->nama_item_detail
            ]);

            return response()->json([
                'message' => 'Item detail created successfully',
                'data' => [
                    'id' => $itemDetail->id,
                    'nama_item_detail' => $itemDetail->nama_item_detail,
                    'created_at' => $itemDetail->created_at,
                    'updated_at' => $itemDetail->updated_at
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create item detail',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $itemDetail = ItemDetail::findOrFail($id);
            return response()->json([
                'message' => 'Item detail retrieved successfully',
                'data' => $itemDetail
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Item detail not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $itemDetail = ItemDetail::findOrFail($id);
            $request->validate([
                'nama_item_detail' => 'required|string|unique:items_detail,nama_item_detail,'.$id
            ]);

            $itemDetail->update($request->all());
            return response()->json([
                'message' => 'Item detail updated successfully',
                'data' => $itemDetail
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update item detail',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $itemDetail = ItemDetail::findOrFail($id);
            $itemDetail->delete();
            return response()->json([
                'message' => 'Item detail deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete item detail',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }
}
