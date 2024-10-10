<?php

use Illuminate\Support\Facades\Route;
use tronderdata\TdTask\Http\Controllers\TaskController;
use tronderdata\TdTask\Http\Controllers\TaskWallController;
use tronderdata\TdTask\Http\Controllers\TaskAdminController;
use tronderdata\TdTask\Http\Controllers\Api\TaskApiController;
use tronderdata\TdTask\Http\Controllers\Api\TaskWallApiController;

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
        Route::put('/{id}/status', [TaskController::class, 'updateStatus'])->name('tasks.updateStatus');
        Route::put('/{task}/assignee', [TaskController::class, 'updateAssignee'])->name('tasks.updateAssignee');
        Route::put('/{task}/update-wall', [TaskController::class, 'updateWall'])->name('tasks.updateWall');
        Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
        Route::post('{task}/comment', [TaskController::class, 'storeComment'])->name('tasks.comment');
        Route::delete('{task}/comment/{comment}', [TaskController::class, 'deleteComment'])->name('tasks.comment.delete');
        Route::put('/{id}/update-actual-time', [TaskController::class, 'updateActualTime'])->name('tasks.updateActualTime'); // Oppdater faktisk tid brukt
    });

    // Walls routes (krever innlogging)
    Route::prefix('walls')->group(function () {
        Route::get('/', [TaskWallController::class, 'index'])->name('walls.index');
        Route::get('/create', [TaskWallController::class, 'create'])->name('walls.create');
        Route::post('/store', [TaskWallController::class, 'store'])->name('walls.store');
        Route::get('/{id}', [TaskWallController::class, 'show'])->name('walls.show');
        Route::delete('/{id}', [TaskWallController::class, 'destroy'])->name('walls.destroy');
    });

    // Ruter for Task admin-siden, krever autentisering og passende tillatelser
    Route::middleware(['auth'])->prefix('admin/task')->group(function () {
        Route::get('index', [TaskAdminController::class, 'index'])->name('admin.task.index');
    });

    Route::get('/api/templates/{id}', [TaskWallController::class, 'getTemplate']);

    // --------------------------------------------------------------------------------------------------
    // API routes
    // --------------------------------------------------------------------------------------------------
    Route::middleware('auth:api')->prefix('api.tasks')->group(function () {
        
        // -------------------------------------------------
        // Task API routes
        // -------------------------------------------------
        Route::get('/', [TaskApiController::class, 'index'])->name('api.tasks.index'); // Hent alle oppgaver
        Route::get('/{id}', [TaskApiController::class, 'show'])->name('api.tasks.show'); // Hent en spesifikk oppgave
        Route::post('/', [TaskApiController::class, 'store'])->name('api.tasks.store'); // Opprett ny oppgave
        Route::put('/{id}', [TaskApiController::class, 'update'])->name('api.tasks.update'); // Oppdater en oppgave
        Route::delete('/{id}', [TaskApiController::class, 'destroy'])->name('api.tasks.destroy'); // Slett en oppgave
        
        // -------------------------------------------------
        // TaskWall API routes
        // -------------------------------------------------
        Route::get('/walls', [TaskWallApiController::class, 'index'])->name('api.walls.index'); // Hent alle vegger
        Route::get('/walls/{id}', [TaskWallApiController::class, 'show'])->name('api.walls.show'); // Hent en spesifikk vegg
        Route::post('/walls', [TaskWallApiController::class, 'store'])->name('api.walls.store'); // Opprett ny vegg
        Route::put('/walls/{id}', [TaskWallApiController::class, 'update'])->name('api.walls.update'); // Oppdater en vegg
        Route::delete('/walls/{id}', [TaskWallApiController::class, 'destroy'])->name('api.walls.destroy'); // Slett en vegg
    });
});
