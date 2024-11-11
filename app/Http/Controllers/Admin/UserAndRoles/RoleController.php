<?php

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// CONTROLLER - ROLE CONTROLLER
// ---------------------------------------------------------------------------------------------------------------------------------------------------
// This controller is responsible for handling profile related actions such as updating the user's profile information and deleting the user's account.
// ---------------------------------------------------------------------------------------------------------------------------------------------------

namespace App\Http\Controllers\Admin\UserAndRoles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // --------------------------------------------------------------------------------------------------
    // FUNCTION - INDEX
    // --------------------------------------------------------------------------------------------------
    // This function returns the view with the user's information.
    // --------------------------------------------------------------------------------------------------
    public function index()
    {

        // -------------------------------------------------
        // Get all roles and permissions
        // -------------------------------------------------
        $roles = Role::all();
        $permissions = Permission::all();

        // -------------------------------------------------
        // Get the logged in user
        // -------------------------------------------------
        $user = Auth::user(); // Få tilgang til innlogget bruker

        // -------------------------------------------------
        // Return the view with the user's information.
        // -------------------------------------------------
        return view('admin/users/roles.index', compact('roles', 'permissions'));
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - CREATE
    // --------------------------------------------------------------------------------------------------
    // This function returns the view with the user's information.
    // --------------------------------------------------------------------------------------------------
    public function create()
    {
        // -------------------------------------------------
        // Get all permissions
        // -------------------------------------------------
        $permissions = Permission::all();

        // -------------------------------------------------
        // Return the view with the user's information.
        // -------------------------------------------------
        return view('admin/users/roles.create', compact('permissions'));
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - STORE
    // --------------------------------------------------------------------------------------------------
    // This function saves a new role to the database.
    // --------------------------------------------------------------------------------------------------
    public function store(Request $request)
    {

        // -------------------------------------------------
        // Get all permissions and create a new role
        // -------------------------------------------------
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        // -------------------------------------------------
        // Redirect to the roles index page with a success message
        // -------------------------------------------------
        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }

    

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - EDIT
    // --------------------------------------------------------------------------------------------------
    // This function returns the edit view for a role.
    // --------------------------------------------------------------------------------------------------
    public function edit(Role $role)
    {

        // -------------------------------------------------
        // Get all permissions
        // -------------------------------------------------
        $permissions = Permission::all();

        // -------------------------------------------------
        // Return the view with the user's information.
        // -------------------------------------------------
        return view('admin/users/roles.edit', compact('role', 'permissions'));
    }

    

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - UPDATE
    // --------------------------------------------------------------------------------------------------
    // This function updates a role in the database.
    // --------------------------------------------------------------------------------------------------
    public function update(Request $request, Role $role)
    {

        // -------------------------------------------------
        // Get all permissions and update the role
        // -------------------------------------------------
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);

        // -------------------------------------------------
        // Redirect to the roles index page with a success message
        // -------------------------------------------------
        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }

    

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - DESTROY
    // --------------------------------------------------------------------------------------------------
    // This function deletes a role from the database.
    // --------------------------------------------------------------------------------------------------
    public function destroy(Role $role)
    {

        // -------------------------------------------------
        // Delete the role
        // -------------------------------------------------
        $role->delete();

        // -------------------------------------------------
        // Redirect to the roles index page with a success message
        // -------------------------------------------------
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }



    // --------------------------------------------------------------------------------------------------
    // FUNCTION - CREATE PERMISSION
    // --------------------------------------------------------------------------------------------------
    // This function returns the view for creating a new permission.
    // --------------------------------------------------------------------------------------------------
    public function createPermission()
    {

        // -------------------------------------------------
        // Return the view for creating a new permission.
        // -------------------------------------------------
        return view('admin.users.permissions.create');
    }

    

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - STORE PERMISSION
    // --------------------------------------------------------------------------------------------------
    // This function saves a new permission to the database.
    // --------------------------------------------------------------------------------------------------
    public function storePermission(Request $request)
    {

        // -------------------------------------------------
        // Create a new permission
        // -------------------------------------------------
        Permission::create(['name' => $request->name]);

        // -------------------------------------------------
        // Redirect to the roles index page with a success message
        // -------------------------------------------------
        return redirect()->route('roles.index')->with('success', 'Permission created successfully');
    }

    

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - EDIT PERMISSION
    // --------------------------------------------------------------------------------------------------
    // This function returns the edit view for a permission.
    // --------------------------------------------------------------------------------------------------
    public function editPermission(Permission $permission)
    {
        // -------------------------------------------------
        // Return the view for editing a permission.
        // -------------------------------------------------
        return view('admin.users.permissions.edit', compact('permission'));
    }

    

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - UPDATE PERMISSION
    // --------------------------------------------------------------------------------------------------
    // This function updates a permission in the database.
    // --------------------------------------------------------------------------------------------------
    public function updatePermission(Request $request, Permission $permission)
    {

        // -------------------------------------------------
        // Update the permission
        // -------------------------------------------------
        $permission->update(['name' => $request->name]);

        // -------------------------------------------------
        // Redirect to the roles index page with a success message
        // -------------------------------------------------
        return redirect()->route('roles.index')->with('success', 'Permission updated successfully');
    }

    

    // --------------------------------------------------------------------------------------------------
    // FUNCTION - DESTROY PERMISSION
    // --------------------------------------------------------------------------------------------------
    // This function deletes a permission from the database.
    // --------------------------------------------------------------------------------------------------
    public function destroyPermission(Permission $permission)
    {

        // -------------------------------------------------
        // Delete the permission
        // -------------------------------------------------
        $permission->delete();

        // -------------------------------------------------
        // Redirect to the roles index page with a success message
        // -------------------------------------------------
        return redirect()->route('roles.index')->with('success', 'Permission deleted successfully');
    }
}
