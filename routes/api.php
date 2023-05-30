<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\TransactionController;
use App\Http\Controllers\Api\UserController;
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

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(
    function () {
        /// ENDPOINT PRODUCT
        Route::get('products', [ProductController::class, 'all']);
        Route::get('categories', [ProductCategoryController::class, 'all']);

        /// ENDPOINT USER
        Route::get('user', [UserController::class, 'fetch']);
        Route::post('user', [UserController::class, 'update']);
        Route::post('logout', [AuthController::class, 'logout']);

        /// ENDPOINT TRANSAKSI
        Route::get('transaction', [TransactionController::class, 'all']);
        Route::post('checkout', [TransactionController::class, 'submit']);
    }
);
