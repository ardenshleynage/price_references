<?php

use App\Http\Controllers\AdminsController;
use App\Http\Controllers\ReadersController;
use App\Http\Controllers\Appcontroller;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

$mid = 'checkusers';

/*-----------------------Home-----------------------*/
Route::get('/', function () {
    // Si l'utilisateur est connecté (session ou remember token), rediriger vers son dashboard
    if (Session::has('user_id') || Auth::check()) {
        $user = Auth::user();

        if (! $user && Session::has('user_id')) {
            $user = \App\Models\User::find(Session::get('user_id'));
        }

        if ($user) {
            switch ($user->role) {
                case 1: // Super Admin
                    return redirect()->route('super_admin_home');
                case 2: // Admin
                    return redirect()->route('admins_home');
                case 3: // Utilisateur
                    return redirect()->route('readers_home');
            }
        }
    }

    // Pas connecté, afficher la page de connexion
    return redirect()->route('login');
})->name('home');


/*-----------------------
 *-----------------------
 *-----------------------
 *-----------------------
 * Super Admins Dashboard
 *-----------------------
 *-----------------------
 *-----------------------
 -----------------------*/
/*-----------------------Super Admins Home-----------------------*/
Route::get('/super_admin_home', [SuperAdminController::class, 'superAdminHome'])
    ->name('super_admin_home')->middleware($mid);


/*-----------------------Super Admins profile-----------------------*/
Route::get('/super_admin_profile', [SuperAdminController::class, 'superAdminProfile'])
    ->name('super_admin_profile')->middleware($mid);

/*-----------------------Super Admins Search-----------------------*/
Route::get('/super_admin_search', [SuperAdminController::class, 'superAdminSearch'])
    ->name('super_admin_search')->middleware($mid);

/*-----------------------Super Admins Categories-----------------------*/
Route::get('/super_admin_categories', [CategoriesController::class, 'superAdminCategories'])
    ->name('super_admin_categories')->middleware($mid);
Route::get('/super_admin_categories_active', [CategoriesController::class, 'superAdminCategoriesActive'])
    ->name('super_admin_categories_active')->middleware($mid);
Route::get('/super_admin_categories_blocked', [CategoriesController::class, 'superAdminCategoriesBlocked'])
    ->name('super_admin_categories_blocked')->middleware($mid);
Route::get('/super_admin_categories_deleted', [CategoriesController::class, 'superAdminCategoriesDeleted'])
    ->name('super_admin_categories_deleted')->middleware($mid);
Route::post('/create/categories', [CategoriesController::class, 'createCategories'])
    ->name('categories.create_category')->middleware($mid);
Route::post('/categories/block', [CategoriesController::class, 'blockCategory'])
    ->name('categories.block')->middleware($mid);
Route::post('/categories/unblock', [CategoriesController::class, 'unblockCategory'])
    ->name('categories.unblock')->middleware($mid);
Route::post('/categories/delete', [CategoriesController::class, 'deleteCategory'])
    ->name('categories.delete')->middleware($mid);
Route::post('/categories/restore', [CategoriesController::class, 'restoreCategory'])
    ->name('categories.restore')->middleware($mid);
Route::post('/categories/erase', [CategoriesController::class, 'permaneneDeleteCategory'])
    ->name('categories.erase')->middleware($mid);
Route::post('/categories/{action}', [CategoriesController::class, 'handleCategoryAction'])
    ->name('categories.handleAction')
    ->where('action', 'block|unblock|delete|restore|erase')
    ->middleware($mid);
Route::post('/categories/update', [CategoriesController::class, 'updateCategories'])
    ->name('categories.update')
    ->middleware($mid);
Route::get('/categories/edit-from-search/{id}', [CategoriesController::class, 'editFromSearch'])
    ->name('categories.edit_from_search')
    ->middleware($mid);
Route::post('/categories/update-from-search', [CategoriesController::class, 'updateFromSearch'])
    ->name('categories.update_from_search')
    ->middleware($mid);

