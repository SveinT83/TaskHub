<?php

use Illuminate\Support\Facades\Route;
use Modules\Core\Http\Controllers\CoreController;

Route::middleware(['auth'])->group(function () {
    Route::get('/', [CoreController::class, 'index'])->name('core.dashboard');
});