<?php

use Illuminate\Support\Facades\Route;
use tronderdata\categories\Http\Controllers\CategoriesController;

// Middleware for autentiserte brukere
Route::middleware(['web', 'auth'])->group(function () {

    // KbArtickles routes
    Route::prefix('admin/cat')->group(function () {

        // All categories
        Route::get('/', [CategoriesController::class, 'index'])->name('cat.index');

        // New Categorie
        Route::get('/new/categorie', [CategoriesController::class, 'create'])->name('cat.categorie.create');

        // Show Categorie
        Route::get('/categorie/{id}', [CategoriesController::class, 'show'])->name('cat.categorie.show');
    });
});
