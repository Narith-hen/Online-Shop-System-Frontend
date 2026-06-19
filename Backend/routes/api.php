<?php

use App\Http\Controllers\Api\AuthController;
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
 
// Categories 
// Route::apiResource('categories', CategoryController::class); 
}); 
// Products 
// Route::apiResource('products', ProductController::class);