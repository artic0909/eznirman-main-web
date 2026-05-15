<?php

use App\Http\Controllers\accounts\Auth\AccountAuthController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest:account'])->prefix('account')->group(function () {
    Route::get('/login', [AccountAuthController::class, 'loginView'])->name('account.login');
    Route::post('/login', [AccountAuthController::class, 'login'])->name('account.login.verify');
});

Route::middleware(['auth:account'])->prefix('account')->name('account.')->group(function () {
    Route::get('/dashboard', function() {
        return "Welcome to Accounts Dashboard";
    })->name('dashboard');
    
    Route::get('/logout', [AccountAuthController::class, 'logout'])->name('logout');
});
