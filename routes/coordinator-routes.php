<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\IsCoordinator;
use App\Http\Controllers\admin\Auth\AuthController;
use App\Http\Controllers\Coordinator\CoordinatorDashboardController;

Route::middleware(['auth:web', IsCoordinator::class])->prefix('coordinator')->name('coordinator.')->group(function () {
    Route::get('/dashboard', [CoordinatorDashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
});