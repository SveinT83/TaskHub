<?php

use Illuminate\Support\Facades\Route;
use Modules\CredentialsBank\Http\Controllers\CredentialsBankController;

Route::middleware(['web', 'auth'])->prefix('credentials-bank')->group(function () {

    // 📊 View Credentials (Main Index)
    Route::get('/credentials', [CredentialsBankController::class, 'index'])->name('credentials-bank.index');

    // ➕ Store New Credential
    Route::post('/credentials/store', [CredentialsBankController::class, 'store'])->name('credentials-bank.store');

    // ✏️ Edit Credential (Shows Edit Form)
    Route::get('/credentials/{id}/edit', [CredentialsBankController::class, 'edit'])->name('credentials-bank.edit');

    // 💾 Update Credential
    Route::put('/credentials/{id}', [CredentialsBankController::class, 'update'])->name('credentials-bank.update');

    // 🗑️ Delete Credential
    Route::delete('/credentials/{id}', [CredentialsBankController::class, 'destroy'])->name('credentials-bank.destroy');

    // 🗝️ Download Public Key
    Route::get('/public-key', [CredentialsBankController::class, 'publicKey'])->name('credentials-bank.public-key');

    // 🔄 Rotate Encryption Keys
    Route::post('/rotate-keys', [CredentialsBankController::class, 'rotateKeys'])->name('credentials-bank.rotate-keys');
});