<?php
use Illuminate\Support\Facades\Route;
use tronderdata\TdClients\Http\Controllers\ClientController;
use tronderdata\TdClients\Http\Controllers\ClientSiteController;
use tronderdata\TdClients\Http\Controllers\ClientUserController;
use tronderdata\TdClients\Http\Controllers\ClientConfigController;

// Ruter for klientmodulen
Route::middleware(['web', 'auth'])->group(function () {

    // Hovedruter for klienter
    Route::resource('clients', ClientController::class);
    Route::get('clients/{client}/profile', [ClientController::class, 'profile'])->name('clients.profile');

    // SITES
    Route::get('client/sites', [ClientSiteController::class, 'index'])->name('client.sites.index');
    Route::get('client/sites/create', [ClientSiteController::class, 'create'])->name('client.sites.create');
    Route::post('client/sites', [ClientSiteController::class, 'store'])->name('client.sites.store');
    Route::get('client/sites/{site}/edit', [ClientSiteController::class, 'edit'])->name('client.sites.edit');
    Route::put('client/sites/{site}', [ClientSiteController::class, 'update'])->name('client.sites.update');
    Route::delete('client/sites/{site}', [ClientSiteController::class, 'destroy'])->name('client.sites.destroy');
    Route::get('client/sites/{site}/profile', [ClientSiteController::class, 'profile'])->name('client.sites.profile');
    Route::get('/api/clients/{client}/sites', [ClientController::class, 'getSites']);


    // USERS
    Route::get('client/users', [ClientUserController::class, 'index'])->name('client.users.index');
    Route::get('client/users/create', [ClientUserController::class, 'create'])->name('client.users.create');
    Route::post('client/users', [ClientUserController::class, 'store'])->name('client.users.store');
    Route::get('client/users/{user}/edit', [ClientUserController::class, 'edit'])->name('client.users.edit');
    Route::put('client/users/{user}', [ClientUserController::class, 'update'])->name('client.users.update');
    Route::delete('client/users/{user}', [ClientUserController::class, 'destroy'])->name('client.users.destroy');
    Route::get('client/users/{user}/profile', [ClientUserController::class, 'profile'])->name('client.users.profile');

    // Admin-ruter for å konfigurere klientmodulen
    Route::prefix('admin')->middleware('can:admin')->group(function () {
        Route::get('clients/config', [ClientConfigController::class, 'index'])->name('admin.clients.config');
    });
});