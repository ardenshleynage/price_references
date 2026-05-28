<?php

use App\Http\Controllers\UsersController;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Dashboard\Index;
use App\Models\User;
use Illuminate\Support\Facades\Route;

/* Route::get('/', function () { */
/*     return view('welcome'); */
/* }); */

Route::get('/', function () {
    if (Session::has('user_id') || Auth::check()) {
        $user = Auth::user();
        if (! $user && Session::has('user_id')) {
            $user = User::find(Session::get('user_id'));
        }
        if ($user) {
            return match ((int) $user->role) {
                1 => redirect()->route('super_admin_home'),
                2 => redirect()->route('admins_home'),
                3 => redirect()->route('readers_home'),
                default => redirect()->route('login'),
            };
        }
    }

    return redirect()->route('login');
})->name('home');

Route::get('/login', Login::class)->name('login');
Route::get('/password/reset/{token}', ResetPassword::class)->name('password.reset');
Route::get('/password/forgot', ForgotPassword::class)->name('password.request');

Route::middleware('auth')->group(function () {
    Route::get('/super_admin/dashboard', Index::class)->name('super_admin_home');
    Route::get('/admins/dashboard', Index::class)->name('admins_home');
    Route::get('/readers/dashboard', Index::class)->name('readers_home');
    Route::get('/super_admin/users', App\Livewire\Users\Index::class)->name('super_admin_users');
    Route::get('/super_admin/categories', App\Livewire\Categories\Index::class)->name('super_admin_categories');
    Route::get('/admins/categories', App\Livewire\Categories\Index::class)->name('admins_categories');
    Route::get('/readers/categories', App\Livewire\Categories\Index::class)->name('readers_categories');
    Route::get('/super_admin/branches', App\Livewire\Branches\Index::class)->name('super_admin_branches');
    Route::get('/admins/branches', App\Livewire\Branches\Index::class)->name('admins_branches');
    Route::get('/readers/branches', App\Livewire\Branches\Index::class)->name('readers_branches');
    Route::get('/super_admin/products', App\Livewire\Products\Index::class)->name('super_admin_products');
    Route::get('/admins/products', App\Livewire\Products\Index::class)->name('admins_products');
    Route::get('/readers/products', App\Livewire\Products\Index::class)->name('readers_products');
    Route::get('/super_admin/profile', App\Livewire\Profile\Index::class)->name('super_admin_profile');
    Route::get('/admins/profile', App\Livewire\Profile\Index::class)->name('admins_profile');
    Route::get('/readers/profile', App\Livewire\Profile\Index::class)->name('readers_profile');
    Route::get('/super_admin/search', App\Livewire\Search\Index::class)->name('super_admin_search');
    Route::get('/admins/search', App\Livewire\Search\Index::class)->name('admins_search');
    Route::get('/readers/search', App\Livewire\Search\Index::class)->name('readers_search');

    Route::post('/logout', [UsersController::class, 'logout'])->name('logout');
});
