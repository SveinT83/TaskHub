<?php
namespace Taskhub\TdClients\Http\Controllers;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role; // Import for roller
use Illuminate\Support\Facades\Auth; // Import for autentisering (valgfritt)

use Taskhub\TdClients\Models\Client;
use Taskhub\TdClients\Models\ClientSite;
use Taskhub\TdClients\Models\ClientUser;
use Illuminate\Support\Facades\Gate;

class ClientConfigController extends Controller
{
    public function index()
    {
        // Henter den innloggede brukeren
        $user = auth()->user();

        // Sjekker om brukeren har tillatelsen 'manageclients'
        if (!$user->can('clients.admin')) {
            // Hvis brukeren ikke har tillatelsen, returnerer vi en 403-feil (forbudt tilgang)
            abort(403, 'You do not have permission to manage clients.');
        }

        $clientCount = Client::count();
        $siteCount = ClientSite::count();
        $userCount = ClientUser::count();

        return view('tdclients::admin.config', compact('clientCount', 'siteCount', 'userCount'));
    }
}
