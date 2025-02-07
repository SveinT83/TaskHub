<?php

use Illuminate\Support\Facades\Route;
use Modules\CredentialsBank\Http\Controllers\CredentialsBankController;

Route::middleware('auth')->prefix('credentials-bank')->group(function () {
    Route::get('/credentials', [CredentialsBankController::class, 'index'])->name('credentials-bank.index');
    Route::post('/credentials/store', [CredentialsBankController::class, 'store'])->name('credentials-bank.store');
    Route::get('/credentials/create', [CredentialsBankController::class, 'create'])->name('credentials-bank.create');
    Route::post('/rotate-keys', [CredentialsBankController::class, 'rotateKeys'])->name('credentials-bank.rotate-keys');
    Route::get('/public-key', [CredentialsBankController::class, 'publicKey'])->name('credentials-bank.public-key');
});