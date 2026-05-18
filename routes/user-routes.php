<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\User\DashboardController;

Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('user.dashboard');
    Route::post('/user/transaction', [DashboardController::class, 'storeTransaction'])->name('user.transaction.store');
    
    // Additional sidebar paths
    Route::get('/user/credits', [DashboardController::class, 'credits'])->name('user.credits');
    Route::get('/user/debits', [DashboardController::class, 'debits'])->name('user.debits');
    Route::get('/user/transactions', [DashboardController::class, 'transactions'])->name('user.transactions');
    Route::get('/user/profile', [DashboardController::class, 'profile'])->name('user.profile');
    Route::post('/user/profile/update', [DashboardController::class, 'profileUpdate'])->name('user.profile.update');
});