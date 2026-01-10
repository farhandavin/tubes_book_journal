<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

/*
|--------------------------------------------------------------------------
| Vercel Storage Configuration
|--------------------------------------------------------------------------
|
| Force the storage path to /tmp in Vercel's serverless environment
| because the default project directory is read-only.
|
*/
if (isset($_ENV['VERCEL'])) {
    $app->useStoragePath('/tmp');
}

return $app;
