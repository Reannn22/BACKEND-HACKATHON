<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminAuthController;

Route::post('/admin/login', [AdminAuthController::class, 'login']); // user dapat login sebagai admin
Route::post('/admin/request_token_forget_password', [AdminAuthController::class, 'requestTokenForgetPassword']);
Route::post('/admin/forgot_password', [AdminAuthController::class, 'forgotPassword']);

Route::middleware('auth:api')->group(function () {
    Route::get('/admin/{id}', [AdminAuthController::class, 'show']); // developer dapat melihat data admin
    Route::get('/admin', [AdminAuthController::class, 'index']); //developer dapat melihat semua data admin
    Route::put('/admin/{id}', [AdminAuthController::class, 'update']); // developer dapat mengupdate data admin
    Route::post('/admin/logout', [AdminAuthController::class, 'logout']); // admin dapat logout
    Route::post('/admin/{id}/change_password', [AdminAuthController::class, 'changePassword']); // change password with token
    Route::delete('/admin/{id}', [AdminAuthController::class, 'destroy']); // admin dapat dihapus
    Route::post('/admin/{id}/request_token_change_no_hp', [AdminAuthController::class, 'requestTokenChangeNoHp']);
    Route::post('/admin/{id}/change_no_hp', [AdminAuthController::class, 'changeNoHp']);
    Route::post('/admin/{id}/request_token_change_password', [AdminAuthController::class, 'requestTokenChangePassword']);
});
