<?php

use Illuminate\Support\Facades\Route;
use tronderdata\TdTickets\Http\Controllers\TicketController;
use tronderdata\TdTickets\Http\Controllers\TicketConfigController;

Route::middleware(['web', 'auth'])->group(function () {
    // Definer mer spesifikke ruter først, ellers vil ikke de generelle rutene fungere
    Route::get('/tickets/new', [TicketController::class, 'createNewTicket'])->name('tickets.new');
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');

    //Generelle ruter
    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{id}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::post('/tickets/{id}/time-spend', [TicketController::class, 'addTimeSpend'])->name('tickets.time_spend');

    // Admin-ruter for å konfigurere klientmodulen
    Route::get('/admin/tickets/config', [TicketConfigController::class, 'index'])->name('admin.tickets.config');
});