<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ValidationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BorrowingController;

Route::post('/admin/login', [AdminAuthController::class, 'login']); // user dapat login sebagai admin
Route::post('/admin/request_token_forget_password', [AdminAuthController::class, 'requestTokenForgetPassword']);
Route::post('/admin/forgot_password', [AdminAuthController::class, 'forgotPassword']);

Route::middleware('auth:api')->group(function () {
    Route::post('/admin/register', [AdminAuthController::class, 'register']);
    Route::get('/admin/{id}', [AdminAuthController::class, 'show']); // developer dapat melihat data admin
    Route::get('/admin', [AdminAuthController::class, 'index']); //developer dapat melihat semua data admin
    Route::put('/admin/{id}', [AdminAuthController::class, 'update']); // developer dapat mengupdate data admin
    Route::post('/admin/logout', [AdminAuthController::class, 'logout']); // admin dapat logout
    Route::post('/admin/{id}/change_password', [AdminAuthController::class, 'changePassword']); // change password with token
    Route::delete('/admin/{id}', [AdminAuthController::class, 'destroy']); // admin dapat dihapus
    Route::post('/admin/{id}/request_token_change_no_hp', [AdminAuthController::class, 'requestTokenChangeNoHp']);
    Route::post('/admin/{id}/change_no_hp', [AdminAuthController::class, 'changeNoHp']);
    Route::post('/admin/{id}/request_token_change_password', [AdminAuthController::class, 'requestTokenChangePassword']);
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']); // add this line

    // Items routes
    Route::get('/items', [ItemController::class, 'index']);
    Route::post('/items', [ItemController::class, 'store']);
    Route::get('/items/{id}', [ItemController::class, 'show']);
    Route::put('/items/{id}', [ItemController::class, 'update']);
    Route::delete('/items/{id}', [ItemController::class, 'destroy']);

    // Rooms routes
    Route::get('/rooms', [RoomController::class, 'index']);
    Route::post('/rooms', [RoomController::class, 'store']);
    Route::get('/rooms/{id}', [RoomController::class, 'show']);
    Route::put('/rooms/{id}', [RoomController::class, 'update']);
    Route::delete('/rooms/{id}', [RoomController::class, 'destroy']);

    // Validations routes
    Route::get('/validations', [ValidationController::class, 'index']);
    Route::post('/validations', [ValidationController::class, 'store']);
    Route::get('/validations/{id}', [ValidationController::class, 'show']);
    Route::put('/validations/{id}', [ValidationController::class, 'update']);
    Route::delete('/validations/{id}', [ValidationController::class, 'destroy']);

    // Payments routes
    Route::get('/payments', [PaymentController::class, 'index']);
    Route::post('/payments', [PaymentController::class, 'store']);
    Route::get('/payments/{id}', [PaymentController::class, 'show']);
    Route::put('/payments/{id}', [PaymentController::class, 'update']);
    Route::delete('/payments/{id}', [PaymentController::class, 'destroy']);

    // Borrowings routes (updated from Borrowing)
    Route::get('/borrowings', [BorrowingController::class, 'index']);
    Route::post('/borrowings', [BorrowingController::class, 'store']);
    Route::get('/borrowings/{id}', [BorrowingController::class, 'show']);
    Route::put('/borrowings/{id}', [BorrowingController::class, 'update']);
    Route::delete('/borrowings/{id}', [BorrowingController::class, 'destroy']);
});
