<?php

namespace TronderData\Equipment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use TronderData\Equipment\Models\Equipment;
use TronderData\Equipment\Models\EquipmentCategory;
use TronderData\Equipment\Models\ServiceHistory;
use TronderData\Equipment\Models\Vendors;


class EquipmentController extends Controller
{
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION INDEX
    // Shows the default page of the equipment module
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function index(Request $request)
    {
        // Get sorting parameters
        $sortField = $request->get('sort_field', 'name');
        $sortOrder = $request->get('sort_order', 'asc');

        // Get all equipment with sorting
        $equipment = Equipment::orderBy($sortField, $sortOrder)->paginate(10);

        // Return view
        return view('equipment::index', compact('equipment', 'sortField', 'sortOrder'));
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION SHOW
    // Shows the details of a specific equipment
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function show(Equipment $equipment)
    {
        // -------------------------------------------------
        // Fetch service history for the equipment
        // -------------------------------------------------
        $serviceHistory = ServiceHistory::where('equipment_id', $equipment->id)->get();

        // -------------------------------------------------
        // If no service history exists, set historyCount to 0
        // -------------------------------------------------
        $historyCount = $serviceHistory->count() ? $serviceHistory->count() : 0;

        // -------------------------------------------------
        // Return view with equipment and service history
        // -------------------------------------------------
        return view('equipment::show', compact('equipment', 'serviceHistory', 'historyCount'));
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION CREATE
    // Creates a new equipment
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function create()
   { 
        // -------------------------------------------------
        // Get all categories
        // -------------------------------------------------
        $categories = EquipmentCategory::equipmentCategories()->get();

        // -------------------------------------------------
        // Get all suppliers
        // -------------------------------------------------
        $vendors = Vendors::getvendors();

        // -------------------------------------------------
        // return view
        // -------------------------------------------------
        return view('equipment::create', compact('categories', 'vendors'));
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION STORE
    // Stores a new equipment
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function store(Request $request)
    {

        // -------------------------------------------------
        // Validate the request
        // -------------------------------------------------
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'internal_number' => 'nullable|string|max:255',
            'vendor_id' => 'min:1|max:20',
            'category_id' => 'required|min:1|max:5',
            'serial_number' => 'string|unique:equipment,serial_number',
            'status' => 'required|in:active,inactive,needs_certification',
            'certification_month' => 'nullable|integer|min:1|max:12',
            'description' => 'nullable|string',
        ]);

        // -------------------------------------------------
        // Store the equipment
        // -------------------------------------------------
        Equipment::create($validated);


        // -------------------------------------------------
        // return view
        // -------------------------------------------------
        return redirect()->route('equipment.index')->with('success', 'Utstyr registrert.');
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION EDIT
    // Edits a specific equipment
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function edit(Equipment $equipment)
    {
        // -------------------------------------------------
        // Get all categories (kun de relevante for td-equipment)
        // -------------------------------------------------
        $categories = EquipmentCategory::equipmentCategories()->get();

        // -------------------------------------------------
        // Get all suppliers
        // -------------------------------------------------
        $vendors = Vendors::getvendors();

        // -------------------------------------------------
        // Hent lagret leverandør og kategori (hvis de finnes)
        // -------------------------------------------------
        $selectedvendor = $equipment->vendor_id ? Vendors::find($equipment->vendor_id) : null;
        $selectedCategory = $equipment->category_id ? EquipmentCategory::find($equipment->category_id) : null;

        // -------------------------------------------------
        // return view
        // -------------------------------------------------
        return view('equipment::edit', compact('equipment', 'categories', 'vendors', 'selectedvendor', 'selectedCategory'));
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION IUPDATE
    // Updates a specific equipment
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function update(Request $request, Equipment $equipment)
    {

        // -------------------------------------------------
        // Validate the request
        // -------------------------------------------------
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'internal_number' => 'nullable|string|max:255',
            'vendor_id' => 'min:1|max:20',
            'category_id' => 'required|min:1|max:20',
            'serial_number' => 'string',
            'status' => 'required|in:active,inactive,needs_certification',
            'certification_month' => 'nullable|integer|min:1|max:12',
            'description' => 'nullable|string',
        ]);

        // -------------------------------------------------
        // Update the equipment
        // -------------------------------------------------
        $equipment->update($validated);

        // -------------------------------------------------
        // return view
        // -------------------------------------------------
        return redirect()->route('equipment.index')->with('success', 'Utstyr oppdatert.');
    }

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION DESTROY
    // DESTROYS a specific equipment
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function destroy(Equipment $equipment)
    {

        // -------------------------------------------------
        // Delete the equipment
        // -------------------------------------------------
        $equipment->delete();

        // -------------------------------------------------
        // return view
        // -------------------------------------------------
        return redirect()->route('equipment.index')->with('success', 'Utstyr slettet.');
    }
}
