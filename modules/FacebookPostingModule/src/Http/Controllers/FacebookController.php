<?php

namespace Modules\FacebookPostingModule\Http\Controllers;

use Illuminate\Http\Request;
use Modules\FacebookPostingModule\Services\FacebookApiService;
use App\Http\Controllers\Controller;

class FacebookController extends Controller
{
    /**
     * Display a list of Facebook groups the authenticated user belongs to.
     *
     * This method uses the FacebookApiService to fetch the user's groups.
     * It will return a JSON response with the list of groups.
     */
    public function listGroups(FacebookApiService $facebookApiService)
    {
        // Call the service method to get the groups
        $groups = $facebookApiService->getUserGroups();

        // Return the response in JSON format (could be a view or different format in a production app)
        return response()->json(['groups' => $groups]);
    }

    /**
     * Post a message to a specific Facebook group.
     *
     * The request should contain the group ID and the message to post.
     */
    public function postToGroup(Request $request, FacebookApiService $facebookApiService)
    {
        // Validate the incoming request to ensure the necessary data is present
        $validated = $request->validate([
            'group_id' => 'required|string',
            'message' => 'required|string',
        ]);

        // Post the message to the specified group using the FacebookApiService
        $response = $facebookApiService->postToGroup($validated['group_id'], $validated['message']);

        // Return the response as a JSON response (success/failure message)
        return response()->json($response);
    }

    /**
     * Post a message to a Facebook page.
     *
     * This method works similarly to postToGroup but posts to a page.
     */
    public function postToPage(Request $request, FacebookApiService $facebookApiService)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'page_id' => 'required|string',
            'message' => 'required|string',
        ]);

        // Post the message to the page using the FacebookApiService
        $response = $facebookApiService->postToPage($validated['page_id'], $validated['message']);

        // Return the response as a JSON response (success/failure message)
        return response()->json($response);
    }
}
