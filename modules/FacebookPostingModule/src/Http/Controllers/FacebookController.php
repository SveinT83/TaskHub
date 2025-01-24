<?php

namespace Modules\FacebookPostingModule\Http\Controllers;

use Illuminate\Http\Request;
use Modules\FacebookPostingModule\Services\FacebookApiService;
use App\Http\Controllers\Controller;

class FacebookController extends Controller
{
    protected $facebookApiService;

    /**
     * FacebookController constructor.
     * @param FacebookApiService $facebookApiService The Facebook API service instance.
     */
    public function __construct(FacebookApiService $facebookApiService)
    {
        $this->facebookApiService = $facebookApiService;
    }

    /**
     * Display the Facebook posting form.
     */
    public function showPostForm()
    {
        return view('FacebookPostingModule::post_form');
    }

    /**
     * Display a list of Facebook groups.
     */
    public function listGroups()
    {
        $groups = $this->facebookApiService->getUserGroups();
        return response()->json(['groups' => $groups]);
    }

    /**
     * Handle a post to a Facebook group.
     */
    public function postToGroup(Request $request)
    {
        $validated = $request->validate([
            'group_id' => 'required|string',
            'message' => 'required|string',
        ]);

        $response = $this->facebookApiService->postToGroup(
            $validated['group_id'],
            $validated['message']
        );

        if (!empty($response['error'])) {
            return response()->json(['error' => $response['error']], 400);
        }

        return response()->json(['success' => true, 'response' => $response]);
    }
}
