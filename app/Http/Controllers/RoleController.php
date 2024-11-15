<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // Viser roller og tillatelser
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();

        $user = Auth::user(); // Få tilgang til innlogget bruker

        return view('roles.index', compact('roles', 'permissions'));
    }

    // Viser skjema for å opprette ny rolle
    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    // Lagrer ny rolle
    public function store(Request $request)
    {
        $role = Role::create(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }

    // Viser skjema for redigering av rolle
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('roles.edit', compact('role', 'permissions'));
    }

    // Oppdaterer en rolle
    public function update(Request $request, Role $role)
    {
        $role->update(['name' => $request->name]);
        $role->syncPermissions($request->permissions);
        return redirect()->route('roles.index')->with('success', 'Role updated successfully');
    }

    // Sletter en rolle
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully');
    }

    // ----------------------
    // Tillatelser (permissions) funksjoner
    // ----------------------

    // Viser skjema for å opprette ny tillatelse
    public function createPermission()
    {
        return view('permissions.create');
    }

    // Lagrer ny tillatelse
    public function storePermission(Request $request)
    {
        Permission::create(['name' => $request->name]);
        return redirect()->route('roles.index')->with('success', 'Permission created successfully');
    }

    // Viser skjema for redigering av tillatelse
    public function editPermission(Permission $permission)
    {
        return view('permissions.edit', compact('permission'));
    }

    // Oppdaterer en tillatelse
    public function updatePermission(Request $request, Permission $permission)
    {
        $permission->update(['name' => $request->name]);
        return redirect()->route('roles.index')->with('success', 'Permission updated successfully');
    }

    // Sletter en tillatelse
    public function destroyPermission(Permission $permission)
    {
        $permission->delete();
        return redirect()->route('roles.index')->with('success', 'Permission deleted successfully');
    }
}
