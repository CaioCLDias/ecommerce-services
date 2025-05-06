<?php

use App\Http\Controllers\ShipmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('shipments')->group(function () {
    Route::post('/{id}', [ShipmentController::class, 'updateStatus']);
   
});