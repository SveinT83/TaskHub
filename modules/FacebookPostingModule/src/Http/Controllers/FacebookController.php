<?php
namespace Modules\FacebookPostingModule\src\Http\Controllers;

use Illuminate\Http\Request;
use Modules\FacebookPostingModule\Services\FacebookApiService;

class FacebookController extends Controller
{
    protected $facebookApiService;

    public function __construct(FacebookApiService $facebookApiService)
    {
        $this->facebookApiService = $facebookApiService;
    }

    public function listGroups()
    {
        $groups = $this->facebookApiService->getUserGroups();
        return view('facebookposter::facebook', compact('groups'));
    }

    public function postToGroup(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|string',
            'message' => 'required|string',
        ]);

        $response = $this->facebookApiService->postToGroup($validated['group_id'], $validated['message']);
        return response()->json($response);
    }
}
