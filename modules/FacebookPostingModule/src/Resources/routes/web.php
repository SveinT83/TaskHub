<?php
use modules\FacebookPostingModule\src\Http\Controllers\FacebookController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'web', 'prefix' => 'facebook-poster'], function () {
    Route::get('/groups', [FacebookController::class, 'listGroups'])->name('facebook.groups');
    Route::post('/post', [FacebookController::class, 'postToGroup'])->name('facebook.post');
});
