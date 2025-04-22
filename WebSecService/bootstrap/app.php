<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Facade;

// Ensure files binding is available early
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}

// Register the Composer autoloader
require __DIR__.'/../vendor/autoload.php';

// Include our bootstrap helper
require_once __DIR__.'/../app/Helpers/bootstrap.php';

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
*/

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

// Set Facade application instance
Facade::setFacadeApplication($app);

return $app;
