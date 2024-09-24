<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\Setting;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        // Hent innstilling for Nextcloud-integrasjon
        $setting = Setting::where('name', 'nextcloud_integration')->first();
        $isNextcloudActive = $setting ? $setting->value == '1' : false;

        // Hvis Nextcloud er aktivert, bruk Nextcloud-påloggingsvisning
        if ($isNextcloudActive) {
            return view('auth.loginNextcloud', compact('isNextcloudActive'));
        }

        // Bruk standard påloggingsvisning
        return view('auth.login', compact('isNextcloudActive'));
    }

    public function store(Request $request)
    {
        // Standard påloggingslogikk for brukere uten Nextcloud-integrasjon
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
