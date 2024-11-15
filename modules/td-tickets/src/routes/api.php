<?php

use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Modules\Tickets\Http\Controllers', 'middleware' => ['api'], 'prefix' => 'api'], function () {
    // Definer API-ruter her
    Route::get('tickets', 'TicketController@apiIndex')->name('tickets.api.index');
});
