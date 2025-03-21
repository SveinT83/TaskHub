<?php
namespace Taskhub\TdClients\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Taskhub\TdClients\Models\ClientSite;
use Taskhub\TdClients\Models\ClientUser;
use Illuminate\Http\JsonResponse;

class ClientUserApiController extends Controller
{
    public function index(ClientSite $site): JsonResponse
    {
        $users = $site->users;
        return response()->json($users);
    }

    public function show(ClientUser $user): JsonResponse
    {
        return response()->json($user);
    }

    /**
     * Hent alle brukere for en spesifikk klient.
     *
     * @param int $clientId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUsersByClient($clientId)
    {
        // Hent alle brukere knyttet til klienten via sites
        $users = ClientUser::where('client_id', $clientId)
                           ->get(['id', 'first_name', 'last_name', 'email']);

        return response()->json($users);
    }
}