/*-----------------------Super Admins Branches-----------------------*/
Route::get('/super_admin_branches', [BranchController::class, 'superAdminBranches'])
    ->name('super_admin_branches')->middleware($mid);
Route::get('/super_admin_branches_active', [BranchController::class, 'superAdminBranchesActive'])
    ->name('super_admin_branches_active')->middleware($mid);
Route::get('/super_admin_branches_blocked', [BranchController::class, 'superAdminBranchesBlocked'])
    ->name('super_admin_branches_blocked')->middleware($mid);
Route::get('/super_admin_branches_deleted', [BranchController::class, 'superAdminBranchesDeleted'])
    ->name('super_admin_branches_deleted')->middleware($mid);
Route::post('/create/branches', [BranchController::class, 'createBranches'])
    ->name('branches.create_branche')->middleware($mid);
Route::post('/branches/block', [BranchController::class, 'blockBranche'])
    ->name('branches.block')->middleware($mid);
Route::post('/branches/unblock', [BranchController::class, 'unblockBranche'])
    ->name('branches.unblock')->middleware($mid);
Route::post('/branches/delete', [BranchController::class, 'deleteBranche'])
    ->name('branches.delete')->middleware($mid);
Route::post('/branches/restore', [BranchController::class, 'restoreBranche'])
    ->name('branches.restore')->middleware($mid);
Route::post('/branches/erase', [BranchController::class, 'permaneneDeleteBranche'])
    ->name('branches.erase')->middleware($mid);
Route::post('/branches/{action}', [BranchController::class, 'handleBranchAction'])
    ->name('branches.handleAction')
    ->where('action', 'block|unblock|delete|restore|erase')
    ->middleware($mid);
Route::post('/branches/update', [BranchController::class, 'updateBranches'])
    ->name('branches.update')
    ->middleware($mid);
Route::get('/branches/edit-from-search/{id}', [BranchController::class, 'editFromSearch'])
    ->name('branches.edit_from_search')
    ->middleware($mid);
Route::post('/branches/update-from-search', [BranchController::class, 'updateFromSearch'])
    ->name('branches.update_from_search')
    ->middleware($mid);

/*-----------------------Super Admins Users-----------------------*/
Route::get('/super_admin_users', [SuperAdminController::class, 'superAdminUsers'])
    ->name('super_admin_users')->middleware($mid);
Route::get('/super_admin_users_active', [SuperAdminController::class, 'superAdminUsersActive'])
    ->name('super_admin_users_active')->middleware($mid);
Route::get('/super_admin_users_blocked', [SuperAdminController::class, 'superAdminUsersBlocked'])
    ->name('super_admin_users_blocked')->middleware($mid);
Route::get('/super_admin_users_deleted', [SuperAdminController::class, 'superAdminUsersDeleted'])
    ->name('super_admin_users_deleted')->middleware($mid);
Route::post('/create/users', [SuperAdminController::class, 'createUsersAdmins'])
    ->name('users.create_user')->middleware($mid);
Route::post('/users/block', [SuperAdminController::class, 'blockUser'])
    ->name('users.block')->middleware($mid);
Route::post('/users/unblock', [SuperAdminController::class, 'unblockUser'])
    ->name('users.unblock')->middleware($mid);
Route::post('/users/delete', [SuperAdminController::class, 'deleteUser'])
    ->name('users.delete')->middleware($mid);
Route::post('/users/restore', [SuperAdminController::class, 'restoreUser'])
    ->name('users.restore')->middleware($mid);
Route::post('/users/erase', [SuperAdminController::class, 'permanentDeleteUser'])
    ->name('users.erase')->middleware($mid);
Route::post('/users/{action}', [SuperAdminController::class, 'handleUserAction'])
    ->name('users.handleAction')
    ->where('action', 'block|unblock|delete|restore|erase')
    ->middleware($mid);
Route::post('/super_admin_users/update', [SuperAdminController::class, 'updateUser'])
    ->name('users.update')->middleware($mid);
