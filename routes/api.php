<?php

use App\Http\Controllers\Admin\Apis\Auth\LoginController;
use App\Http\Controllers\Admin\Store\Apis\CategoryController;
use App\Http\Controllers\Admin\Store\Apis\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [LoginController::class, 'login'])->name('admin.login');

Route::group(['middleware' => 'auth:admin', 'prefix' => 'admin', 'as' => 'admin.'], function (){
    Route::group(['prefix' => 'category/', 'as' => 'category.'], function (){
        Route::post('create', [CategoryController::class, 'store'])->name('create');
        Route::post('change-status', [CategoryController::class, 'status'])->name('status');
        Route::put('update/{id}', [CategoryController::class, 'update'])->name('update');
        Route::post('index', [CategoryController::class, 'index'])->name('index');
        Route::get('without-products', [CategoryController::class, 'withoutProducts'])->name('filter.withoutProducts');
        Route::get('filter/bigger/{count}', [CategoryController::class, 'filter'])->name('filter.bigger');
        Route::get('filter/smaller/{count}', [CategoryController::class, 'filter'])->name('filter.smaller');
        Route::post('filter/status', [CategoryController::class, 'statusFilter'])->name('filter.status');
        Route::get('with-products', [CategoryController::class, 'categoryWithProducts'])->name('category.with.products');
    });
    Route::group(['prefix' => 'product/', 'as' => 'product.'], function (){
        Route::post('create', [ProductController::class, 'store'])->name('store');
        Route::post('status-change', [ProductController::class, 'status'])->name('status');
        Route::put('update/{id}', [ProductController::class, 'update'])->name('update');
        Route::delete('delete/{id}', [ProductController::class, 'delete'])->name('delete');
        Route::get('filter/bigger/{count}', [ProductController::class, 'filter'])->name('filter.bigger');
        Route::get('filter/smaller/{count}', [ProductController::class, 'filter'])->name('filter.smaller');
        Route::post('filter/status', [ProductController::class, 'getStatusProducts'])->name('filter.status');
    });
});
