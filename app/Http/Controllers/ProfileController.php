<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER FOR PROFILE
//
// This controller is responsible for handling profile related actions such as updating the user's profile information and deleting the user's account.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    // --------------------------------------------------------------------------------------------------
    // FUNCTION - EDIT
    // This function returns the view with the user's information.
    // --------------------------------------------------------------------------------------------------
    public function edit(Request $request): View
    {
        // -------------------------------------------------
        // Return the view with the user's information.
        // -------------------------------------------------
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - UPDATE
    // Updates the user's profile information.
    // --------------------------------------------------------------------------------------------------
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // -------------------------------------------------
        // Retrieve the form data and validate it.
        // -------------------------------------------------
        $request->user()->fill($request->validated());

        // -------------------------------------------------
        // If the user's email has been changed, set the email_verified_at field to null.
        // -------------------------------------------------
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }


        // -------------------------------------------------
        // Update the user's information.
        // -------------------------------------------------
        $request->user()->save();

        // -------------------------------------------------
        // Redirect the user back to the profile edit page with a success message.
        // -------------------------------------------------
        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - DELETE
    // Deletes the user's account.
    // --------------------------------------------------------------------------------------------------
    public function destroy(Request $request): RedirectResponse
    {
        // -------------------------------------------------
        // Validate the request.
        // -------------------------------------------------
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        // -------------------------------------------------
        // Retrieve the user.
        // -------------------------------------------------
        $user = $request->user();

        // -------------------------------------------------
        // Log the user out.
        // -------------------------------------------------
        Auth::logout();

        // -------------------------------------------------
        // Delete the user.
        // -------------------------------------------------
        $user->delete();

        // -------------------------------------------------
        // Invalidate the session and regenerate the token.
        // -------------------------------------------------
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // -------------------------------------------------
        // Redirect the user to the home page.
        // -------------------------------------------------
        return Redirect::to('/');
    }
}
