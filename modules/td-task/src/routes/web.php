<?php

use Illuminate\Support\Facades\Route;
use tronderdata\TdTask\Http\Controllers\TaskController;
use tronderdata\TdTask\Http\Controllers\TaskWallController;

// Middleware for autentiserte brukere
Route::middleware(['web', 'auth'])->group(function () {

    // Tasks routes (krever innlogging)
    Route::prefix('tasks')->group(function () {
        Route::get('/', [TaskController::class, 'index'])->name('tasks.index');
        Route::get('/create', [TaskController::class, 'create'])->name('tasks.create');
        Route::post('/store', [TaskController::class, 'store'])->name('tasks.store');
        Route::get('/{id}', [TaskController::class, 'show'])->name('tasks.show');

        // Redigeringsruter
        Route::get('/{task}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
        Route::put('/{task}', [TaskController::class, 'update'])->name('tasks.update');

        //Dyn edit Routes
        Route::post('/{task}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
        Route::put('/{task}/assignee', [TaskController::class, 'updateAssignee'])->name('tasks.updateAssignee');
        Route::put('/{task}/update-wall', [TaskController::class, 'updateWall'])->name('tasks.updateWall');
        Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        Route::post('{task}/comment', [TaskController::class, 'storeComment'])->name('tasks.comment');
        Route::delete('{task}/comment/{comment}', [TaskController::class, 'deleteComment'])->name('tasks.comment.delete');
    });

    // Walls routes (krever innlogging)
    Route::prefix('walls')->group(function () {
        Route::get('/', [TaskWallController::class, 'index'])->name('walls.index');
        Route::get('/create', [TaskWallController::class, 'create'])->name('walls.create');
        Route::post('/store', [TaskWallController::class, 'store'])->name('walls.store');
        Route::get('/{id}', [TaskWallController::class, 'show'])->name('walls.show');
    });
});
