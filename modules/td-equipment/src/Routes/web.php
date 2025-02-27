<?php

use Illuminate\Support\Facades\Route;
use TronderData\Equipment\Http\Controllers\EquipmentController;

Route::middleware(['web', 'auth'])->prefix('admin/equipment')->group(function () {
    
    Route::get('/', [EquipmentController::class, 'index'])
        ->name('equipment.index')
        ->middleware('can:equipment.view');

    Route::get('/{equipment}', [EquipmentController::class, 'show'])
        ->name('equipment.show')
        ->middleware('can:equipment.view');

    Route::get('/create', [EquipmentController::class, 'create'])
        ->name('equipment.create')
        ->middleware('can:equipment.create');

    Route::post('/store', [EquipmentController::class, 'store'])
        ->name('equipment.store')
        ->middleware('can:equipment.create');

    Route::get('/{equipment}/edit', [EquipmentController::class, 'edit'])
        ->name('equipment.edit')
        ->middleware('can:equipment.edit');

    Route::put('/{equipment}/update', [EquipmentController::class, 'update'])
        ->name('equipment.update')
        ->middleware('can:equipment.edit');

    Route::delete('/{equipment}/delete', [EquipmentController::class, 'destroy'])
        ->name('equipment.destroy')
        ->middleware('can:equipment.delete');
});

// 📌 Custom 403-melding hvis bruker ikke har tilgang
Route::fallback(function () {
    return response()->view('errors.403', [], 403);
});
