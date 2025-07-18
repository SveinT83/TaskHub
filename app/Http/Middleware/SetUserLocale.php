<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetUserLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = null;

        // Priority 1: User preference (if logged in)
        if ($user = Auth::user()) {
            $locale = $user->locale;
        }

        // Priority 2: Session locale (for guest users)
        if (!$locale) {
            $locale = $request->session()->get('locale');
        }

        // Priority 3: Default locale
        if (!$locale) {
            $locale = config('app.locale');
        }

        // Validate that the locale is supported
        if (array_key_exists($locale, config('app.supported_locales', []))) {
            App::setLocale($locale);
        }

        return $next($request);
    }
}
