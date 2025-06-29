<?php
return [
    'namespace' => 'Modules',
    'stubs' => [
        'enabled' => false,
        'path' => base_path('vendor/nwidart/laravel-modules/src/Commands/stubs'),
        'files' => [
            'routes/web' => 'Routes/web.php',
            'routes/api' => 'Routes/api.php',
            'views/index' => 'Resources/views/index.blade.php',
            'views/master' => 'Resources/views/layouts/master.blade.php',
            'scaffold/config' => 'Config/config.php',
            'composer' => 'composer.json',
            'assets/js/app' => 'Resources/assets/js/app.js',
            'assets/sass/app' => 'Resources/assets/sass/app.scss',
            'webpack' => 'webpack.mix.js',
            'package' => 'package.json',
        ],
        'replacements' => [
            'LOWER_NAME' => 'lowerName',
            'STUDLY_NAME' => 'studlyName',
            'MODULE_NAMESPACE' => 'moduleNamespace',
            'CONTROLLER_NAMESPACE' => 'controllerNamespace',
            'VIEW_PREFIX' => 'viewPrefix',
            'ROUTE_PREFIX' => 'routePrefix',
            'ROUTE_NAMED_PREFIX' => 'routeNamedPrefix',
        ],
        'git-ignore' => [
            '#' => '',
        ],
    ],
    'paths' => [
        'modules' => base_path('modules'),
        'assets' => public_path('modules'),
        'migration' => base_path('database/migrations'),
        'generator' => [
            'controller' => ['path' => 'Http/Controllers', 'generate' => true],
            'middleware' => ['path' => 'Http/Middleware', 'generate' => false],
            'command' => ['path' => 'Console', 'generate' => false],
            'filter' => ['path' => 'Http/Middleware', 'generate' => false],
            'request' => ['path' => 'Http/Requests', 'generate' => true],
            'provider' => ['path' => 'Providers', 'generate' => true],
            'assets' => ['path' => 'Resources/assets', 'generate' => false],
            'lang' => ['path' => 'Resources/lang', 'generate' => false],
            'views' => ['path' => 'Resources/views', 'generate' => false],
            'test' => ['path' => 'Tests/Unit', 'generate' => true],
            'model' => ['path' => 'Models', 'generate' => true],
            'repository' => ['path' => 'Repositories', 'generate' => false],
            'event' => ['path' => 'Events', 'generate' => false],
            'listener' => ['path' => 'Listeners', 'generate' => false],
            'policies' => ['path' => 'Policies', 'generate' => false],
            'resource' => ['path' => 'Http/Resources', 'generate' => true],
            'migration' => ['path' => 'Database/Migrations', 'generate' => true],
            'seeder' => ['path' => 'Database/Seeders', 'generate' => true],
            'factory' => ['path' => 'Database/Factories', 'generate' => true],
        ],
    ],
    'scan' => [
        'enabled' => false,
        'paths' => [
            base_path('modules/*'),
        ],
    ],
    'composer' => [
        'vendor' => 'nwidart',
        'author' => [
            'name' => 'Nicolas Widart',
            'email' => 'n.widart@gmail.com',
        ],
    ],
    'cache' => [
        'enabled' => false,
        'key' => 'laravel-modules',
        'lifetime' => 60,
    ],
    'register' => [
        'translations' => true,
        'files' => 'register.php',
    ],
    'activators' => [
        'file' => [
            'class' => 'Nwidart\Modules\Activators\FileActivator',
            'paths' => [
                'modules' => 'modules_statuses.json',
            ],
        ],
    ],
];
