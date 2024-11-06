<?php

namespace App\Http\Controllers\Admin\UserAndRoles;

use App\Http\Controllers\Controller;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Viser liste over brukere
    public function index()
    {
        $users = User::with('roles')->get();
        return view('admin.users.index', compact('users'));
    }

    // Viser skjema for å opprette ny bruker
    public function create()
    {
        $roles = Role::all(); // Henter alle tilgjengelige roller
        return view('admin.users.create', compact('roles'));
    }

    // Lagrer ny bruker
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
            'roles' => 'required'
        ]);

        // Opprett ny bruker
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Tildel roller
        $user->assignRole($request->roles);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    // Viser skjema for redigering av bruker
    public function edit(User $user)
    {
        $roles = Role::all(); // Henter alle roller
        $userRoles = $user->roles->pluck('name')->toArray(); // Henter brukerens nåværende roller

        return view('admin.users.edit', compact('user', 'roles', 'userRoles'));
    }

    // Oppdaterer en bruker
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'roles' => 'required'
        ]);

        // Oppdater brukeren
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password,
        ]);

        // Oppdater roller
        $user->syncRoles($request->roles);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    // Sletter en bruker
    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }
}
