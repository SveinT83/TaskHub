<?php

// -------------------------------------------------
// Namespace
// -------------------------------------------------
namespace tronderdata\TdTask\Http\Controllers;

// -------------------------------------------------
// Dependencies
// -------------------------------------------------
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
}
