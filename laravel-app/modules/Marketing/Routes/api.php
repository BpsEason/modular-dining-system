<?php

use Illuminate\Support\Facades\Route;
use Modules\Marketing\Http\Controllers\NotificationController;

Route::prefix('notifications')->group(function() {
    Route::post('/send', [NotificationController::class, 'send'])->name('notifications.send')->middleware('permission:notification.send');
});
