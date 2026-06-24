<?php

use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// ========================================
// PUBLIC ROUTES (No Authentication)
// ========================================

// Authentication Routes
Route::post('/register', [AuthController::class, 'register'])
    ->name('api.register');
Route::post('/login', [AuthController::class, 'login'])
    ->name('api.login');

// Social Login Routes
Route::get('/auth/{provider}/redirect', [\App\Http\Controllers\Api\SocialAuthController::class, 'redirectToProvider'])
    ->name('api.auth.social.redirect');
Route::get('/auth/{provider}/callback', [\App\Http\Controllers\Api\SocialAuthController::class, 'handleProviderCallback'])
    ->name('api.auth.social.callback');

// Public Product Routes (Customers can view products)
Route::get('/products', [ProductController::class, 'index'])
    ->name('api.products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('api.products.show');


// ========================================
// SHARED AUTH ROUTES (Any Authenticated User)
// ========================================

Route::middleware('auth:sanctum')->group(function () {

    // Profile & Logout (available to both admin and customer)
    Route::get('/profile', [AuthController::class, 'profile'])
        ->name('api.profile.show');
    Route::post('/profile', [AuthController::class, 'updateProfile'])
        ->name('api.profile.update');
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('api.logout');

});


// ========================================
// CUSTOMER-ONLY ROUTES (Auth + Customer Role Required)
// ========================================

Route::middleware(['auth:sanctum', 'customer'])->group(function () {

    // Customer Orders
    Route::get('/orders', [\App\Http\Controllers\Api\OrderController::class, 'index'])
        ->name('api.orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Api\OrderController::class, 'show'])
        ->name('api.orders.show');
    Route::post('/orders/{order}/cancel', [\App\Http\Controllers\Api\OrderController::class, 'cancel'])
        ->name('api.orders.cancel');

});


// ========================================
// ADMIN ROUTES (Auth + Admin Role Required)
// ========================================

Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {

    // Admin Categories Management
    Route::post('/categories', [CategoryController::class, 'store'])
        ->name('api.admin.categories.store');
    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('api.admin.categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])
        ->name('api.admin.categories.show');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])
        ->name('api.admin.categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
        ->name('api.admin.categories.destroy');

    // Admin Products Management
    Route::post('/products', [ProductController::class, 'store'])
        ->name('api.admin.products.store');
    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->name('api.admin.products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->name('api.admin.products.destroy');


    // Settings page
    Route::get('/settings', [SettingsController::class, 'index'])
        ->name('admin.settings');
    
    // Update settings (fullname, email, password)
    Route::post('/settings', [SettingsController::class, 'update'])
        ->name('admin.settings.update');
});