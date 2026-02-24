<?php

use App\Service\HelperResponse;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (Throwable $exception, Request $request) {
            if (! $request->wantsJson() && ! $request->is('api/*')) {
                return null; // let Laravel handle web exceptions normally
            }

            return match (true) {
                $exception instanceof AuthenticationException => HelperResponse::error('Unauthenticated', 401),
                $exception instanceof AuthorizationException  => HelperResponse::error('Forbidden', 403),
                $exception instanceof ModelNotFoundException  => HelperResponse::error('Not Found', 404),
                default                                       => HelperResponse::error($exception->getMessage(), 500),
            };
        });
    })->create();
