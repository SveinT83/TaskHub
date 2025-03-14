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

    // 🔑 Download Individual Key (Ensures Plain Text Format)
    
    Route::get('/download-key/{file}', function ($file) {
        $filePath = storage_path("app/individual_keys/{$file}");
        Log::info("Attempting to download file from: " . $filePath);
    
        if (!file_exists($filePath)) {
            Log::error("File not found: " . $filePath);
            return response()->json(['error' => 'Key file not found.'], 404);
        }
    
        try {
            Log::info("Sending file for download: " . $filePath);
            return response()->download($filePath, $file, [
                'Content-Type' => 'text/plain',
                'Content-Disposition' => 'attachment; filename="' . $file . '"'
            ])->deleteFileAfterSend(true);
    
        } catch (\Exception $e) {
            Log::error("Error serving file for download: " . $e->getMessage());
            return response()->json(['error' => 'Internal Server Error.'], 500);
        }
    })->name('credentials-bank.download-key');

    // 🔄 Rotate Encryption Keys
    Route::post('/rotate-keys', [CredentialsBankController::class, 'rotateKeys'])->name('credentials-bank.rotate-keys');
});

Route::post('/credentials-bank/decrypt/{id}', [CredentialsBankController::class, 'decrypt'])->name('credentials-bank.decrypt');