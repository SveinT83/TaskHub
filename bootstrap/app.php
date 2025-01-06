<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

    $app = require_once __DIR__ . '/../vendor/autoload.php';

    $app = new Illuminate\Foundation\Application(
        $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
    );

    // Load environment configurations
    Dotenv\Dotenv::createImmutable($app->environmentPath(), $app->environmentFile())->safeLoad();

    // Dynamically register module service providers
    \App\Services\ModuleServiceLoader::register($app, base_path('modules'));

    // Return the application instance
    return $app;
