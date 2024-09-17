<?php
namespace tronderdata\TdClients\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use tronderdata\TdClients\Models\Client;
use tronderdata\TdClients\Models\ClientSite;
use tronderdata\TdClients\Models\ClientUser;

class ClientSiteController extends Controller
{
    public function index()
    {
        // Hent alle sites fra ClientSite-modellen
        $sites = ClientSite::all();
        return view('tdClients::sites.index', compact('sites'));
    }

    public function create(Request $request)
    {
        $client_id = $request->input('client_id'); // Hent client_id fra forespørselen, hvis tilstede
        $clients = Client::all(); // Hent alle klienter for dropdown-feltet

        return view('tdClients::sites.create', compact('client_id', 'clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'client_id' => 'required|exists:clients,id',
            // Valider eventuelt flere felter
        ]);

        ClientSite::create($validated);

        return redirect()->route('client.sites.index')->with('success', 'Site created successfully.');
    }

    public function edit(ClientSite $site)
    {
        return view('tdClients::sites.edit', compact('site'));
    }

    public function update(Request $request, ClientSite $site)
    {
        // Oppdater site ved hjelp av ClientSite-modellen
        $site->update($request->all());
        return redirect()->route('client.sites.index');
    }

    public function destroy(ClientSite $site)
    {
        // Slett site ved hjelp av ClientSite-modellen
        $site->delete();
        return redirect()->route('client.sites.index');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Profile
    // Shows a profile of a site and all information about it
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function profile(ClientSite $site)
    {
        // -------------------------------------------------
        // Hent alle brukerne som er tilknyttet siten
        // -------------------------------------------------
        $users = $site->users;

        return view('tdClients::sites.profile', compact('site', 'users'));
    }
}
