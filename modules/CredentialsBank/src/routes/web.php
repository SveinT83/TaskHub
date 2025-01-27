<?php

use Illuminate\Support\Facades\Route;
use Modules\CredentialsBank\Http\Controllers\CredentialsBankController;

Route::middleware('auth')->prefix('credentials-bank')->group(function () {
    Route::get('/', [CredentialsBankController::class, 'index'])->name('credentials-bank.index');
    Route::post('/', [CredentialsBankController::class, 'store'])->name('credentials-bank.store');
});
