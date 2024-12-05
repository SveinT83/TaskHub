<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserAndRoles\UserController;
use App\Http\Controllers\Admin\UserAndRoles\RoleController;
use App\Http\Controllers\Admin\Configurations\ConfigurationsController;
use App\Http\Controllers\Admin\Configurations\MenuConfigurationController;
use App\Http\Controllers\Admin\Configurations\EmailAccountController;
use App\Http\Controllers\Admin\Integrations\IntegrationsController;
use App\Http\Controllers\Admin\Integrations\Nextcloud\NextcloudController;
use App\Http\Controllers\Admin\Appearance\AppearanceController;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;

Route::get('/', function () {
    return view('welcome');
});

// ---------------------------------------------------------------------------------------------------------------------------------------------------
// AUTHENTICATED ROUTES
//
// Routes for all authenticated users
// ---------------------------------------------------------------------------------------------------------------------------------------------------
Route::middleware('auth')->group(function () {

    // -------------------------------------------------
    // Dashboard routes
    // -------------------------------------------------
    Route::prefix('dashboard')->middleware('verified')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    });

    // -------------------------------------------------
    // Profile routes
    // -------------------------------------------------
    Route::prefix('profile')->middleware('verified')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    // PREFIX ADMIN
    // ---------------------------------------------------------------------------------------------------------------------------------------------------
    Route::prefix('admin')->middleware('verified')->group(function () {

        // -------------------------------------------------
        // Admin
        // -------------------------------------------------
        Route::get('/', [AdminController::class, 'index'])->name('admin.index');

        // --------------------------------------------------------------------------------------------------
        // PREFIX ROLES
        // All routes for roles and permissions
        // --------------------------------------------------------------------------------------------------
        Route::prefix('users')->middleware('verified')->group(function () {

            // -------------------------------------------------
            // List of users
            // -------------------------------------------------
            Route::get('/users', [UserController::class, 'index'])->name('users.index');

            // -------------------------------------------------
            // Create user
            // -------------------------------------------------
            Route::get('/create', [UserController::class, 'create'])->name('users.create');
            Route::post('/users', [UserController::class, 'store'])->name('users.store');

            // -------------------------------------------------
            // Edit user
            // -------------------------------------------------
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('users.update');

            // -------------------------------------------------
            // delete user
            // -------------------------------------------------
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        });

        // --------------------------------------------------------------------------------------------------
        // PREFIX ROLES & PERMISSIONS
        // All routes for roles and permissions
        // --------------------------------------------------------------------------------------------------
        Route::prefix('roles')->middleware('verified')->group(function () {

            // -------------------------------------------------
            // Roles
            // -------------------------------------------------
            Route::resource('/roles', RoleController::class);
            Route::get('/create', [RoleController::class, 'createPermission'])->name('permissions.create');
            Route::post('/store', [RoleController::class, 'storePermission'])->name('permissions.store');

            // -------------------------------------------------
            // Permissions
            // -------------------------------------------------
            Route::get('/{permission}/edit', [RoleController::class, 'editPermission'])->name('permissions.edit');
            Route::put('/{permission}', [RoleController::class, 'updatePermission'])->name('permissions.update');
            Route::delete('/{permission}', [RoleController::class, 'destroyPermission'])->name('permissions.destroy');
        });

        // --------------------------------------------------------------------------------------------------
        // PREFIX CONFIGURATIONS
        // All routes for configurations
        // --------------------------------------------------------------------------------------------------
        Route::prefix('configurations')->middleware('verified')->group(function () {


            Route::get('/', [ConfigurationsController::class, 'index'])->name('dashboard');

            // --------------------------------------------------------------------------------------------------
            // PREFIX MENUS
            // All routes for menus
            // --------------------------------------------------------------------------------------------------
            Route::prefix('menu')->middleware('verified')->group(function () {

                // -------------------------------------------------
                // Menu Configurations
                // -------------------------------------------------
                Route::get('/', [MenuConfigurationController::class, 'index'])->name('menu.configurations');

                // -------------------------------------------------
                // Create menu
                // -------------------------------------------------
                Route::post('/create', [MenuConfigurationController::class, 'store'])->name('menu.create');

                // -------------------------------------------------
                // Route for listing and managing menu items for a specific menu
                // -------------------------------------------------
                Route::get('/{menu}/items', [MenuConfigurationController::class, 'show'])->name('menu.items');

                // -------------------------------------------------
                // Route for adding/editing menu items
                // -------------------------------------------------
                Route::post('/{menu}/items/create', [MenuConfigurationController::class, 'storeItem'])->name('menu.items.create');
                Route::get('/{menu}/items/{item}/edit', [MenuConfigurationController::class, 'editItem'])->name('menu.items.edit');
                Route::post('/{menu}/items/{item}/update', [MenuConfigurationController::class, 'updateItem'])->name('menu.items.update');
            });

            // --------------------------------------------------------------------------------------------------
            // PREFIX EMAIL
            // All routes for email accounts
            // --------------------------------------------------------------------------------------------------
            Route::prefix('email')->middleware('verified')->group(function () {

                // -------------------------------------------------
                // Email Accounts, list, create, edit, delete
                // -------------------------------------------------
                Route::resource('/email_accounts', EmailAccountController::class);
            });

        });

        // --------------------------------------------------------------------------------------------------
        // PREFIX APPEARANCE
        // All routes for appearance
        // --------------------------------------------------------------------------------------------------
        Route::prefix('appearance')->middleware('verified')->group(function () {

            // -------------------------------------------------
            // Appearance
            // -------------------------------------------------
            Route::get('/', [AppearanceController::class, 'index'])->name('appearance.index');
        });

        // --------------------------------------------------------------------------------------------------
        // PREFIX INTEGRATIONS
        // All routes for integrations
        // --------------------------------------------------------------------------------------------------
        Route::prefix('integration')->middleware('verified')->group(function () {

            // -------------------------------------------------
            // Integrations
            // -------------------------------------------------
            Route::get('/', [IntegrationsController::class, 'index'])->name('admin.integrations');

            // ---------------------------------------------------------------------------------------------------------------------------------------------------
            // ROUTES NEXTCLOUD INTRGRATIONS
            // Routes for Nextcloud integrations
            // ---------------------------------------------------------------------------------------------------------------------------------------------------
            Route::prefix('nextcloud')->group(function () {

                // -------------------------------------------------
                // Next Cloud Intregration settings
                // -------------------------------------------------
                Route::get('/', [NextcloudController::class, 'showSettings'])->name('nextcloud.settings');
                Route::post('/toggle', [NextcloudController::class, 'toggleNextcloudIntegration'])->name('nextcloud.toggle');

            });
        });

    });
});


// ---------------------------------------------------------------------------------------------------------------------------------------------------
// ROUTES NEXTCLOUD LOGIN AND CONNECT
//
// Routes for Nextcloud Login and Connect
// ---------------------------------------------------------------------------------------------------------------------------------------------------

// -------------------------------------------------
// NextCloud Connect and callback routes
// -------------------------------------------------
Route::get('auth/nextcloud', [NextcloudController::class, 'redirectToNextcloud'])->name('nextcloud.connect');
Route::get('auth/nextcloud/callback', [NextcloudController::class, 'handleNextcloudCallback'])->name('nextcloud.callback');

// -------------------------------------------------
// NextCloud login routes
// -------------------------------------------------
Route::get('auth/nextcloud', [NextcloudController::class, 'redirectToNextcloud'])->name('login.nextcloud');
Route::get('auth/nextcloud/callback', [NextcloudController::class, 'handleNextcloudCallback'])->name('nextcloud.callback');

// -------------------------------------------------
// Require the auth routes
// -------------------------------------------------
require __DIR__.'/auth.php';
