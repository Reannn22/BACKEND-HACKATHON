<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;

Route::get('/test/db', [TestController::class, 'testDatabase']);
Route::get('/test/user', [TestController::class, 'testUser']);
