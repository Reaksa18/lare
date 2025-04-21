<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\API\SlideController;


Route::get('/', function () {
    return response()->json(['message' => 'Welcome to the API']);
});


Route::get('/hello', function () {
    return response()->json(['message' => 'Hello from Laravel API']);
});


Route::apiResource('categories', CategoryController::class);
Route::get('/categories/{id}/products', [ProductController::class, 'productsByCategory']);


Route::apiResource('products', ProductController::class);
Route::get('/products/{id}', [ProductController::class, 'show']);



Route::middleware('auth:api')->group(function () {
    Route::get('/cart', [CartController::class, 'getCartItems']);
    Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::post('/cart/update', [CartController::class, 'updateCart']);
    Route::post('/cart/remove', [CartController::class, 'removeFromCart']);
    Route::get('cart/count', [CartController::class, 'getCartCount']);

});




Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/me', [AuthController::class, 'me'])->middleware('auth:api')->name('me');
});



Route::prefix('admin')->group(function () {
    Route::post('/register', [AdminAuthController::class, 'register'])->name('admin.register');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware('auth:admin')->name('admin.logout');
    Route::post('/refresh', [AdminAuthController::class, 'refresh'])->middleware('auth:admin')->name('admin.refresh');
    Route::post('/me', [AdminAuthController::class, 'me'])->middleware('auth:admin')->name('admin.me');
});



// routes/api.php


Route::apiResource('slides', SlideController::class);
Route::post('/slides/{id}', [SlideController::class, 'update']);









