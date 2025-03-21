<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER - INTEGRATIONS CONTROLLER
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This controller is responsible for handling integrations related actions such as displaying the integrations view.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Http\Controllers\Admin\Integrations;

use App\Http\Controllers\Controller;
use App\Models\Integration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class IntegrationsController extends Controller {

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - INDEX
    // --------------------------------------------------------------------------------------------------
    // This function returns the integrations view.
    // --------------------------------------------------------------------------------------------------
    public function index()
    {

        // -------------------------------------------------
        // Return all integrations from the database
        // -------------------------------------------------
        $integrations = Integration::all();

        // -------------------------------------------------
        // Return the integrations view
        // -------------------------------------------------
        return view('admin.integrations.index', compact('integrations'));
    }

    public function activate($id)
    {
        $integration = Integration::findOrFail($id);
        $integration->active = 1;
        $integration->save();

        // Add menu item
        DB::table('menu_items')->insert([
            'title' => ucfirst($integration->name),
            'url' => '/admin/integration/' . strtolower($integration->name),
            'menu_id' => 1,
            'parent_id' => 4,
            'icon' => $integration->icon,
            'is_parent' => 0,
            'order' => DB::table('menu_items')->max('order') + 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.integrations.index')->with('success', 'Integration activated successfully.');
    }

    public function deactivate($id)
    {
        $integration = Integration::findOrFail($id);
        $integration->active = 0;
        $integration->save();

        // Remove menu item
        DB::table('menu_items')->where('url', '/admin/integration/' . strtolower($integration->name))->delete();

        return redirect()->route('admin.integrations.index')->with('success', 'Integration deactivated successfully.');
    }
}
