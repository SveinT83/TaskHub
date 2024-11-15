<?php

use Illuminate\Support\Facades\Route;
use tronderdata\TdSalgsSkjema\Http\Controllers\TdsalgsSkjemaController;

//Route::get('/tdsalgsskjema', [TdsalgsskjemaController::class, 'index'])->name('tdsalgsskjema.index');
//Route::get('/tdsalgsskjema/create', [TdsalgsskjemaController::class, 'create'])->name('tdsalgsskjema.create');

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// AUTHENTICATED ROUTES
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// Routes for all authenticated users
// ----------------------------------------------------------------------------------------------------------------------------------------------------
Route::middleware('web', 'auth')->group(function () {


    // -------------------------------------------------
    // Module Routes
    // -------------------------------------------------
    Route::prefix('tdsalgsskjema')->group(function () {

        Route::get('/', [TdsalgsskjemaController::class, 'index'])->name('tdsalgsskjema.index');
        Route::get('/create', [TdsalgsskjemaController::class, 'create'])->name('tdsalgsskjema.create');
        Route::get('/businessOrPrivate', [TdsalgsskjemaController::class, 'businessOrPrivate'])->name('tdsalgsskjema.businessOrPrivate');
        Route::get('/aLaCarte', [TdsalgsskjemaController::class, 'aLaCarte'])->name('tdsalgsskjema.aLaCarte');
        Route::get('/FindCustomerForm', [TdsalgsskjemaController::class, 'FindCustomerForm'])->name('tdsalgsskjema.FindCustomerForm');
    });


    // -------------------------------------------------
    // Admin Routes
    // -------------------------------------------------
    Route::prefix('admin/tdsalgsskjema')->middleware('verified')->group(function () {

    });

});
