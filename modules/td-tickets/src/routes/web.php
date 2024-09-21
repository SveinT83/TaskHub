<?php

use Illuminate\Support\Facades\Route;
use tronderdata\TdTickets\Http\Controllers\TicketController;

Route::middleware(['web', 'auth'])->group(function () {
    Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tickets/{id}', [TicketController::class, 'show'])->name('tickets.show');
    Route::post('/tickets/{id}/reply', [TicketController::class, 'reply'])->name('tickets.reply');
    Route::post('/tickets/{id}/time-spend', [TicketController::class, 'addTimeSpend'])->name('tickets.time_spend');
    Route::get('/my-tickets', [TicketController::class, 'myTickets'])->name('tickets.my_tickets');
});