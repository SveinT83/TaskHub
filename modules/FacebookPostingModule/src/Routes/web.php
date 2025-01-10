<?php

use Illuminate\Support\Facades\Route;
use Modules\FacebookPostingModule\Http\Controllers\FacebookController;

// Define routes for the Facebook Posting Module
Route::group(['middleware' => 'web', 'prefix' => 'facebook-poster'], function () {
    // Route to list user groups
    Route::get('/groups', [FacebookController::class, 'listGroups'])->name('facebook.groups');

    // Route to post a message to a Facebook group
    Route::post('/post-group', [FacebookController::class, 'postToGroup'])->name('facebook.post-group');

    // Route to post a message to a Facebook page
    Route::post('/post-page', [FacebookController::class, 'postToPage'])->name('facebook.post-page');
});
