<?php

namespace Modules\Projects\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Projects\Models\Project;
use Modules\Customers\Models\Customer;
use Illuminate\Support\Facades\Mail;
use Modules\Invoicing\Mail\RepairEstimateMail;
use Illuminate\Support\Facades\Auth;

class ProjectController extends Controller
{
    public function index()
    {
        $this->authorizeRoles(['admin', 'manager', 'technician']);
        $projects = Project::with('customer')->get();
        return view('projects::index', compact('projects'));
    }

    public function create()
    {
        $this->authorizeRoles(['admin', 'manager', 'technician']);
        $customers = Customer::all();
        return view('projects::create', compact('customers'));
    }

    public function store(Request $request)
    {
        $this->authorizeRoles(['admin', 'manager', 'technician']);

        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        Project::create([
            'project_number' => 'P-' . now()->format('Ymd') . '-' . strtoupper(\Str::random(4)),
            'customer_id' => $request->customer_id,
            'name' => $request->name,
            'description' => $request->description,
            'status' => 'pending',
        ]);

        return redirect()->route('projects.index')->with('success', 'Project created!');
    }

    public function edit($id)
    {
        $this->authorizeRoles(['admin', 'manager', 'technician']);

        $project = Project::findOrFail($id);
        $customers = Customer::all();
        return view('projects::edit', compact('project', 'customers'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeRoles(['admin', 'manager', 'technician']);

        $project = Project::findOrFail($id);
        $user = auth()->user();
        $data = [];

        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'status' => 'required|string',
                'description' => 'nullable|string',
            ]);
            $data = $request->only(['customer_id', 'status', 'description']);
        } elseif ($user->hasRole('technician')) {
            $request->validate([
                'description' => 'nullable|string',
            ]);
            $data = $request->only(['description']);
        }

        $project->update($data);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully!');
    }

    public function destroy($id)
    {
        $this->authorizeRoles(['admin']);

        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted!');
    }

    public function sendRepairEstimate($projectId)
    {
        $this->authorizeRoles(['admin', 'manager']);

        $project = Project::findOrFail($projectId);

        $n8nWebhookUrl = env('N8N_PROJECT_APPROVAL_URL');
        if ($n8nWebhookUrl) {
            $client = new \GuzzleHttp\Client();
            $client->post($n8nWebhookUrl, [
                'json' => [
                    'project_id' => $project->id,
                    'customer_email' => $project->customer->email,
                    'estimated_cost' => $project->estimated_cost,
                ]
            ]);
        }

        Mail::to($project->customer->email)->send(new RepairEstimateMail($project));

        return back()->with('success', 'Repair estimate sent to customer.');
    }

    /**
     * Role gate.
     */
    private function authorizeRoles(array $roles)
    {
        $user = Auth::user();

        if (!$user || !$user->hasAnyRole($roles)) {
            abort(403, 'Unauthorized action.');
        }
    }
}