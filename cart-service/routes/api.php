<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'show']);
    Route::post('/add', [CartController::class, 'store']);
    Route::delete('/remove/{product_id}', [CartController::class, 'remove']);
    Route::delete('/clear/{user_id}', [CartController::class, 'clear']);
    Route::post('/checkout', [CartController::class, 'checkout']);
});