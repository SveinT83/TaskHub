<?php

use Illuminate\Support\Facades\Route;
use tronderdata\Skeleton\Http\Controllers\SkeletonController;

Route::get('skeleton', [SkeletonController::class, 'index'])->name('skeleton.index');
