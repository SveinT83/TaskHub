<?php
namespace tronderdata\TdClients\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use tronderdata\TdClients\Models\Client;
use tronderdata\TdClients\Models\ClientSite;
use tronderdata\TdClients\Models\ClientUser;

class ClientSiteController extends Controller
{

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Index
    // Shows a list of all sites
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function index()
    {

        // -------------------------------------------------
        // Sjekker om brukeren har tillatelsen
        // -------------------------------------------------
        if (!Gate::allows('site.view')) {
            // Hvis brukeren ikke har tillatelsen, returner en 403-feil
            abort(403, 'You do not have permission to create users.');
        }

        // -------------------------------------------------
        // Hent alle sites fra ClientSite-modellen
        // -------------------------------------------------
        $sites = ClientSite::all();
        return view('tdClients::sites.index', compact('sites'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Create
    // Shows a form for creating a new site
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function create(Request $request)
    {

        // -------------------------------------------------
        // Sjekker om brukeren har tillatelsen
        // -------------------------------------------------
        if (!Gate::allows('site.create')) {
            // Hvis brukeren ikke har tillatelsen, returner en 403-feil
            abort(403, 'You do not have permission to create users.');
        }

        // -------------------------------------------------
        // Get client_id from the request, if present
        // -------------------------------------------------
        $client_id = $request->input('client_id'); // Hent client_id fra forespørselen, hvis tilstede
        $clients = Client::all(); // Hent alle klienter for dropdown-feltet

        // -------------------------------------------------
        // Return the view with the client_id and clients1
        // -------------------------------------------------
        return view('tdClients::sites.create', compact('client_id', 'clients'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Store
    // Stores a new site in the database
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
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



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Edit
    // Shows a form for editing a site
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function edit(ClientSite $site)
    {

        // -------------------------------------------------
        // Sjekker om brukeren har tillatelsen
        // -------------------------------------------------
        if (!Gate::allows('site.edit')) {
            // Hvis brukeren ikke har tillatelsen, returner en 403-feil
            abort(403, 'You do not have permission to create users.');
        }

        // -------------------------------------------------
        // Return the view with the site
        // -------------------------------------------------
        return view('tdClients::sites.edit', compact('site'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Update
    // Updates a site in the database
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function update(Request $request, ClientSite $site)
    {
        // -------------------------------------------------
        // Update the site with the request data
        // -------------------------------------------------
        $site->update($request->all());

        // -------------------------------------------------
        // Return the view with the site
        // -------------------------------------------------
        return redirect()->route('client.sites.index');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Destroy
    // Deletes a site from the database
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function destroy(ClientSite $site)
    {
        // -------------------------------------------------
        // Slett site ved hjelp av ClientSite-modellen
        // -------------------------------------------------
        $site->delete();

        // -------------------------------------------------
        // Return the view with the site
        // -------------------------------------------------
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
        // Sjekker om brukeren har tillatelsen
        // -------------------------------------------------
        if (!Gate::allows('site.view')) {
            // Hvis brukeren ikke har tillatelsen, returner en 403-feil
            abort(403, 'You do not have permission to create users.');
        }

        // -------------------------------------------------
        // Hent alle brukerne som er tilknyttet siten
        // -------------------------------------------------
        $users = $site->users;

        // -------------------------------------------------
        // Return the view with the site and users
        // -------------------------------------------------
        return view('tdClients::sites.profile', compact('site', 'users'));
    }
}
