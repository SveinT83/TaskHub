<?php

namespace Modules\CredentialsBank\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CredentialsBankController extends Controller
{
    public function index()
    {
        $credentials = Auth::user()->credentialsBank()->get(['id', 'encrypted_username', 'encrypted_password']);
        return response()->json($credentials);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'encrypted_username' => 'required|string',
            'encrypted_password' => 'required|string',
        ]);

        Auth::user()->credentialsBank()->create($validated);

        return response()->json(['message' => 'Credentials stored securely.']);
    }
}
