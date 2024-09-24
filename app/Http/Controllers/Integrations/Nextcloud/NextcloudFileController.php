<?php
use GuzzleHttp\Client;

class NextcloudFileController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => config('services.nextcloud.base_url'),
        ]);
    }

    public function listFiles()
    {
        $response = $this->client->get('/remote.php/dav/files/{username}/', [
            'headers' => [
                'Authorization' => 'Bearer ' . auth()->user()->nextcloud_token,
            ],
        ]);

        $files = json_decode($response->getBody(), true);

        return view('files.index', compact('files'));
    }
}