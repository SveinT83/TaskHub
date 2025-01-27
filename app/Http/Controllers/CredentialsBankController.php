<?php

namespace Modules\CredentialsBank\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CredentialsBankController extends Controller
{
    /**
     * Fetch encrypted credentials for the authenticated user.
     */
    public function index()
    {
        // Fetch the user's encrypted credentials.
        $credentials = Auth::user()->credentialsBank()->get(['id', 'encrypted_username', 'encrypted_password']);

        // Return the credentials as a JSON response.
        return response()->json($credentials);
    }

    /**
     * Store encrypted credentials for the authenticated user.
     */
    public function store(Request $request)
    {
        // Validate incoming request.
        $validated = $request->validate([
            'encrypted_username' => 'required|string',
            'encrypted_password' => 'required|string',
        ]);

        // Store the encrypted credentials under the authenticated user.
        Auth::user()->credentialsBank()->create($validated);

        // Return a success message.
        return response()->json(['message' => 'Credentials stored securely.']);
    }
}
