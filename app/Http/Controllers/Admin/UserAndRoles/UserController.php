<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER - USER CONTROLLER
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This controller is responsible for handling user related actions such as creating, updating, and deleting users.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Http\Controllers\Admin\UserAndRoles;

use App\Http\Controllers\Controller;

// --------------------------------------------------------------------------------------------------
// MODEL - USER
// --------------------------------------------------------------------------------------------------
// This controller uses the User model to manage user-related operations.
// --------------------------------------------------------------------------------------------------
use App\Models\User;

// --------------------------------------------------------------------------------------------------
// MODEL - ROLE
// --------------------------------------------------------------------------------------------------
// This controller uses the Role model to manage role-related operations. 
// --------------------------------------------------------------------------------------------------
use Spatie\Permission\Models\Role;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $middleware = ['role:superadmin']; //Ser ikke ut til å virke

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - INDEX
    // Index function returns the view with all users.
    // --------------------------------------------------------------------------------------------------
    public function index()
    {
        // -------------------------------------------------
        // Retrieve all users with their roles.
        // -------------------------------------------------
        $users = User::with('roles')->get();

        // -------------------------------------------------
        // Return the view with all users.
        // -------------------------------------------------
        return view('admin.users.index', compact('users'));
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - CREATE
    // Create function returns the view with the form to create a new user.
    // --------------------------------------------------------------------------------------------------
    public function create()
    {
        // -------------------------------------------------
        // Retrieve all available roles.
        // -------------------------------------------------
        $roles = Role::all();

        // -------------------------------------------------
        // Return the view with the form to create a new user.
        // -------------------------------------------------
        return view('admin.users.create', compact('roles'));
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - STORE
    // Store function creates a new user.
    // --------------------------------------------------------------------------------------------------
    public function store(Request $request)
    {

        // -------------------------------------------------
        // Validate the request
        // -------------------------------------------------
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'roles' => 'required'
        ]);

        // -------------------------------------------------
        // Create the user
        // -------------------------------------------------
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // -------------------------------------------------
        // Assign roles to the user
        // -------------------------------------------------
        $user->assignRole($request->roles);

        // -------------------------------------------------
        // Redirect the user back to the users index page with a success message.
        // -------------------------------------------------
        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - EDIT
    // Edit function returns the view with the form to edit a user.
    // --------------------------------------------------------------------------------------------------
    public function edit(User $user)
    {
        // -------------------------------------------------
        // Get all roles
        // -------------------------------------------------
        $roles = Role::all();

        // -------------------------------------------------
        // Get the user current roles
        // -------------------------------------------------
        $userRoles = $user->roles->pluck('name')->toArray();

        // -------------------------------------------------
        // Return the view with the form to edit a user.
        // -------------------------------------------------
        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - UPDATE
    // Update function updates a user.
    // --------------------------------------------------------------------------------------------------
    public function update(Request $request, User $user)
    {

        // -------------------------------------------------
        // Validate the request
        // -------------------------------------------------
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'roles' => 'required'
        ]);

        // -------------------------------------------------
        // Update the user
        // -------------------------------------------------
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        // -------------------------------------------------
        // Sync the user roles
        // -------------------------------------------------
        $user->syncRoles($request->roles);

        // -------------------------------------------------
        // Redirect the user back to the users index page with a success message.
        // -------------------------------------------------
        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - DESTROY
    // Destroy function deletes a user.
    // --------------------------------------------------------------------------------------------------
    public function destroy(User $user)
    {

        // -------------------------------------------------
        // Delete the user
        // -------------------------------------------------
        $user->delete();

        // -------------------------------------------------
        // Redirect the user back to the users index page with a success message.
        // -------------------------------------------------
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
