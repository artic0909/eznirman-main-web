<?php

use App\Http\Controllers\admin\Auth\AuthController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\machinery\MachineryController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.verify');
});

// admin guard routes
Route::middleware(['auth:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Machinery
    Route::get('/machinery/machine-category', [MachineryController::class, 'machineCategoryView'])->name('admin.machinery.machine-category');

    // Add Machinery
    Route::get('/machinery/add-machinery', [MachineryController::class, 'addMachineryView'])->name('admin.machinery.add-machinery');

    // Transfer Machinery
    Route::get('/machinery/transfer-machinery', [MachineryController::class, 'transferMachineryView'])->name('admin.machinery.transfer-machinery');
});