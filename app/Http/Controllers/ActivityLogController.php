<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ActivityLogController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $activities = ActivityLog::all();
            return response()->json([
                'message' => 'Activities log retrieved successfully',
                'data' => $activities
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve activities log',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nama_activity' => 'required|string|max:255|unique:activities_log,nama_activity'
            ]);

            $activity = ActivityLog::create([
                'nama_activity' => $request->nama_activity
            ]);

            return response()->json([
                'message' => 'Activity log created successfully',
                'data' => [
                    'id' => $activity->id,
                    'nama_activity' => $activity->nama_activity,
                    'created_at' => $activity->created_at,
                    'updated_at' => $activity->updated_at
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create activity log',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $activity = ActivityLog::findOrFail($id);
            return response()->json([
                'message' => 'Activity log retrieved successfully',
                'data' => $activity
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Activity log not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $activity = ActivityLog::findOrFail($id);
            $request->validate([
                'nama_activity' => 'required|string|unique:activities_log,nama_activity,'.$id
            ]);

            $activity->update($request->all());
            return response()->json([
                'message' => 'Activity log updated successfully',
                'data' => $activity
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update activity log',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $activity = ActivityLog::findOrFail($id);
            $activity->delete();
            return response()->json([
                'message' => 'Activity log deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete activity log',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }
}
