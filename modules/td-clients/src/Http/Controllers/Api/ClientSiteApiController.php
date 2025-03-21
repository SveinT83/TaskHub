<?php
namespace Taskhub\TdClients\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Taskhub\TdClients\Models\Client;
use Taskhub\TdClients\Models\ClientSite;
use Illuminate\Http\JsonResponse;

class ClientSiteApiController extends Controller
{
    public function index(Client $client): JsonResponse
    {
        $sites = $client->sites;
        return response()->json($sites);
    }

    public function show(ClientSite $site): JsonResponse
    {
        return response()->json($site);
    }
}
