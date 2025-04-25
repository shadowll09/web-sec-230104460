<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Middleware Aliases
    |--------------------------------------------------------------------------
    |
    | Here you may define all of your middleware aliases that will be registered
    | with the application. This allows you to use more readable names in your
    | middleware routes.
    |
    */

    // Role and permission middleware
    'role' => \App\Http\Middleware\CheckRole::class,
    'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
    'role_or_permission' => \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class,
    
    // Rate limiting middleware
    'rate.login' => \App\Http\Middleware\RateLimitLogin::class,
];
