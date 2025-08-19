<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Configurations\CurrencyController;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

// Currency Management Routes are now defined in web.php
// Commented out to avoid route conflicts
/*
Route::prefix('admin')->middleware(['auth', 'verified'])->group(function () {
    Route::prefix('configurations')->group(function () {
        Route::resource('currency', CurrencyController::class);
        Route::post('currency/{currency}/set-default', [CurrencyController::class, 'setDefault'])
            ->name('admin.configurations.currency.set-default');
        Route::post('currency/update-rates', [CurrencyController::class, 'updateRates'])
            ->name('admin.configurations.currency.update-rates');
        Route::get('currency/settings/edit', [CurrencyController::class, 'settings'])
            ->name('admin.configurations.currency.settings');
        Route::post('currency/settings', [CurrencyController::class, 'updateSettings'])
            ->name('admin.configurations.currency.settings.update');
    });
});
*/
