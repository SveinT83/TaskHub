<?php

use Modules\Inventory\src\Http\Controllers\InventoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('inventory')->group(function () {
    Route::get('/', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/store', [InventoryController::class, 'store'])->name('inventory.store');
    Route::post('/use/{id}', [InventoryController::class, 'usePart'])->name('inventory.use');
});