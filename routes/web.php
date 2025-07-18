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
use App\Http\Controllers\Admin\Configurations\WidgetController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Livewire\Livewire;

Route::get('/', function () {
    return view('welcome');
});

// Debug route for testing auth state
Route::get('/debug-auth', function () {
    $authCheck = Auth::check();
    $userId = Auth::id();
    $user = Auth::user();
    
    return response()->json([
        'auth_check' => $authCheck,
        'user_id' => $userId,
        'user' => $user ? $user->toArray() : null,
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
    ]);
});

Route::get('/debug-translation', function() {
    dd([
        'locale' => App::getLocale(),
        'config_locale' => config('app.locale'), 
        'translation_test' => __('core.ui.edit'),
        'translation_exists' => Lang::has('core.ui.edit'),
        'file_exists' => file_exists(resource_path('lang/en/core.php'))
    ]);
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

            // --------------------------------------------------------------------------------------------------
            // PREFIX WIDGETS
            // All routes for widget CMS
            // --------------------------------------------------------------------------------------------------
            Route::prefix('widgets')->middleware('verified')->group(function () {

                // -------------------------------------------------
                // Widget CMS
                // -------------------------------------------------
                Route::get('/', [WidgetController::class, 'index'])->name('admin.configurations.widgets.index');
                Route::get('/configure', [WidgetController::class, 'configure'])->name('admin.configurations.widgets.configure');
                Route::post('/add', [WidgetController::class, 'addWidget'])->name('admin.configurations.widgets.add');
                Route::post('/position/add', [WidgetController::class, 'addToPosition'])->name('admin.configurations.widgets.position.add');
                Route::delete('/position/{id}', [WidgetController::class, 'removeFromPosition'])->name('admin.configurations.widgets.position.remove');
                Route::put('/position/{id}/settings', [WidgetController::class, 'updateSettings'])->name('admin.configurations.widgets.position.settings');
                Route::post('/position/{id}/toggle', [WidgetController::class, 'togglePosition'])->name('admin.configurations.widgets.position.toggle');
                Route::post('/reorder', [WidgetController::class, 'reorderWidgets'])->name('admin.configurations.widgets.reorder');
                Route::get('/preview/{widget}', [WidgetController::class, 'preview'])->name('admin.configurations.widgets.preview');
                Route::get('/refresh/{widgetPosition}', [WidgetController::class, 'refreshWidget'])->name('admin.configurations.widgets.refresh');
            });

            // --------------------------------------------------------------------------------------------------
            // LANGUE MANAGEMENT
            // Routes for langue and translation management
            // --------------------------------------------------------------------------------------------------
            Route::prefix('langue')->middleware('verified')->group(function () {
                Route::get('/', [App\Http\Controllers\Admin\Configurations\Langue\TranslationController::class, 'index'])->name('admin.translations.index');
                Route::get('/stats', [App\Http\Controllers\Admin\Configurations\Langue\TranslationController::class, 'stats'])->name('admin.translations.stats');
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
            Route::get('/', [IntegrationsController::class, 'index'])->name('admin.integrations.index');
            Route::post('/{id}/activate', [IntegrationsController::class, 'activate'])->name('admin.integrations.activate');
            Route::post('/{id}/deactivate', [IntegrationsController::class, 'deactivate'])->name('admin.integrations.deactivate');

            // ---------------------------------------------------------------------------------------------------------------------------------------------------
            // ROUTES NEXTCLOUD INTRGRATIONS
            // Routes for Nextcloud integrations
            // ---------------------------------------------------------------------------------------------------------------------------------------------------
            Route::prefix('nextcloud')->group(function () {

                // -------------------------------------------------
                // Next Cloud Intregration settings
                // -------------------------------------------------
                Route::get('/', [NextcloudController::class, 'showSettings'])->name('admin.integration.nextcloud');
                Route::post('/toggle', [NextcloudController::class, 'toggleNextcloudIntegration'])->name('nextcloud.toggle');
                Route::post('/update-credentials', [NextcloudController::class, 'updateCredentials'])->name('nextcloud.updateCredentials');

            });

            // ---------------------------------------------------------------------------------------------------------------------------------------------------
            // ROUTES TRIPLETEX INTRGRATIONS  
            // Routes for Tripletex integrations (placeholder)
            // ---------------------------------------------------------------------------------------------------------------------------------------------------
            Route::prefix('tripletex')->group(function () {
                Route::get('/', function() { 
                    return view('admin.integrations.tripletex.show'); 
                })->name('admin.integration.tripletex');
            });
        });

        // --------------------------------------------------------------------------------------------------
        // LEGACY WIDGET ROUTES
        // Redirect old widget routes to new ones
        // --------------------------------------------------------------------------------------------------
        Route::prefix('widgets')->middleware('verified')->group(function () {
            Route::get('/', function() {
                return redirect()->route('admin.configurations.widgets.index');
            })->name('admin.widgets.index');
            
            Route::get('/configure', function(Illuminate\Http\Request $request) {
                return redirect()->route('admin.configurations.widgets.configure', $request->query());
            })->name('admin.widgets.configure');
            
            Route::post('/add', function(Illuminate\Http\Request $request) {
                return redirect()->route('admin.configurations.widgets.add', $request->all());
            })->name('admin.widgets.add');
        });
    });
});


// ---------------------------------------------------------------------------------------------------------------------------------------------------
// ROUTES NEXTCLOUD LOGIN AND CONNECT
//
// Routes for Nextcloud Login and Connect
// ---------------------------------------------------------------------------------------------------------------------------------------------------

// -------------------------------------------------
// NextCloud Connect and callback routes (for admin integration)
// -------------------------------------------------
Route::get('auth/nextcloud', [NextcloudController::class, 'redirectToNextcloud'])->name('nextcloud.connect');
Route::get('auth/nextcloud/callback', [NextcloudController::class, 'handleNextcloudCallback'])->name('nextcloud.callback');

// -------------------------------------------------
// Require the auth routes (these handle login-specific routes)
// -------------------------------------------------
require __DIR__.'/auth.php';
