<?php

use Illuminate\Support\Facades\Route;
use Modules\FacebookPostingModule\Http\Controllers\FacebookController;

Route::group(['middleware' => 'web', 'prefix' => 'facebook-poster'], function () {
    Route::get('/post-form', [FacebookController::class, 'showPostForm'])->name('facebook.post-form');
    Route::get('/groups', [FacebookController::class, 'listGroups'])->name('facebook.groups');
    Route::post('/post-group', [FacebookController::class, 'postToGroup'])->name('facebook.post-group');
    Route::post('/post-page', [FacebookController::class, 'postToPage'])->name('facebook.post');
    Route::post('/facebook/post-to-wall', [FacebookController::class, 'postToWall']);
});
