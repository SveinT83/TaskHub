<?php

use Modules\Clients\default\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::get('/clients', [ClientController::class, 'index'])->name('clients.index');

