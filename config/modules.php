return [
    /*
    |--------------------------------------------------------------------------
    | Module Namespace
    |--------------------------------------------------------------------------
    |
    | Default module namespace.
    |
    */
    'namespace' => 'Modules',

    /*
    |--------------------------------------------------------------------------
    | Module Stubs
    |--------------------------------------------------------------------------
    |
    | Define the stubs configuration.
    |
    */
    'stubs' => [
        'enabled' => false,
        'path' => base_path('vendor/nwidart/laravel-modules/src/Commands/stubs'),
        'files' => [
            'routes/web' => 'src/Routes/web.php',
            'routes/api' => 'src/Routes/api.php',
            'views/index' => 'src/Resources/views/index.blade.php',
            'scaffold/config' => 'src/Config/config.php',
            'composer' => 'composer.json',
            'assets/js/app' => 'src/Resources/assets/js/app.js',
            'assets/sass/app' => 'src/Resources/assets/sass/app.scss',
            'webpack' => 'webpack.mix.js',
            'package' => 'package.json',
        ],
        'replacements' => [
            'routes/web' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE', 'CONTROLLER_NAMESPACE'],
            'routes/api' => ['LOWER_NAME', 'STUDLY_NAME', 'MODULE_NAMESPACE'],
            'views/index' => ['LOWER_NAME'],
            'scaffold/config' => ['STUDLY_NAME'],
            'composer' => [
                'LOWER_NAME',
                'STUDLY_NAME',
                'VENDOR',
                'AUTHOR_NAME',
                'AUTHOR_EMAIL',
                'MODULE_NAMESPACE',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Paths
    |--------------------------------------------------------------------------
    |
    | These paths are used throughout the package.
    |
    */
    'paths' => [
        /*
        |--------------------------------------------------------------------------
        | Modules path
        |--------------------------------------------------------------------------
        |
        | This path used for save the generated module. This path also will added
        | automatically to list of scanned folders.
        |
        */
        'modules' => base_path('modules'),

        /*
        |--------------------------------------------------------------------------
        | Modules assets path
        |--------------------------------------------------------------------------
        |
        | Here you may update the module assets path.
        |
        */
        'assets' => public_path('modules'),

        /*
        |--------------------------------------------------------------------------
        | The migrations path
        |--------------------------------------------------------------------------
        |
        | Where you run 'module:publish-migration' command.
        |
        */
        'migration' => base_path('modules/FacebookPostingModule/database/migrations'),

        /*
        |--------------------------------------------------------------------------
        | Generator path
        |--------------------------------------------------------------------------
        |
        | Customise the paths where the folders will be generated.
        |
        */
        'generator' => [
            'config' => 'src/Config',
            'command' => 'src/Console',
            'migration' => 'database/migrations',
            'seeder' => 'database/seeders',
            'factory' => 'database/factories',
            'model' => 'src/Entities',
            'routes' => 'src/Routes',
            'controller' => 'src/Http/Controllers',
            'middleware' => 'src/Http/Middleware',
            'request' => 'src/Http/Requests',
            'provider' => 'src/Providers',
            'assets' => 'src/Resources/assets',
            'lang' => 'src/Resources/lang',
            'views' => 'src/Resources/views',
            'test' => 'Tests',
            'jobs' => 'src/Jobs',
            'emails' => 'src/Emails',
            'notifications' => 'src/Notifications',
            'resource' => 'src/Http/Resources',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Scan Path
    |--------------------------------------------------------------------------
    |
    | Here you may define your own directories to be scanned. The package
    | will scan defined directories for modules.
    |
    */
    'scan' => [
        'enabled' => false,
        'paths' => [
            base_path('modules'),
        ],
    ],
];
