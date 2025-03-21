<?php

use Illuminate\Support\Facades\Route;
use Taskhub\Equipment\Http\Controllers\EquipmentController;
use Taskhub\Equipment\Http\Controllers\ServiceController;
use Taskhub\Equipment\Http\Controllers\WidgetController;

Route::middleware(['web', 'auth'])->group(function () {

    // --------------------------------------------------------------------------------------------------
    // Equipment routes
    // --------------------------------------------------------------------------------------------------

    Route::get('equipment/', [EquipmentController::class, 'index'])
        ->name('equipment.index')
        ->middleware('can:equipment.view');

    Route::get('equipment/create', [EquipmentController::class, 'create'])
        ->name('equipment.create')
        ->middleware('can:equipment.create');

    Route::get('equipment/{equipment}', [EquipmentController::class, 'show'])
        ->name('equipment.show')
        ->middleware('can:equipment.view');

    Route::post('equipment/store', [EquipmentController::class, 'store'])
        ->name('equipment.store')
        ->middleware('can:equipment.create');

    Route::get('equipment/{equipment}/edit', [EquipmentController::class, 'edit'])
        ->name('equipment.edit')
        ->middleware('can:equipment.edit');

    Route::put('equipment/{equipment}/update', [EquipmentController::class, 'update'])
        ->name('equipment.update')
        ->middleware('can:equipment.edit');

    Route::delete('equipment/{equipment}/delete', [EquipmentController::class, 'destroy'])
        ->name('equipment.destroy')
        ->middleware('can:equipment.delete');

    // --------------------------------------------------------------------------------------------------
    // Services routes
    // --------------------------------------------------------------------------------------------------

    Route::get('equipment/{equipment}/service/create', [ServiceController::class, 'create'])
        ->name('equipment.service.create')
        ->middleware('can:equipment.create');

    Route::post('equipment/{equipment}/service/store', [ServiceController::class, 'store'])
        ->name('equipment.service.store')
        ->middleware('can:equipment.create');

    Route::delete('equipment/service/{serviceHistory}/delete', [ServiceController::class, 'destroy'])
        ->name('equipment.service.destroy')
        ->middleware('can:equipment.edit');


    // --------------------------------------------------------------------------------------------------
    // Widgets routes
    // --------------------------------------------------------------------------------------------------
    Route::get('/widgets/equipments-list', [WidgetController::class, 'equipmentsListWidget'])->name('widgets.equipmentsListWidget');
});