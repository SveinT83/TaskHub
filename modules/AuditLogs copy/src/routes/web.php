<?php

use Illuminate\Support\Facades\Route;

Route::get('/forbidden', function () {
    return view('core::errors.403');
})->name('error.403');