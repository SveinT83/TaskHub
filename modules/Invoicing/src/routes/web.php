<?php

use Modules\Invoicing\src\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('invoices')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');
    Route::post('/send/{id}', [InvoiceController::class, 'sendToTripletex'])->name('invoices.send');
});