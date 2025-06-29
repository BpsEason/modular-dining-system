<?php

use Illuminate\Support\Facades\Route;
use Modules\CustomerProfile\Http\Controllers\CustomerController;

Route::prefix('customers')->group(function() {
    Route::get('/', [CustomerController::class, 'index'])->name('customers.index')->middleware('permission:customer.read');
    Route::post('/', [CustomerController::class, 'store'])->name('customers.store')->middleware('permission:customer.create');
    Route::get('/{customer}', [CustomerController::class, 'show'])->name('customers.show')->middleware('permission:customer.read');
});
