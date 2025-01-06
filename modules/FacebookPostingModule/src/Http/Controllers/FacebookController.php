<?php

namespace Modules\FacebookPostingModule\Http\Controllers;

use Illuminate\Http\Request;
use Modules\FacebookPostingModule\Services\FacebookApiService;
use App\Http\Controllers\Controller;

class FacebookController extends Controller
{
    /**protected $facebookApiService;

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
    } */
    public function listGroups()
    {
        return response()->json(['groups' => ['Group 1', 'Group 2']]);
    }

    public function postToGroup(Request $request)
    {
        $groupId = $request->input('group_id');
        $message = $request->input('message');

        // Simulated response for now
        return response()->json(['success' => true, 'group_id' => $groupId, 'message' => $message]);
    }
}
