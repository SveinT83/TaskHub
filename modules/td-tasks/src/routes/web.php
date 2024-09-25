<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// 
// DEPENDENCIES
//
// ---------------------------------------------------------------------------------------------------------------------------------------------------
use Modules\TdTasks\Http\Controllers\TaskController;
use Modules\TdTasks\Http\Controllers\TaskWallController;
use Modules\TdTasks\Http\Controllers\TaskConfigController;

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// 
// ROUTES
//
// ---------------------------------------------------------------------------------------------------------------------------------------------------

// -------------------------------------------------
// Tasks routes
// -------------------------------------------------
Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');

// -------------------------------------------------
// Walls routes
// -------------------------------------------------
Route::get('/walls', [TaskWallController::class, 'index'])->name('walls.index');
Route::get('/walls/create', [TaskWallController::class, 'create'])->name('walls.create');
Route::post('/walls', [TaskWallController::class, 'store'])->name('walls.store');

// -------------------------------------------------
// Config routes
// -------------------------------------------------
Route::get('/admin/tasks/config', [TaskConfigController::class, 'index'])->name('tasks.config');