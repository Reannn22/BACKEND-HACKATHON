<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class LocationController extends Controller
{
    protected $rules = [
        'nama_lokasi' => 'required|string|max:255'
    ];

    private function formatLocationResponse($location)
    {
        return [
            'id' => $location->id,
            'nama_lokasi' => $location->nama_lokasi
        ];
    }

    public function index(): JsonResponse
    {
        try {
            $locations = Location::select('id', 'nama_lokasi')->get();
            return response()->json([
                'message' => 'Locations retrieved successfully',
                'data' => $locations
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve locations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $validatedData = $request->validate($this->rules);

            if (Location::count() === 0) {
                \DB::statement('ALTER TABLE locations AUTO_INCREMENT = 1');
            }

            $location = Location::create($validatedData);

            return response()->json([
                'message' => 'Location created successfully',
                'data' => $this->formatLocationResponse($location)
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $location = Location::select('id', 'nama_lokasi')->findOrFail($id);
            return response()->json([
                'message' => 'Location retrieved successfully',
                'data' => $this->formatLocationResponse($location)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Location not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $location = Location::findOrFail($id);
            $validatedData = $request->validate($this->rules);

            $location->update($validatedData);
            return response()->json([
                'message' => 'Location updated successfully',
                'data' => $this->formatLocationResponse($location)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update location',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $location = Location::findOrFail($id);
            $location->delete();
            return response()->json([
                'message' => 'Location deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete location',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
