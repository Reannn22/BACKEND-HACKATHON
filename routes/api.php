<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\AdminAuthController;

Route::get('/test/db', [TestController::class, 'testDatabase']);
Route::get('/test/user', [TestController::class, 'testUser']);
Route::post('/admin/login', [AdminAuthController::class, 'login']);
