<?php

namespace tronderdata\TdClients\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use tronderdata\TdClients\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientApiController extends Controller
{
    // Hent alle klienter
    public function index(): JsonResponse
    {
        $clients = Client::all();
        return response()->json($clients);
    }

    // Hent en spesifikk klient basert på ID
    public function show(Client $client): JsonResponse
    {
        return response()->json($client);
    }

    // Opprett en ny klient
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'main_email' => 'required|email|max:255',
            'vat_number' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
        ]);

        $client = Client::create($validated);

        return response()->json($client, 201); // 201 Created
    }

    // Oppdater en eksisterende klient
    public function update(Request $request, Client $client): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'main_email' => 'required|email|max:255',
            'vat_number' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
        ]);

        $client->update($validated);

        return response()->json($client);
    }

    // Slett en klient
    public function destroy(Client $client): JsonResponse
    {
        $client->delete();

        return response()->json(['message' => 'Client deleted successfully.']);
    }
}
