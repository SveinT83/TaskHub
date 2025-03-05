<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Widgets\WidgetController;

Route::resource('widgets', WidgetController::class);
