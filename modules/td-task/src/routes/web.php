<?php

use Illuminate\Support\Facades\Route;
use tronderdata\TdTask\Http\Controllers\TaskController;
use tronderdata\TdTask\Http\Controllers\TaskWallController;

// Middleware for autentiserte brukere
Route::middleware('auth')->group(function () {
    
    // Tasks routes (krever innlogging)
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/store', [TaskController::class, 'store'])->name('tasks.store');
    });

    // Walls routes (krever innlogging)
    Route::prefix('walls')->group(function () {
        Route::get('/', [TaskWallController::class, 'index'])->name('walls.index');
        Route::get('/{id}', [TaskWallController::class, 'show'])->name('walls.show');
    });
});