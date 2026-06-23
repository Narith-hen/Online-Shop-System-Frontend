<?php

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

// Public Product Routes (Customers can view products)
Route::get('/products', [ProductController::class, 'index'])
    ->name('api.products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])
    ->name('api.products.show');


// ========================================
// CUSTOMER ROUTES (Auth Required)
// ========================================

Route::middleware('auth:sanctum')->group(function () {

    // Customer Profile
    Route::get('/profile', [AuthController::class, 'profile'])
        ->name('api.profile.show');
    Route::post('/profile', [AuthController::class, 'updateProfile'])
        ->name('api.profile.update');
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('api.logout');

    // Add more customer-specific routes here if needed
    // Route::get('/orders', [OrderController::class, 'index'])
    //     ->name('api.orders.index');
    // Route::get('/orders/{order}', [OrderController::class, 'show'])
    //     ->name('api.orders.show');

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

    // Add more admin routes here
    // Route::apiResource('orders', OrderController::class);
    // Route::get('/dashboard', [DashboardController::class, 'index']);

});
