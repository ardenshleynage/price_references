<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [ApiController::class, 'login']);
Route::post('/logout', [ApiController::class, 'logout']);
// Routes protégées
Route::middleware('api.auth')->group(function () {
    Route::get('/products', [ApiController::class, 'products']);
    Route::get('/categories', [ApiController::class, 'categories']);
    Route::get('/branches', [ApiController::class, 'branches']);
    Route::get('/user', [ApiController::class, 'user']);
});
