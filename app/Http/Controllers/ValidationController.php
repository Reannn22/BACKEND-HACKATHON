<?php

namespace App\Http\Controllers;

use App\Models\Validation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ValidationController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $validations = Validation::all();
            return response()->json([
                'message' => 'Validations retrieved successfully',
                'data' => $validations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve validations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nama_validation' => 'required|string|max:255|unique:validations,nama_validation'
            ]);

            $validation = Validation::create([
                'nama_validation' => $request->nama_validation
            ]);

            return response()->json([
                'message' => 'Validation created successfully',
                'data' => [
                    'id' => $validation->id,
                    'nama_validation' => $validation->nama_validation,
                    'created_at' => $validation->created_at,
                    'updated_at' => $validation->updated_at
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create validation',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $validation = Validation::findOrFail($id);
            return response()->json([
                'message' => 'Validation retrieved successfully',
                'data' => $validation
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Validation not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $validation = Validation::findOrFail($id);
            $request->validate([
                'nama_validation' => 'required|string|unique:validations,nama_validation,'.$id
            ]);

            $validation->update($request->all());
            return response()->json([
                'message' => 'Validation updated successfully',
                'data' => $validation
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update validation',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $validation = Validation::findOrFail($id);
            $validation->delete();
            return response()->json([
                'message' => 'Validation deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete validation',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }
}
