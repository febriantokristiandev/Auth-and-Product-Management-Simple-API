<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
 

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::get('products/{product}', [ProductController::class, 'show']);
Route::get('products', [ProductController::class, 'index']); 

Route::middleware('auth:sanctum')->group(function () {
    // Routes for authenticated users
    Route::post('logout', [AuthController::class, 'logout']);

    // Admin routes
    Route::middleware('admin')->group(function () {
        Route::post('products', [ProductController::class, 'store']);
        Route::put('products/{product}', [ProductController::class, 'update']);
        Route::delete('products/{product}', [ProductController::class, 'destroy']);
    });
});
