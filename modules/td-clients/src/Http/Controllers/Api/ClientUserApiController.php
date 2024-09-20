<?php
namespace tronderdata\TdClients\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use tronderdata\TdClients\Models\ClientSite;
use tronderdata\TdClients\Models\ClientUser;
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
}