Route::get('/users/edit-from-search/{id}', [SuperAdminController::class, 'editUserFromSearch'])
    ->name('users.edit_from_search')->middleware($mid);
Route::post('/users/update-from-search', [SuperAdminController::class, 'updateUserFromSearch'])
    ->name('users.update_from_search')->middleware($mid);

/*-----------------------Super Admins Products-----------------------*/
Route::get('/super_admin_products', [ProductsController::class, 'superAdminProducts'])
    ->name('super_admin_products')->middleware($mid);
Route::get('/super_admin_products_active', [ProductsController::class, 'superAdminProductsActive'])
    ->name('super_admin_products_active')->middleware($mid);
Route::get('/super_admin_products_blocked', [ProductsController::class, 'superAdminProductsBlocked'])
    ->name('super_admin_products_blocked')->middleware($mid);
Route::get('/super_admin_products_deleted', [ProductsController::class, 'superAdminProductsDeleted'])
    ->name('super_admin_products_deleted')->middleware($mid);
Route::post('/products/create', [ProductsController::class, 'createProduct'])
    ->name('products.create')
    ->middleware($mid);
Route::post('/products/block', [ProductsController::class, 'blockProducts'])
    ->name('products.block')->middleware($mid);
Route::post('/products/unblock', [ProductsController::class, 'unblockProducts'])
    ->name('products.unblock')->middleware($mid);
Route::post('/products/delete', [ProductsController::class, 'deleteProducts'])
    ->name('products.delete')->middleware($mid);
Route::post('/products/restore', [ProductsController::class, 'restoreProducts'])
    ->name('products.restore')->middleware($mid);
Route::post('/products/erase', [ProductsController::class, 'permaneneDeleteProducts'])
    ->name('products.erase')->middleware($mid);
Route::post('/products/{action}', [ProductsController::class, 'handleProductAction'])
    ->name('products.handleAction')
    ->where('action', 'block|unblock|delete|restore|erase')
    ->middleware($mid);
Route::post('/products/update', [ProductsController::class, 'updateProducts'])
    ->name('products.update')
    ->middleware($mid);
Route::get('/products/edit-from-search/{id}', [ProductsController::class, 'editFromSearch'])
    ->name('products.edit_from_search')
    ->middleware($mid);
Route::post('/products/update-from-search', [ProductsController::class, 'updateFromSearch'])
    ->name('products.update_from_search')
    ->middleware($mid);

/*-----------------------
 *-----------------------
 *-----------------------
 *-----------------------
 * Users/App
 *-----------------------
 *-----------------------
 *-----------------------
 -----------------------*/

/*-----------------------Connexion/Déconexion-----------------------*/
Route::get('/login', [Appcontroller::class, 'Login'])->name('login');
Route::post('/login/users', [UsersController::class, 'loginUsers'])->name('users.login_user');
Route::post('/logout', [UsersController::class, 'logout'])->name('logout');

