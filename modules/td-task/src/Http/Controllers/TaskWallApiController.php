<?php
namespace tronderdata\TdTask\Http\Controllers\Api;

use tronderdata\TdTask\Models\TaskWall;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaskWallApiController extends Controller
{
    // Hent alle vegger
    public function index()
    {
        $walls = TaskWall::all();
        return response()->json($walls);
    }

    // Hent en spesifikk vegg
    public function show($id)
    {
        $wall = TaskWall::findOrFail($id);
        return response()->json($wall);
    }

    // Opprett en ny vegg
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'created_by' => 'required|exists:users,id',
        ]);

        $wall = TaskWall::create($validatedData);

        return response()->json(['message' => 'Wall created successfully', 'wall' => $wall], 201);
    }

    // Oppdater en vegg
    public function update(Request $request, $id)
    {
        $wall = TaskWall::findOrFail($id);
        $wall->update($request->all());

        return response()->json(['message' => 'Wall updated successfully', 'wall' => $wall]);
    }

    // Slett en vegg
    public function destroy($id)
    {
        $wall = TaskWall::findOrFail($id);
        $wall->delete();

        return response()->json(['message' => 'Wall deleted successfully']);
    }
}
