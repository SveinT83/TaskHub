<?php
namespace tronderdata\TdClients\Http\Controllers;

use App\Http\Controllers\Controller;
use tronderdata\TdClients\Models\Client;
use tronderdata\TdClients\Models\ClientSite;
use Illuminate\Http\Request;
use tronderdata\TdClients\Models\ClientUser;

class ClientUserController extends Controller
{

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION index
    // Shows all users of the clients
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function index()
    {
        // -------------------------------------------------
        // Get all users of the clients
        // -------------------------------------------------
        $users = ClientUser::all();

        // -------------------------------------------------
        // Return the view with the users
        // -------------------------------------------------
        return view('tdClients::users.index', compact('users'));
    }


    
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Create
    // Shows a form to create a new user
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function create()
    {
        // -------------------------------------------------
        // Hent alle klienter
        // -------------------------------------------------
        $clients = Client::all();

        // -------------------------------------------------
        // Send klientene til visningen
        // -------------------------------------------------
        return view('tdClients::users.create', compact('clients'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Store
    // Stores a new user
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function store(Request $request)
    {
        // -------------------------------------------------
        // Valider dataene
        // -------------------------------------------------
        ClientUser::create($request->all());

        // -------------------------------------------------
        // Return to the index view
        // -------------------------------------------------
        return redirect()->route('client.users.index');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION edit
    // Shows a form to edit a user
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function edit(ClientUser $user)
    {
        // -------------------------------------------------
        // Hent alle sites for den klienten som brukeren er tilknyttet
        // -------------------------------------------------
        $sites = ClientSite::where('client_id', $user->client_id)->get();

        // -------------------------------------------------
        // Return visningen med brukeren og sites
        // -------------------------------------------------
        return view('tdClients::users.edit', compact('user', 'sites'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Update
    // Updates a user
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function update(Request $request, ClientUser $user)
    {
        // -------------------------------------------------
        // Valider dataene
        // -------------------------------------------------
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'site_id' => 'required|exists:client_sites,id', // Sjekker at site_id eksisterer
        ]);

        // -------------------------------------------------
        // Oppdater brukeren med de nye verdiene
        // -------------------------------------------------
        $user->update($validated);

        return redirect()->route('client.users.index')->with('success', 'User updated successfully.');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Destroy
    // Deletes a user
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function destroy(ClientUser $user)
    {
        // -------------------------------------------------
        // Delete the user
        // -------------------------------------------------
        $user->delete();

        // -------------------------------------------------
        // Return to the index view
        // -------------------------------------------------
        return redirect()->route('client.users.index');
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION Profile
    // Shows a profile of a user and all information about it
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function profile(ClientUser $user)
    {
        // -------------------------------------------------
        // Return the view with the user
        // -------------------------------------------------
        return view('tdClients::users.profile', compact('user'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION getSites
    // Shows all sites connected to a client
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function getSites(Client $client)
    {
        // -------------------------------------------------
        // Returner alle sites knyttet til klienten
        // -------------------------------------------------
        return response()->json($client->sites);
    }
}
