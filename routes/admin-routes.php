<?php

use App\Http\Controllers\admin\Auth\AuthController;
use App\Http\Controllers\admin\DashboardController;
use App\Http\Controllers\admin\ProfileController;
use App\Http\Controllers\admin\MachineryController;
use App\Http\Controllers\admin\UnitController;
use App\Http\Controllers\admin\ProductCategoryController;
use App\Http\Controllers\admin\MaterialCodeController;
use App\Http\Controllers\admin\MaterialPurchaseController;
use App\Http\Controllers\admin\MaterialConsumeController;
use App\Http\Controllers\admin\SkillController;
use App\Http\Controllers\admin\DesignationController;
use App\Http\Controllers\admin\HRManagementController;
use Illuminate\Support\Facades\Route;

Route::middleware(['guest:admin'])->prefix('admin')->group(function () {
    Route::get('/login', [AuthController::class, 'loginView'])->name('admin.login');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.verify');
});

Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile Settings
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

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

        // Damaged Machinery
        Route::get('/damaged', [MachineryController::class, 'damagedMachineryView'])->name('damaged');
        Route::get('/damaged/{id}', [MachineryController::class, 'damagedMachineryShow'])->name('damaged.show');

        // Running Machinery
        Route::get('/running', [MachineryController::class, 'runningMachineryView'])->name('running');
        Route::get('/running/{id}', [MachineryController::class, 'runningMachineryShow'])->name('running.show');

        // Repair Machinery
        Route::get('/repair', [MachineryController::class, 'repairMachineryView'])->name('repair');
        Route::get('/repair/{id}', [MachineryController::class, 'repairMachineryShow'])->name('repair.show');
    });

    // Purchase Register (Materials)
    Route::prefix('purchase')->name('purchase.')->group(function () {
        Route::resource('units', UnitController::class)->except(['create', 'show', 'edit']);
        Route::resource('product-categories', ProductCategoryController::class)->except(['create', 'show', 'edit']);
        Route::resource('material-codes', MaterialCodeController::class)->except(['create', 'show', 'edit']);
        Route::resource('material-purchases', MaterialPurchaseController::class)->except(['create', 'show', 'edit']);
        Route::resource('unauthorized-purchases', \App\Http\Controllers\admin\UnauthorizedPurchaseController::class)->except(['create', 'show', 'edit', 'store', 'update']);
        Route::resource('material-consumes', MaterialConsumeController::class)->only(['index', 'store', 'destroy']);
        Route::get('material-consumes/get-stock-locations/{purchase_id}', [MaterialConsumeController::class, 'getStockLocations'])->name('material-consumes.locations');
    });

    // HR Management
    Route::prefix('hrmanagement')->name('hrmanagement.')->group(function () {
        Route::resource('skills', SkillController::class)->except(['create', 'show', 'edit']);
        Route::post('skills/bulk-action', [SkillController::class, 'bulkAction'])->name('skills.bulk-action');
        
        Route::resource('designations', DesignationController::class)->except(['create', 'show', 'edit']);
        Route::post('designations/bulk-action', [DesignationController::class, 'bulkAction'])->name('designations.bulk-action');

        // Human Resource (Users)
        Route::get('/', [HRManagementController::class, 'index'])->name('index');
        Route::get('/create', [HRManagementController::class, 'create'])->name('create');
        Route::post('/store', [HRManagementController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [HRManagementController::class, 'edit'])->name('edit');
        Route::put('/{id}/update', [HRManagementController::class, 'update'])->name('update');
        Route::get('/{id}/show', [HRManagementController::class, 'show'])->name('show');
        Route::delete('/{id}/destroy', [HRManagementController::class, 'destroy'])->name('destroy');
    });
});