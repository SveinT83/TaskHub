<?php
namespace tronderdata\TdClients\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use tronderdata\TdClients\Models\Client;
use tronderdata\TdClients\Models\ClientSite;
use tronderdata\TdClients\Models\ClientUser;

class ClientController extends Controller
{
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION INDEX
    // Shows a list of all clients
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function index()
    {
        $clients = Client::all();
        return view('tdclients::clients.index', compact('clients'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION create
    // Shows a form to create a new client, whit the main site and user
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function create()
    {
        return view('tdclients::clients.create');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION STORE
    // Saves new client/Customers and it's main site and user
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function store(Request $request)
    {

        // -------------------------------------------------
        // Valider dataene
        // -------------------------------------------------
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'main_email' => 'required|email|max:255',
            'vat_number' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'site_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'zip' => 'nullable|integer',
            'city' => 'nullable|string|max:255',
            'county' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'state' => 'nullable|string|max:255',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'user_email' => 'required|email|max:255',
            'phone' => 'nullable|integer',
        ]);

        // -------------------------------------------------
        // Sjekk om kunden allerede eksisterer
        // -------------------------------------------------
        $existingClient = Client::where('name', $validated['name'])
            ->orWhere('main_email', $validated['main_email'])
            ->first();

        // -------------------------------------------------
        // Hvis kunden allerede finnes, returner en melding
        // -------------------------------------------------
        if ($existingClient) {
            // Customer exists, exit whit message
            return redirect()->back()->withErrors([
                'client_exists' => 'A client with this name or email already exists.',
            ])->withInput();
        }

        // -------------------------------------------------
        // Hvis kunden ikke finnes, lagre klienten
        // -------------------------------------------------
        $client = Client::create([
            'name' => $validated['name'],
            'main_email' => $validated['main_email'],
            'vat_number' => $validated['vat_number'],
            'account_number' => $validated['account_number'],
        ]);

        // -------------------------------------------------
        // Lagre site for klienten
        // -------------------------------------------------
        $site = ClientSite::create([
            'client_id' => $client->id,
            'name' => $validated['site_name'],
            'address' => $validated['address'],
            'zip' => $validated['zip'],
            'city' => $validated['city'],
            'county' => $validated['county'],
            'country' => $validated['country'],
            'state' => $validated['state'],
        ]);

        // -------------------------------------------------
        // Lagre bruker for klienten og site
        // -------------------------------------------------
        ClientUser::create([
            'client_id' => $client->id,
            'site_id' => $site->id,
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['user_email'],
            'phone' => $validated['phone'],
        ]);

        // -------------------------------------------------
        // Omdiriger til klientoversikten
        // -------------------------------------------------
        return redirect()->route('clients.index')->with('success', 'Client created successfully.');
    }


    
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION edit
    // Shows a form to edit a client and to give extra information about the client
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function edit(Client $client)
    {

        // -------------------------------------------------
        // Sjekker om brukeren har tillatelsen
        // -------------------------------------------------
        if (!Gate::allows('client.edit')) {
            // Hvis brukeren ikke har tillatelsen, returner en 403-feil
            abort(403, 'You do not have permission to create users.');
        }

        // -------------------------------------------------
        // Hent den første tilknyttede siten og brukeren
        // -------------------------------------------------
        $mainSite = $client->sites()->orderBy('created_at')->first(); 
        $mainUser = $client->users()->orderBy('created_at')->first();

        return view('tdClients::clients.edit', compact('client', 'mainSite', 'mainUser'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION update
    // Update the client information
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function update(Request $request, Client $client)
    {
        $client->update($request->all());
        return redirect()->route('clients.index');
    }


    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION destroy
    // Deletes or disable's the client, and the corresponding site's and user's
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function destroy(Client $client)
    {

        // -------------------------------------------------
        // Sjekker om brukeren har tillatelsen
        // -------------------------------------------------
        if (!Gate::allows('client.delete')) {
            // Hvis brukeren ikke har tillatelsen, returner en 403-feil
            abort(403, 'You do not have permission to create users.');
        }

        $client->delete();
        return redirect()->route('clients.index');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Profile
    // Shows a profile of the client, with all the information about the client
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function profile(Client $client)
    {

        // -------------------------------------------------
        // Sjekker om brukeren har tillatelsen
        // -------------------------------------------------
        if (!Gate::allows('client.view')) {
            // Hvis brukeren ikke har tillatelsen, returner en 403-feil
            abort(403, 'You do not have permission to create users.');
        }

        // -------------------------------------------------
        // Henter alle sites knyttet til klienten
        // -------------------------------------------------
        $sites = $client->sites;

        // -------------------------------------------------
        // Henter alle users knyttet til klienten
        // -------------------------------------------------
        $users = $client->users;


        // -------------------------------------------------
        // Return the view
        // -------------------------------------------------
        return view('tdClients::clients.profile', compact('client', 'sites', 'users'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION API GetSites
    // Shows all sites connected to a client
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function getSites(Client $client)
    {
        // -------------------------------------------------
        // Return all sites connected to the client
        // -------------------------------------------------
        return response()->json($client->sites); // Returner alle sites knyttet til klienten
    }
}
