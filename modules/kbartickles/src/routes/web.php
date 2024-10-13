<?php

use Illuminate\Support\Facades\Route;
use tronderdata\kbartickles\Http\Controllers\KbarticklesController;
use tronderdata\kbartickles\Http\Controllers\CategoryController;

// Middleware for autentiserte brukere
Route::middleware(['web', 'auth'])->group(function () {

    // KbArtickles routes
    Route::prefix('kb')->group(function () {

        // Liste over alle artikler og kategorier
        Route::get('/', [KbarticklesController::class, 'index'])->name('kb.index');

        // Ny artikkel
        Route::get('/new/artickle', [KbArticklesController::class, 'create'])->name('kb.artickle.create');
        Route::post('/new/artickle', [KbArticklesController::class, 'store'])->name('kb.artickle.store');

        // Vis artikkel
        Route::get('/artickles/{id}', [KbArticklesController::class, 'show'])->name('kb.artickle.show');

        // Ny kategori
        Route::get('/new/category', [CategoryController::class, 'create'])->name('kb.category.create');
        Route::post('/new/category', [CategoryController::class, 'store'])->name('kb.category.store');

        // Vis kategori
        Route::get('/category/{id}', [CategoryController::class, 'show'])->name('kb.category.show');
    });
});