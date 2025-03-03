<?php

namespace TronderData\Equipment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use TronderData\Equipment\Models\Equipment;
use TronderData\Equipment\Models\EquipmentCategory;
use TronderData\Equipment\Models\ServiceHistory;

class ServiceController extends Controller
{
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION CREATE
    // Creates a new equipment service
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function create(Equipment $equipment)
    {
        // -------------------------------------------------
        // return view with equipment data
        // -------------------------------------------------
        return view('equipment::service.create', compact('equipment'));
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION STORE
    // Stores a new equipment service history
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function store(Request $request, Equipment $equipment)
    {
        // Validate the request
        $validated = $request->validate([
            'description' => 'required|string',
            'service_date' => 'required|date',
        ]);

        // Create the service history
        ServiceHistory::create([
            'equipment_id' => $equipment->id,
            'description' => $validated['description'],
            'service_date' => $validated['service_date'],
        ]);

        // Redirect to the equipment show view
        return redirect()->route('equipment.show', $equipment->id)->with('success', 'Servicehistorikk lagret.');
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION DESTROY
    // Deletes a specific service history entry
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function destroy(ServiceHistory $serviceHistory)
    {
        // Delete the service history entry
        $serviceHistory->delete();

        // Redirect back to the equipment show view
        return redirect()->route('equipment.show', $serviceHistory->equipment_id)->with('success', 'Servicehistorikk slettet.');
    }
}