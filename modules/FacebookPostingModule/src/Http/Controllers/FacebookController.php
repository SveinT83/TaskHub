<?php

namespace Modules\FacebookPostingModule\Http\Controllers;

use Illuminate\Http\Request;
use Modules\FacebookPostingModule\Services\FacebookApiService;
use App\Http\Controllers\Controller;

class FacebookController extends Controller
{
    protected $facebookApiService;

    public function __construct(FacebookApiService $facebookApiService)
    {
        $this->facebookApiService = $facebookApiService;
    }

    /**
     * Display the Facebook posting form.
     *
     * This method renders a view where users can post to a page or group.
     */
    public function showPostForm()
    {
        return view('FacebookPostingModule::post_form');
    }

    /**
     * Display a list of Facebook groups the authenticated user belongs to.
     *
     * This method uses the FacebookApiService to fetch the user's groups.
     * It will return a JSON response with the list of groups or render a view in the future.
     */
    public function listGroups()
    {
        $groups = $this->facebookApiService->getUserGroups();
        return response()->json(['groups' => $groups]);
    }

    /**
     * Handle a post to a Facebook group.
     *
     * The request should contain the group ID and the message to post.
     */
    public function postToGroup(Request $request)
    {
        // Validate the incoming request to ensure the necessary data is present
        $validated = $request->validate([
            'group_id' => 'required|string',
            'message' => 'required|string',
            'access_token' => 'required|string', // Validate access token
        ]);

        // Post the message to the specified group using the FacebookApiService
        $response = $this->facebookApiService->postToGroup(
            $validated['group_id'],
            $validated['message'],
            $validated['access_token'] // Pass access token for API call
        );

        // Return the response as JSON or redirect back to a view with a success message
        if (isset($response['success'])) {
            return redirect()->back()->with('success', 'Message posted to group successfully!');
        }

        return redirect()->back()->with('error', 'Failed to post message to the group.');
    }

    /**
     * Handle a post to a Facebook page.
     *
     * This method works similarly to postToGroup but posts to a page.
     */
    public function postToPage(Request $request)
    {
        // Validate the incoming request
        $validated = $request->validate([
            'page_id' => 'required|string',
            'message' => 'required|string',
            'access_token' => 'required|string', // Validate access token
        ]);

        // Post the message to the page using the FacebookApiService
        $response = $this->facebookApiService->postToPage(
            $validated['page_id'],
            $validated['message'],
            $validated['access_token'] // Pass access token for API call
        );

        // Return the response as JSON or redirect back to a view with a success message
        if (isset($response['success'])) {
            return redirect()->back()->with('success', 'Message posted to page successfully!');
        }

        return redirect()->back()->with('error', 'Failed to post message to the page.');
    }
}
