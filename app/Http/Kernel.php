<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     */
    protected $middleware = [
        // Laravel standard middleware
    ];

    /**
     * The application's route middleware groups.
     */
    protected $middlewareGroups = [
        'web' => [
            // Laravel standard middleware for web
        ],

        'api' => [
            'throttle:api',
            'bindings',
        ],
    ];

    /**
     * The application's route middleware.
     */
    protected $routeMiddleware = [
        // Andre middleware
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class, // Legg til Spatie Role Middleware
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class, // Tillatelse middleware
        'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class, // Alternativt
    ];
}
