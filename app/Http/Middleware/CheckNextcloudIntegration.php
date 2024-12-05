<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Setting;

class CheckNextcloudIntegration
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Sjekk om Nextcloud-integrasjonen er aktivert
        $isNextcloudActive = Setting::where('name', 'nextcloud_integration')->value('value') == 1;

        if (!$isNextcloudActive) {
            return redirect('/login')->with('error', 'Nextcloud integrasjon er ikke aktivert.');
        }

        return $next($request);
    }
}
