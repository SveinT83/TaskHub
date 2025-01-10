<?php

use Illuminate\Support\Facades\Route;
use tronderdata\TdTickets\Http\Controllers\TicketController;
use tronderdata\TdTickets\Http\Controllers\TicketConfigController;
use tronderdata\TdTickets\Http\Controllers\TicketTaskController;

Route::middleware(['web', 'auth'])->group(function () {

    // --------------------------------------------------------------------------------------------------
    // Definer mer spesifikke ruter først, ellers vil ikke de generelle rutene fungere
    // --------------------------------------------------------------------------------------------------
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/new', [TicketController::class, 'createNewTicket'])->name('tickets.new');
    Route::post('/tickets/store', [TicketController::class, 'store'])->name('tickets.store');

    // --------------------------------------------------------------------------------------------------
    // Generelle ruter
    // --------------------------------------------------------------------------------------------------

    // -------------------------------------------------
    // Get users by client - used in ticket form
    // -------------------------------------------------
    Route::get('/tickets/clients/{client}/users', [TicketController::class, 'getUsersByClient'])->name('tickets.clients.users');

    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{id}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::post('/tickets/{id}/time-spend', [TicketController::class, 'addTimeSpend'])->name('tickets.time_spend');

    // Admin-ruter for å konfigurere klientmodulen
    Route::get('/admin/tickets/config', [TicketConfigController::class, 'index'])->name('admin.tickets.config');


    // Ticket Task routes
    Route::prefix('tickets/{ticketId}/tasks')->group(function () {
        Route::post('/store', [TicketTaskController::class, 'storeTask'])->name('tickets.tasks.store');
        Route::delete('/{taskId}/delete', [TicketTaskController::class, 'deleteTask'])->name('tickets.tasks.delete');
        Route::put('/{taskId}/update', [TicketTaskController::class, 'updateTask'])->name('tickets.tasks.update');
        Route::get('/{taskId}/details', [TicketTaskController::class, 'getTaskDetails'])->name('tickets.tasks.details');
        Route::put('/{taskId}/complete', [TicketTaskController::class, 'completeTask'])->name('tickets.tasks.complete');
    });
});

