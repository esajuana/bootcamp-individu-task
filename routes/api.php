<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
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


// untuk register user
Route::post('register', [RegisterController::class, 'register']);

// untuk login user
Route::post('login', [LoginController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    // product routes
    Route::put('/products/restore/{id}', [ProductController::class, 'restore']);
    Route::get('products/deleted', [ProductController::class, 'getSoftDelete']);
    Route::resource('products', ProductController::class);

    // category routes
    Route::put('/categories/restore/{id}', [CategoryController::class, 'restore']);
    Route::get('categories/deleted', [CategoryController::class, 'trash']);
    Route::resource('categories', CategoryController::class);

    // brand routes
    Route::put('/brand/restore/{id}', [BrandController::class, 'restore']);
    Route::get('brand/deleted', [BrandController::class, 'trash']);
    Route::resource('brand', BrandController::class);

    // untuk logout
    Route::post('logout', [LogoutController::class, 'logout']);
});
