<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\AuthController;
use App\Http\Controllers\api\DashboardController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/dashboard', [AuthController::class, 'dashboard']);
    
    // Transactions
    Route::post('/user/transaction', [DashboardController::class, 'storeTransaction']);
    Route::get('/user/credits', [DashboardController::class, 'credits']);
    Route::get('/user/debits', [DashboardController::class, 'debits']);
    Route::get('/user/transactions', [DashboardController::class, 'transactions']);
    
    // Profile
    Route::get('/user/profile', [DashboardController::class, 'profile']);
    Route::post('/user/profile', [DashboardController::class, 'profileUpdate']);
    
    // Data for forms
    Route::get('/user/send-money-data', [DashboardController::class, 'sendMoneyData']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
