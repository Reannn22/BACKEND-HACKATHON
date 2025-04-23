<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class BorrowingController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $borrowings = Borrowing::all();
            return response()->json([
                'message' => 'Borrowings retrieved successfully',
                'data' => $borrowings
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve borrowings',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nama_borrowing' => 'required|string|max:255|unique:borrowings,nama_borrowing'
            ]);

            $borrowing = Borrowing::create([
                'nama_borrowing' => $request->nama_borrowing
            ]);

            return response()->json([
                'message' => 'Borrowing created successfully',
                'data' => [
                    'id' => $borrowing->id,
                    'nama_borrowing' => $borrowing->nama_borrowing,
                    'created_at' => $borrowing->created_at,
                    'updated_at' => $borrowing->updated_at
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create borrowing',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $borrowing = Borrowing::findOrFail($id);
            return response()->json([
                'message' => 'Borrowing retrieved successfully',
                'data' => $borrowing
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Borrowing not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $borrowing = Borrowing::findOrFail($id);
            $request->validate([
                'nama_borrowing' => 'required|string|unique:borrowings,nama_borrowing,'.$id
            ]);

            $borrowing->update($request->all());
            return response()->json([
                'message' => 'Borrowing updated successfully',
                'data' => $borrowing
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update borrowing',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $borrowing = Borrowing::findOrFail($id);
            $borrowing->delete();
            return response()->json([
                'message' => 'Borrowing deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete borrowing',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }
}
