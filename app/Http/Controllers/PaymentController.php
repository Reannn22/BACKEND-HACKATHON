<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $payments = Payment::all();
            return response()->json([
                'message' => 'Payments retrieved successfully',
                'data' => $payments
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to retrieve payments',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'nama_payment' => 'required|string|max:255|unique:payments,nama_payment'
            ]);

            $payment = Payment::create([
                'nama_payment' => $request->nama_payment
            ]);

            return response()->json([
                'message' => 'Payment created successfully',
                'data' => [
                    'id' => $payment->id,
                    'nama_payment' => $payment->nama_payment,
                    'created_at' => $payment->created_at,
                    'updated_at' => $payment->updated_at
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create payment',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(int $id): JsonResponse
    {
        try {
            $payment = Payment::findOrFail($id);
            return response()->json([
                'message' => 'Payment retrieved successfully',
                'data' => $payment
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Payment not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $payment = Payment::findOrFail($id);
            $request->validate([
                'nama_payment' => 'required|string|unique:payments,nama_payment,'.$id
            ]);

            $payment->update($request->all());
            return response()->json([
                'message' => 'Payment updated successfully',
                'data' => $payment
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to update payment',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $payment = Payment::findOrFail($id);
            $payment->delete();
            return response()->json([
                'message' => 'Payment deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to delete payment',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }
}
