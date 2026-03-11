<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::resource('customers', CustomerController::class)->except(['show']);
Route::resource('products', ProductController::class)->except(['show', 'destory']);

Route::resource('orders', OrderController::class)->only(['index', 'create', 'store', 'show']);
Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');
