<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [ApiController::class, 'login']);
Route::post('/logout', [ApiController::class, 'logout']);
// Routes protégées
Route::middleware('api.auth')->group(function () {
    Route::get('/products', [ApiController::class, 'products']);
    Route::get('/products/counts', [ApiController::class, 'productsCounts']);
    Route::post('/products/create', [ApiController::class, 'createProduct']);
    Route::post('/products/update', [ApiController::class, 'updateProduct']);
    Route::post('/products/block', [ApiController::class, 'blockProduct']);
    Route::post('/products/unblock', [ApiController::class, 'unblockProduct']);
    Route::post('/products/delete', [ApiController::class, 'deleteProduct']);
    Route::post('/products/restore', [ApiController::class, 'restoreProduct']);
    Route::post('/products/erase', [ApiController::class, 'eraseProduct']);
    Route::get('/categories', [ApiController::class, 'categories']);
    Route::get('/branches', [ApiController::class, 'branches']);
    Route::get('/search', [ApiController::class, 'search']);
    Route::get('/user', [ApiController::class, 'user']);
    Route::get('/dashboard/stats', [ApiController::class, 'dashboardStats']);
    Route::put('/user/update/username', [ApiController::class, 'updateUsername']);
    Route::put('/user/update/email', [ApiController::class, 'updateEmail']);
    Route::put('/user/update/password', [ApiController::class, 'updatePassword']);
});
