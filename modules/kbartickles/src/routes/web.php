<?php

use Illuminate\Support\Facades\Route;
use tronderdata\kbartickles\Http\Controllers\ArticleController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::prefix('kb')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('kb.index');
        Route::get('/article-form', [ArticleController::class, 'create'])->name('kb.article-form');
        Route::get('/{id}', [ArticleController::class, 'show'])->name('kb.show');
    });
});