<?php

use App\Http\Controllers\ApiController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [ApiController::class, 'login']);
Route::post('/logout', [ApiController::class, 'logout']);
Route::post('/password/forgot', [ApiController::class, 'forgotPassword']);
Route::post('/password/reset', [ApiController::class, 'resetPassword']);
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
    Route::get('/categories/counts', [ApiController::class, 'categoriesCounts']);
    Route::post('/categories/create', [ApiController::class, 'createCategory']);
    Route::post('/categories/update', [ApiController::class, 'updateCategory']);
    Route::post('/categories/block', [ApiController::class, 'blockCategory']);
    Route::post('/categories/unblock', [ApiController::class, 'unblockCategory']);
    Route::post('/categories/delete', [ApiController::class, 'deleteCategory']);
    Route::post('/categories/restore', [ApiController::class, 'restoreCategory']);
    Route::post('/categories/erase', [ApiController::class, 'eraseCategory']);
    Route::get('/branches', [ApiController::class, 'branches']);
    Route::get('/branches/counts', [ApiController::class, 'branchesCounts']);
    Route::post('/branches/create', [ApiController::class, 'createBranch']);
    Route::post('/branches/update', [ApiController::class, 'updateBranch']);
    Route::post('/branches/block', [ApiController::class, 'blockBranch']);
    Route::post('/branches/unblock', [ApiController::class, 'unblockBranch']);
    Route::post('/branches/delete', [ApiController::class, 'deleteBranch']);
    Route::post('/branches/restore', [ApiController::class, 'restoreBranch']);
    Route::post('/branches/erase', [ApiController::class, 'eraseBranch']);
    Route::get('/search', [ApiController::class, 'search']);
    Route::get('/user', [ApiController::class, 'user']);
    Route::get('/dashboard/stats', [ApiController::class, 'dashboardStats']);
    Route::put('/user/update/username', [ApiController::class, 'updateUsername']);
    Route::put('/user/update/email', [ApiController::class, 'updateEmail']);
    Route::put('/user/update/password', [ApiController::class, 'updatePassword']);
    Route::put('/user/update/theme', [ApiController::class, 'updateTheme']);
    Route::get('/users', [ApiController::class, 'users']);
    Route::get('/users/counts', [ApiController::class, 'usersCounts']);
    Route::post('/users/create', [ApiController::class, 'createUser']);
    Route::post('/users/update', [ApiController::class, 'updateUser']);
    Route::post('/users/block', [ApiController::class, 'blockUser']);
    Route::post('/users/unblock', [ApiController::class, 'unblockUser']);
    Route::post('/users/delete', [ApiController::class, 'deleteUser']);
    Route::post('/users/restore', [ApiController::class, 'restoreUser']);
    Route::post('/users/erase', [ApiController::class, 'eraseUser']);
});
