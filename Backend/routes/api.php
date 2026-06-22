<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


/* 
|-------------------------------------------------------------------------- 
| Public Routes 
|-------------------------------------------------------------------------- 
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {

    // Authentication 
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile', [AuthController::class, 'updateProfile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    //  protect route (private)
    Route::post('/categories', [CategoryController::class, 'store'])
        ->name('api.categories.store');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])
        ->name('api.categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
        ->name('api.categories.destroy');

    // public route
    Route::get('/categories', [CategoryController::class, 'index'])
        ->name('api.categories.index');
    Route::get('/categories/{category}', [CategoryController::class, 'show'])
        ->name('api.categories.show');

    // Products 
    Route::apiResource('products', ProductController::class);
});
