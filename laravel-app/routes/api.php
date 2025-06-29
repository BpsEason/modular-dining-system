<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth:sanctum', 'check.tenant', 'log.api.request'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // Load module routes
    require base_path('modules/CustomerProfile/Routes/api.php');
    require base_path('modules/PosCore/Routes/api.php');
    require base_path('modules/Marketing/Routes/api.php');
});
