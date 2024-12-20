<?php
use Modules\FacebookPostingModule\src\Http\Controllers\FacebookController;

Route::group(['middleware' => 'web', 'prefix' => 'facebook-poster'], function () {
    Route::get('/groups', [FacebookController::class, 'listGroups'])->name('facebook.groups');
    Route::post('/post', [FacebookController::class, 'postToGroup'])->name('facebook.post');
});
