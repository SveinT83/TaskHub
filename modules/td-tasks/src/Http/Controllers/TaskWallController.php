<?php
// -------------------------------------------------
// Namespace
// -------------------------------------------------
namespace tronderdata\TdTasks\Http\Controllers;

// -------------------------------------------------
// Dependencies
// -------------------------------------------------
use App\Http\Controllers\Controller;
use App\Models\TaskWall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskWallController extends Controller
{
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION INDEX
    //
    // Shows all task walls
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function index()
    {

        // -------------------------------------------------
        // Get all task walls
        // -------------------------------------------------
        $walls = TaskWall::with('tasks')->get();

        // -------------------------------------------------
        // Return the view
        // -------------------------------------------------
        return view('walls.index', compact('walls'));
    }

    public function create()
    {
        return view('walls.create');
    }

    public function store(Request $request)
    {

        // -------------------------------------------------
        // Validate input
        // -------------------------------------------------
        $wall = TaskWall::create([
            'name' => $request->name,
            'description' => $request->description,
            'created_by' => Auth::id(),
        ]);


        // -------------------------------------------------
        // Return to the index view with a success message
        // -------------------------------------------------
        return redirect()->route('walls.index')->with('success', 'Task Wall created!');
    }

    // Additional methods...
}
