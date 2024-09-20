<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MenuConfigurationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//Roles
Route::middleware(['auth'])->group(function () {
    // Ruter for roller
    Route::resource('roles', RoleController::class);

    // Ruter for tillatelser
    Route::get('permissions/create', [RoleController::class, 'createPermission'])->name('permissions.create');
    Route::post('permissions/store', [RoleController::class, 'storePermission'])->name('permissions.store');
    Route::get('permissions/{permission}/edit', [RoleController::class, 'editPermission'])->name('permissions.edit');
    Route::put('permissions/{permission}', [RoleController::class, 'updatePermission'])->name('permissions.update');
    Route::delete('permissions/{permission}', [RoleController::class, 'destroyPermission'])->name('permissions.destroy');
});

//Users
Route::middleware(['auth'])->group(function () {
    // Liste over brukere
    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    // Opprett ny bruker
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    // Rediger bruker
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');

    // Slett bruker
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
});

//Config / Menus
Route::middleware(['auth'])->group(function () {
    // Route for Menu Configurations view
    Route::get('/configurations/menu', [MenuConfigurationController::class, 'index'])->name('menu.configurations');
    
    // Route for creating a new menu
    Route::post('/configurations/menu/create', [MenuConfigurationController::class, 'store'])->name('menu.create');

    // Route for listing and managing menu items for a specific menu
    Route::get('/configurations/menu/{menu}/items', [MenuConfigurationController::class, 'show'])->name('menu.items');

    // Route for adding/editing menu items
    Route::post('/configurations/menu/{menu}/items/create', [MenuConfigurationController::class, 'storeItem'])->name('menu.items.create');
    Route::get('/configurations/menu/{menu}/items/{item}/edit', [MenuConfigurationController::class, 'editItem'])->name('menu.items.edit');
    Route::post('/configurations/menu/{menu}/items/{item}/update', [MenuConfigurationController::class, 'updateItem'])->name('menu.items.update');
});




require __DIR__.'/auth.php';
