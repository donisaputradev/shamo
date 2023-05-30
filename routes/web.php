<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthController::class, 'login_view'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::get('register', [AuthController::class, 'register_view'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register');
});

Route::middleware(['auth'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', [DashboardController::class, 'index'])->name('index');
    Route::get('/transaction/{transaction}', [DashboardController::class, 'edit'])->name('transaction');
    Route::put('/transaction/{transaction}', [DashboardController::class, 'update'])->name('transaction');

    Route::get('category', [ProductCategoryController::class, 'index'])->name('category');
    Route::get('category/create', [ProductCategoryController::class, 'create'])->name('category/create');
    Route::post('category/create', [ProductCategoryController::class, 'store'])->name('category/create');
    Route::get('category/{productCategory}/edit', [ProductCategoryController::class, 'edit'])->name('category/edit');
    Route::put('category/{productCategory}/edit', [ProductCategoryController::class, 'update'])->name('category/edit');
    Route::delete('category/{productCategory}/delete', [ProductCategoryController::class, 'delete'])->name('category/delete');

    Route::get('product', [ProductController::class, 'index'])->name('product');
    Route::get('product/create', [ProductController::class, 'create'])->name('product/create');
    Route::post('product/create', [ProductController::class, 'store'])->name('product/create');
    Route::get('product/{product}/edit', [ProductController::class, 'edit'])->name('product/edit');
    Route::put('product/{product}/edit', [ProductController::class, 'update'])->name('product/edit');
    Route::delete('product/{product}/delete', [ProductController::class, 'delete'])->name('product/delete');

    Route::get('gallery', [GalleryController::class, 'index'])->name('gallery');
    Route::get('gallery/create', [GalleryController::class, 'create'])->name('gallery/create');
    Route::post('gallery/create', [GalleryController::class, 'store'])->name('gallery/create');
    Route::get('gallery/{gallery}/edit', [GalleryController::class, 'edit'])->name('gallery/edit');
    Route::put('gallery/{gallery}/edit', [GalleryController::class, 'update'])->name('gallery/edit');
    Route::delete('gallery/{gallery}/delete', [GalleryController::class, 'delete'])->name('gallery/delete');

    Route::get('user', [ProfileController::class, 'index'])->name('user');
    Route::put('user', [ProfileController::class, 'update'])->name('user');
});
