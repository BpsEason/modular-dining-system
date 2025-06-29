<?php

use Illuminate\Support\Facades\Route;
use Modules\PosCore\Http\Controllers\OrderController;

Route::prefix('orders')->group(function() {
    Route::get('/', [OrderController::class, 'index'])->name('orders.index')->middleware('permission:order.read');
    Route::post('/', [OrderController::class, 'store'])->name('orders.store')->middleware('permission:order.create');
    Route::get('/{order}', [OrderController::class, 'show'])->name('orders.show')->middleware('permission:order.read');
});
