<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminAuthController;

Route::post('/admin/login', [AdminAuthController::class, 'login']); // user dapat login sebagai admin

Route::middleware('auth:api')->group(function () {
    Route::get('/admin/{id}', [AdminAuthController::class, 'show']); // developer dapat melihat data admin
    Route::get('/admin', [AdminAuthController::class, 'index']); //developer dapat melihat semua data admin
    Route::put('/admin/{id}', [AdminAuthController::class, 'update']); // developer dapat mengupdate data admin
    Route::post('/admin/logout', [AdminAuthController::class, 'logout']); // admin dapat logout
});