/*-----------------------Password Reset-----------------------*/
Route::get('/forget_password', [Appcontroller::class, 'forgetPassword'])->name('forget_password');
Route::post('/password/email', [Appcontroller::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [Appcontroller::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [Appcontroller::class, 'reset'])->name('password.update');
Route::get('/contact_it', [Appcontroller::class, 'contactIt'])->name('contact_it');

/*-----------------------Route pour sauvegarder l'état du sidebar-----------------------*/
Route::post('/update-sidebar-state', [UsersController::class, 'updateSidebarState'])
    ->name('sidebar.update_state')->middleware($mid);

/*-----------------------Profile-----------------------*/
Route::post('/profile/update_username', [UsersController::class, 'updateUsername'])
    ->name('profile.update_username')->middleware($mid);
Route::post('/profile/update_password', [UsersController::class, 'updatePassword'])
    ->name('profile.update_password')->middleware($mid);
Route::post('/profile/update_email', [UsersController::class, 'updateEmail'])
    ->name('profile.update_email')->middleware($mid);
Route::post('/profile/update_theme', [UsersController::class, 'updateThemeFromProfile'])
    ->name('profile.update_theme')->middleware($mid);

/*-----------------------
 *-----------------------
 *-----------------------
 *-----------------------
 *  Admins
 *-----------------------
 *-----------------------
 *-----------------------
 *-----------------------*/

/*-----------------------Admins Home-----------------------*/
Route::get('/admins_home', [AdminsController::class, 'adminsHome'])
    ->name('admins_home')->middleware($mid);

/*-----------------------Admins profile-----------------------*/
Route::get('/admins_profile', [AdminsController::class, 'adminsProfile'])
    ->name('admins_profile')->middleware($mid);

/*-----------------------Admins Search-----------------------*/
Route::get('/admins_search', [AdminsController::class, 'adminsSearch'])
    ->name('admins_search')->middleware($mid);


/*-----------------------Admins Categories-----------------------*/
Route::get('/admins_categories', [CategoriesController::class, 'adminsCategories'])
    ->name('admins_categories')->middleware($mid);
Route::get('/admins_categories_active', [CategoriesController::class, 'adminsCategoriesActive'])
    ->name('admins_categories_active')->middleware($mid);
Route::get('/admins_categories_deleted', [CategoriesController::class, 'adminsCategoriesDeleted'])
    ->name('admins_categories_deleted')->middleware($mid);
Route::post('/admins/create/categories', [CategoriesController::class, 'adminsCreateCategories'])
    ->name('admins.categories.create_category')->middleware($mid);
Route::post('/admins/categories/delete', [CategoriesController::class, 'adminsDeleteCategory'])
    ->name('admins.categories.delete')->middleware($mid);
Route::post('/admins/categories/restore', [CategoriesController::class, 'adminsRestoreCategory'])
    ->name('admins.categories.restore')->middleware($mid);
Route::post('/admins/categories/erase', [CategoriesController::class, 'adminsFakePermanentDeleteCategory'])
    ->name('admins.categories.erase')->middleware($mid);
Route::post('/admins/categories/{action}', [CategoriesController::class, 'adminshandleCategoryAction'])
    ->name('admins.categories.handleAction')
    ->where('action', 'delete|restore|fake_erase')
    ->middleware($mid);
Route::post('/admins/categories/update', [CategoriesController::class, 'adminsUpdateCategories'])
    ->name('admins.categories.update')
    ->middleware($mid);
Route::get('/admins/categories/edit-from-search/{id}', [CategoriesController::class, 'adminsEditFromSearch'])
    ->name('admins.categories.edit_from_search')
    ->middleware($mid);
Route::post('/admins/categories/update-from-search', [CategoriesController::class, 'adminsUpdateFromSearch'])
    ->name('admins.categories.update_from_search')
    ->middleware($mid);

/*-----------------------Admins Branches-----------------------*/
Route::get('/admins_branches', [BranchController::class, 'adminsBranches'])
    ->name('admins_branches')->middleware($mid);
Route::get('/admins_branches_active', [BranchController::class, 'adminsBranchesActive'])
    ->name('admins_branches_active')->middleware($mid);
Route::get('/admins_branches_deleted', [BranchController::class, 'adminsBranchesDeleted'])
    ->name('admins_branches_deleted')->middleware($mid);
Route::post('/admins/create/branches', [BranchController::class, 'adminsCreateBranches'])
    ->name('admins.branches.create_branch')->middleware($mid);
Route::post('/admins/branches/delete', [BranchController::class, 'adminsDeleteBranche'])
    ->name('admins.branches.delete')->middleware($mid);
Route::post('/admins/branches/restore', [BranchController::class, 'adminsRestoreBranche'])
    ->name('admins.branches.restore')->middleware($mid);
Route::post('/admins/branches/erase', [BranchController::class, 'adminsFakePermanentDeleteBranche'])
    ->name('admins.branches.erase')->middleware($mid);
Route::post('/admins/branches/{action}', [BranchController::class, 'adminshandleBranchAction'])
    ->name('admins.branches.handleAction')
    ->where('action', 'delete|restore|fake_erase')
    ->middleware($mid);
Route::post('/admins/branches/update', [BranchController::class, 'adminsUpdateBranches'])
    ->name('admins.branches.update')
    ->middleware($mid);
Route::get('/admins/branches/edit-from-search/{id}', [BranchController::class, 'adminsEditFromSearch'])
    ->name('admins.branches.edit_from_search')
    ->middleware($mid);
Route::post('/admins/branches/update-from-search', [BranchController::class, 'adminsUpdateFromSearch'])
    ->name('admins.branches.update_from_search')
    ->middleware($mid);

/*-----------------------Admins Products-----------------------*/
Route::get('/admins_products', [ProductsController::class, 'adminsProducts'])
    ->name('admins_products')->middleware($mid);
Route::get('/admins_products_active', [ProductsController::class, 'adminsProductsActive'])
    ->name('admins_products_active')->middleware($mid);
Route::get('/admins_products_deleted', [ProductsController::class, 'adminsProductsDeleted'])
    ->name('admins_products_deleted')->middleware($mid);
Route::post('/admins/create/products', [ProductsController::class, 'adminsCreateProduct'])
    ->name('admins.products.create_product')->middleware($mid);
Route::post('/admins/products/delete', [ProductsController::class, 'adminsDeleteProducts'])
    ->name('admins.products.delete')->middleware($mid);
Route::post('/admins/products/restore', [ProductsController::class, 'adminsRestoreProducts'])
    ->name('admins.products.restore')->middleware($mid);
Route::post('/admins/products/erase', [ProductsController::class, 'adminsFakePermanentDeleteProducts'])
    ->name('admins.products.erase')->middleware($mid);
Route::post('/admins/products/{action}', [ProductsController::class, 'adminshandleProductAction'])
    ->name('admins.products.handleAction')
    ->where('action', 'delete|restore|fake_erase')
    ->middleware($mid);
Route::post('/admins/products/update', [ProductsController::class, 'adminsUpdateProducts'])
    ->name('admins.products.update')
    ->middleware($mid);
Route::get('/admins/products/edit-from-search/{id}', [ProductsController::class, 'adminsEditFromSearch'])
    ->name('admins.products.edit_from_search')
    ->middleware($mid);
Route::post('/admins/products/update-from-search', [ProductsController::class, 'adminsUpdateFromSearch'])
    ->name('admins.products.update_from_search')
    ->middleware($mid);

/*-----------------------
 *-----------------------
 *-----------------------
 *-----------------------
 *Utilisateur/Reader
 *-----------------------
 *-----------------------
 *-----------------------
 -----------------------*/

/*-----------------------Utilisateur Home-----------------------*/
Route::get('/readers_home', [ReadersController::class, 'readersHome'])
    ->name('readers_home')->middleware($mid);

/*-----------------------Utilisateurprofile-----------------------*/
Route::get('/readers_profile', [ReadersController::class, 'readersProfile'])
    ->name('readers_profile')->middleware($mid);

/*-----------------------Utilisateur Search-----------------------*/
Route::get('/readers_search', [ReadersController::class, 'readersSearch'])
    ->name('readers_search')->middleware($mid);

/*-----------------------Utilisateur Products-----------------------*/
Route::get('/readers_products_active', [ProductsController::class, 'readersProductsActive'])
    ->name('readers_products_active')->middleware($mid);

/*-----------------------Admins Branches-----------------------*/
Route::get('/readers_branches_active', [BranchController::class, 'readersBranchesActive'])
    ->name('readers_branches_active')->middleware($mid);

/*-----------------------Admins Categories-----------------------*/
Route::get('/readers_categories_active', [CategoriesController::class, 'readersCategoriesActive'])
    ->name('readers_categories_active')->middleware($mid);
