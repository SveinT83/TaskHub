<?php
use Illuminate\Support\Facades\Route;
use Modules\FacebookPostingModule\Http\Controllers\FacebookController;

Route::group(['middleware' => 'web', 'prefix' => 'facebook-poster'], function () {
    Route::get('/groups', [FacebookController::class, 'listGroups'])->name('facebook.groups');
    Route::post('/post', [FacebookController::class, 'postToGroup'])->name('facebook.post');
});
