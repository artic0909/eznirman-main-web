<?php

use App\Http\Controllers\admin\Auth\AuthController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\MachineryController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\MaterialCodeController;
use App\Http\Controllers\Admin\MaterialPurchaseController;
use App\Http\Controllers\Admin\MaterialConsumeController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest:admin'])->prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.verify');
});

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Machinery Management
    Route::prefix('machinery')->name('machinery.')->group(function () {
        // Categories
        Route::get('/machine-category', [MachineryController::class, 'machineCategoryView'])->name('machine-category');
        Route::post('/machine-category', [MachineryController::class, 'machineCategoryStore'])->name('machine-category.store');
        Route::put('/machine-category/{id}', [MachineryController::class, 'machineCategoryUpdate'])->name('machine-category.update');
        Route::delete('/machine-category/{id}', [MachineryController::class, 'machineCategoryDelete'])->name('machine-category.delete');

        // Add/Manage Machinery
        Route::get('/add-machinery', [MachineryController::class, 'addMachineryView'])->name('add-machinery');
        Route::post('/add-machinery', [MachineryController::class, 'machineryStore'])->name('machinery.store');
        Route::post('/bulk-delete', [MachineryController::class, 'machineryBulkDelete'])->name('machinery.bulk-delete');
        Route::put('/add-machinery/{id}', [MachineryController::class, 'machineryUpdate'])->name('machinery.update');
        Route::delete('/add-machinery/{id}', [MachineryController::class, 'machineryDelete'])->name('machinery.delete');

        // Transfer Machinery
        Route::get('/transfer-machinery', [MachineryController::class, 'transferMachineryView'])->name('transfer-machinery');
        Route::post('/transfer-machinery', [MachineryController::class, 'transferStore'])->name('transfer.store');

        // Working Sites
        Route::get('/working-sites', [MachineryController::class, 'workingSitesView'])->name('working-sites');
        Route::post('/working-sites', [MachineryController::class, 'workingSiteStore'])->name('working-sites.store');
        Route::put('/working-sites/{id}', [MachineryController::class, 'workingSiteUpdate'])->name('working-sites.update');
        Route::delete('/working-sites/{id}', [MachineryController::class, 'workingSiteDelete'])->name('working-sites.delete');
    });

    // Purchase Register (Materials)
    Route::prefix('purchase')->name('purchase.')->group(function () {
        Route::resource('units', UnitController::class)->except(['create', 'show', 'edit']);
        Route::resource('product-categories', ProductCategoryController::class)->except(['create', 'show', 'edit']);
        Route::resource('material-codes', MaterialCodeController::class)->except(['create', 'show', 'edit']);
        Route::resource('material-purchases', MaterialPurchaseController::class)->except(['create', 'show', 'edit']);
        Route::resource('material-consumes', MaterialConsumeController::class)->only(['index', 'store', 'destroy']);
        Route::get('material-consumes/get-stock-locations/{purchase_id}', [MaterialConsumeController::class, 'getStockLocations'])->name('material-consumes.locations');
    });
});