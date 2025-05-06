<?php

use App\Http\Controllers\OrderControlle;
use Illuminate\Support\Facades\Route;

Route::prefix('orders')->group(function () {
    Route::get('/', [OrderControlle::class, 'index']);
    Route::get('/{order_id}', [OrderControlle::class, 'show']);
});