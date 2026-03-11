<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderApiController;

Route::get('/orders', [OrderApiController::class, 'index']);
