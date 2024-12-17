<?php

use Illuminate\Support\Facades\Route;
use Tronderdata\Skeleton\Http\Controllers\SkeletonController;

Route::middleware('web', 'auth')->group(function () {
    Route::get('skeleton', [SkeletonController::class, 'index'])->name('skeleton.index');
});
