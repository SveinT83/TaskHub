<?php

// -------------------------------------------------
// Namespace
// -------------------------------------------------
namespace tronderdata\TdTask\Http\Controllers;

// -------------------------------------------------
// Dependencies
// -------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use tronderdata\TdTask\Models\TaskWall;

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CLASS TaskWallController
// 
// The TaskWallController is responsible for managing task "walls", which are used to group and organize tasks.
// 
// A "Wall" represents a collection of tasks that are grouped together under a specific category or project. 
// Walls serve as a way to visually and logically organize tasks within the system, making it easier to manage 
// related tasks for a specific project, department, or category.
//
// Functionality provided by this controller includes:
// 
// - Displaying a list of all task walls.
// - Showing the tasks that are grouped under a specific wall.
// - Creating, editing, and deleting task walls (future functionality).
// 
// The idea behind walls is to give users a flexible structure for managing their tasks in a project-based or 
// categorized manner, allowing for better task management and overview within the TaskHub system. Each wall can 
// contain multiple tasks, and walls themselves can be customized based on the needs of the user or organization.
//
// ---------------------------------------------------------------------------------------------------------------------------------------------------
class TaskWallController extends Controller
{
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION INDEX
    // Creating a view with all walls
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function index()
    {

        // -------------------------------------------------
        // Get all walls
        // -------------------------------------------------
        $walls = TaskWall::all();

        // -------------------------------------------------
        // Return view with walls
        // -------------------------------------------------
        return view('tdtask::walls.index', compact('walls'));
    }



    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // FUNCTION SHOW
    // Creating a view with a specific wallq
    //
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    public function show($id)
    {

        // -------------------------------------------------
        // Get wall by id
        // -------------------------------------------------
        $wall = TaskWall::findOrFail($id);

        // -------------------------------------------------
        // Return view with wall
        // -------------------------------------------------
        return view('tdtask::walls.show', compact('wall'));
    }


    
    // -------------------------------------------------
    // FUNCTION CREATE
    // Viser skjemaet for å opprette en ny "wall"
    // -------------------------------------------------
    public function create()
    {
        return view('tdtask::walls.create');
    }



    // -------------------------------------------------
    // FUNCTION STORE
    // Lagre en ny "wall" i databasen og legg til en menyoppføring
    // -------------------------------------------------
    public function store(Request $request)
    {
        // -------------------------------------------------
        // Validering av inputdata
        // -------------------------------------------------
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        // -------------------------------------------------
        // Legg til bruker ID som opprettet veggen
        // -------------------------------------------------
        $validatedData['created_by'] = Auth::id();

        // -------------------------------------------------
        // Finn "Tasks"-menyen ved hjelp av slug
        // -------------------------------------------------
        $taskMenu = DB::table('menus')->where('slug', 'tasks')->first();

        if ($taskMenu) {

            // -------------------------------------------------
            // Create the wall and store the entire object
            // -------------------------------------------------
            $taskWall = TaskWall::create($validatedData);

            // -------------------------------------------------
            // Legg til veggen som et meny-element under "Tasks"-menyen
            // -------------------------------------------------
            DB::table('menu_items')->insert([
                'menu_id' => $taskMenu->id,
                'parent_id' => null, // Hvis du vil ha den som et hovedmenypunkt
                'title' => $taskWall->name, // Bruk navnet på veggen
                'url' => "/walls/{$taskWall->id}", // Lagre relativ URL
                'icon' => 'bi bi-columns', // Valgfritt ikon for menyen
                'permission' => null, // Sett tillatelser hvis nødvendig
                'order' => 0, // Endre rekkefølgen hvis nødvendig
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // -------------------------------------------------
        // Omadresser tilbake til veggoversikten med suksessmelding
        // -------------------------------------------------
        return redirect()->route('walls.index')->with('success', 'Wall created successfully and added to menu.');
    }

}
