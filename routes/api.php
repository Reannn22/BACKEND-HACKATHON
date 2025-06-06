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
use App\Http\Controllers\ItemDetailController;
use App\Http\Controllers\RoomDetailController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ItemReviewController;
use App\Http\Controllers\RoomReviewController;
use App\Http\Controllers\RoomCategoryController;
use App\Http\Controllers\LocationController;

Route::post('/admin/login', [AdminAuthController::class, 'login']); // user dapat login sebagai admin
Route::post('/admin/request_token_forget_password', [AdminAuthController::class, 'requestTokenForgetPassword']);
Route::post('/admin/forgot_password', [AdminAuthController::class, 'forgotPassword']);

// Move these routes outside the auth middleware group
Route::get('/items_reviews', [ItemReviewController::class, 'index']);
Route::post('/items_reviews', [ItemReviewController::class, 'store']);
Route::get('/items_reviews/{id}', [ItemReviewController::class, 'show']);
Route::put('/items_reviews/{id}', [ItemReviewController::class, 'update']);
Route::delete('/items_reviews/{id}', [ItemReviewController::class, 'destroy']);
Route::delete('/items_reviews', [ItemReviewController::class, 'deleteAll']);

// Public routes for items
Route::get('/items', [ItemController::class, 'index']);
Route::get('/items/category/{nama_kategori}', [ItemController::class, 'getByCategory']); // Add this line
Route::get('/items/{id}', [ItemController::class, 'show']);

Route::middleware('auth:api')->group(function () {
    Route::post('/admin/register', [AdminAuthController::class, 'register']);
    Route::get('/admin/{id}', [AdminAuthController::class, 'show']); // developer dapat melihat data admin
    Route::get('/admin', [AdminAuthController::class, 'index']); //developer dapat melihat semua data admin
    Route::put('/admin/{id}', [AdminAuthController::class, 'update']); // developer dapat mengupdate data admin
    Route::post('/admin/logout', [AdminAuthController::class, 'logout']); // admin dapat logout
    Route::post('/admin/change_password', [AdminAuthController::class, 'changePassword']); // change password with token
    Route::delete('/admin/{id}', [AdminAuthController::class, 'destroy']); // admin dapat dihapus
    Route::post('/admin/request_token_change_no_hp', [AdminAuthController::class, 'requestTokenChangeNoHp']);
    Route::post('/admin/change_no_hp', [AdminAuthController::class, 'changeNoHp']);
    Route::post('/admin/request_token_change_password', [AdminAuthController::class, 'requestTokenChangePassword']); // modified this line
    Route::delete('/admin', [AdminAuthController::class, 'deleteAll']);

    // Items Categories routes (renamed from Categories)
    Route::get('/items_categories', [CategoryController::class, 'index']);
    Route::post('/items_categories', [CategoryController::class, 'store']);
    Route::get('/items_categories/{id}', [CategoryController::class, 'show']);
    Route::put('/items_categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/items_categories/{id}', [CategoryController::class, 'destroy']);
    Route::delete('/items_categories', [CategoryController::class, 'deleteAll']); // Add this line

    // Protected items routes (except index and show)
    Route::post('/items', [ItemController::class, 'store']);
    Route::put('/items/{id}', [ItemController::class, 'update']);
    Route::delete('/items/{id}', [ItemController::class, 'destroy']);
    Route::delete('/items', [ItemController::class, 'deleteAll']);

    // Items Detail routes
    Route::get('/items_detail', [ItemDetailController::class, 'index']);
    Route::post('/items_detail', [ItemDetailController::class, 'store']);
    Route::get('/items_detail/{id}', [ItemDetailController::class, 'show']);
    Route::put('/items_detail/{id}', [ItemDetailController::class, 'update']);
    Route::delete('/items_detail/{id}', [ItemDetailController::class, 'destroy']);

    // Rooms routes
    Route::get('/rooms', [RoomController::class, 'index']);
    Route::post('/rooms', [RoomController::class, 'store']);
    Route::get('/rooms/{id}', [RoomController::class, 'show']);
    Route::put('/rooms/{id}', [RoomController::class, 'update']);
    Route::delete('/rooms/{id}', [RoomController::class, 'destroy']);

    // Rooms Detail routes
    Route::get('/rooms_detail', [RoomDetailController::class, 'index']);
    Route::post('/rooms_detail', [RoomDetailController::class, 'store']);
    Route::get('/rooms_detail/{id}', [RoomDetailController::class, 'show']);
    Route::put('/rooms_detail/{id}', [RoomDetailController::class, 'update']);
    Route::delete('/rooms_detail/{id}', [RoomDetailController::class, 'destroy']);

    // Rooms Categories routes
    Route::get('/rooms_categories', [RoomCategoryController::class, 'index']);
    Route::post('/rooms_categories', [RoomCategoryController::class, 'store']);
    Route::get('/rooms_categories/{id}', [RoomCategoryController::class, 'show']);
    Route::put('/rooms_categories/{id}', [RoomCategoryController::class, 'update']);
    Route::delete('/rooms_categories/{id}', [RoomCategoryController::class, 'destroy']);
    Route::delete('/rooms_categories', [RoomCategoryController::class, 'deleteAll']);

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

    // Activities Log routes
    Route::get('/activities_log', [ActivityLogController::class, 'index']);
    Route::post('/activities_log', [ActivityLogController::class, 'store']);
    Route::get('/activities_log/{id}', [ActivityLogController::class, 'show']);
    Route::put('/activities_log/{id}', [ActivityLogController::class, 'update']);
    Route::delete('/activities_log/{id}', [ActivityLogController::class, 'destroy']);

    // Room Reviews routes
    Route::get('/rooms_reviews', [RoomReviewController::class, 'index']);
    Route::post('/rooms_reviews', [RoomReviewController::class, 'store']);
    Route::get('/rooms_reviews/{id}', [RoomReviewController::class, 'show']);
    Route::put('/rooms_reviews/{id}', [RoomReviewController::class, 'update']);
    Route::delete('/rooms_reviews/{id}', [RoomReviewController::class, 'destroy']);

    // Locations routes
    Route::middleware('api')->group(function() {
        Route::get('/locations', [LocationController::class, 'index']);
        Route::post('/locations', [LocationController::class, 'store']);
        Route::get('/locations/{id}', [LocationController::class, 'show']);
        Route::put('/locations/{id}', [LocationController::class, 'update']);
        Route::delete('/locations/{id}', [LocationController::class, 'destroy']);
    });
});
