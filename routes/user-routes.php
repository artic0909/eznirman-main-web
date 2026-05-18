<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('/user/dashboard', function () {
        return view('user.dashbaord.index');
    })->name('user.dashboard');
});