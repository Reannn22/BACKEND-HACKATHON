<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class TestController extends Controller
{
    public function testDatabase(): JsonResponse
    {
        try {
            DB::connection()->getPdo();
            return response()->json([
                'message' => 'Database connected successfully',
                'database' => DB::connection()->getDatabaseName()
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function testUser(): JsonResponse
    {
        return response()->json(['message' => 'User endpoint working']);
    }
}
