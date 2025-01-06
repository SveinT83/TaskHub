<?php

use Illuminate\Support\Facades\Route;
use Modules\FacebookPostingModule\Http\Controllers\FacebookController;

// Define the routes for the FacebookPostingModule
Route::group(['middleware' => 'web', 'prefix' => 'facebook-poster'], function () {
    // Route to list groups
    Route::get('/groups', [FacebookController::class, 'listGroups'])->name('facebook.groups');

    // Route to post a message to a group
    Route::post('/post', [FacebookController::class, 'postToGroup'])->name('facebook.post');
});
