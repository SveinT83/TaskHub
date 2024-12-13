<?php

use App\Http\Controllers\FacebookController;
use App\Services\FacebookService;
use Illuminate\Support\Facades\Route;

// Route for Facebook login redirect
Route::get('login/facebook', [FacebookController::class, 'redirectToFacebook'])->name('facebook.login');

// Route to handle the callback after Facebook authentication
Route::get('facebook/callback', [FacebookController::class, 'handleFacebookCallback'])->name('facebook.callback');

// Route for posting to Facebook Wall (user feed)
Route::post('facebook/post', [FacebookController::class, 'postToFacebook'])->name('facebook.post');

// Route for posting to a Facebook Group (user must provide group ID)
Route::post('facebook/post-group/{groupId}', [FacebookController::class, 'postToFacebookGroup'])->name('facebook.post.group');

// Example dashboard route after user logs in
Route::get('dashboard', function () {
    return view('dashboard');
})->name('dashboard');
