<?php
 
 use App\Http\Controllers\accounts\Auth\AccountAuthController;
 use App\Http\Controllers\Accounts\DashboardController;
 use Illuminate\Support\Facades\Route;
 use App\Http\Controllers\Accounts\ProfileController;
 use App\Http\Controllers\Accounts\AccountcodeController;
 
 Route::middleware(['guest:account'])->prefix('account')->group(function () {
     Route::get('/login', [AccountAuthController::class, 'loginView'])->name('account.login');
     Route::post('/login', [AccountAuthController::class, 'login'])->name('account.login.verify');
 });
 
 Route::middleware(['auth:account'])->prefix('account')->name('account.')->group(function () {
     Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 
     // Profile Settings
     Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
     Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
     
     // Account Codes CRUD
     Route::get('/accountcode', [AccountcodeController::class, 'index'])->name('accountcode.index');
     Route::post('/accountcode', [AccountcodeController::class, 'store'])->name('accountcode.store');
     Route::put('/accountcode/{id}', [AccountcodeController::class, 'update'])->name('accountcode.update');
     Route::delete('/accountcode/{id}', [AccountcodeController::class, 'destroy'])->name('accountcode.destroy');
 
     Route::get('/logout', [AccountAuthController::class, 'logout'])->name('logout');
 });
