<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);

    Route::view('orders', 'admin.orders.index')->name('orders.index');
    Route::view('orders/{order}', 'admin.orders.index')->name('orders.show');
    Route::view('orders/{order}/edit', 'admin.orders.index')->name('orders.edit');
    Route::view('settings', 'admin.settings.index')->name('settings');

    Route::get('logout', function () {
        return redirect(env('FRONTEND_URL') . '/login?logout=1');
    })->name('logout');
});

// // category route:
// Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
// Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
// Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
// Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

// Public 404 Fallback
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});