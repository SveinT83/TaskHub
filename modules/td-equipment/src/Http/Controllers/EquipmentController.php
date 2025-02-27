<?php

namespace TronderData\Equipment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use TronderData\Equipment\Models\Equipment;
use TronderData\Equipment\Models\EquipmentCategory;


class EquipmentController extends Controller
{
    /**
     * 📌 1️⃣ Hovedvisning: Liste over alt utstyr
     */
    public function index()
    {
        $equipment = Equipment::paginate(10);
        return view('equipment::index', compact('equipment'));
    }

    /**
     * 📌 2️⃣ Vis spesifikt utstyr (Profilside)
     */
    public function show(Equipment $equipment)
    {
        return view('equipment.show', compact('equipment'));
    }

    /**
     * 📌 3️⃣ Registreringsside: Legg til nytt utstyr
     */
    public function create()
    {
        return view('equipment.create');
    }

    /**
     * 📌 4️⃣ Lagrer nytt utstyr
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:equipment_categories,id',
            'serial_number' => 'required|string|unique:equipment,serial_number',
            'status' => 'required|in:active,inactive,needs_certification',
            'certification_month' => 'nullable|integer|min:1|max:12',
            'description' => 'nullable|string',
        ]);

        Equipment::create($validated);

        return redirect()->route('equipment.index')->with('success', 'Utstyr registrert.');
    }

    /**
     * 📌 5️⃣ Rediger utstyr
     */
    public function edit(Equipment $equipment)
    {
        return view('equipment.edit', compact('equipment'));
    }

    /**
     * 📌 6️⃣ Oppdater utstyr
     */
    public function update(Request $request, Equipment $equipment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:equipment_categories,id',
            'serial_number' => "required|string|unique:equipment,serial_number,{$equipment->id}",
            'status' => 'required|in:active,inactive,needs_certification',
            'certification_month' => 'nullable|integer|min:1|max:12',
            'description' => 'nullable|string',
        ]);

        $equipment->update($validated);

        return redirect()->route('equipment.index')->with('success', 'Utstyr oppdatert.');
    }

    /**
     * 📌 7️⃣ Slett utstyr
     */
    public function destroy(Equipment $equipment)
    {
        $equipment->delete();
        return redirect()->route('equipment.index')->with('success', 'Utstyr slettet.');
    }
}
