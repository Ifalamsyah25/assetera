<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'permission' => \App\Http\Middleware\EnsurePermission::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $renderJson = static function (Request $request): bool {
            return $request->is('api/*') || $request->expectsJson();
        };

        $exceptions->render(function (AuthenticationException $e, Request $request) use ($renderJson) {
            if (! $renderJson($request)) {
                return null;
            }

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.',
                'error_code' => 'UNAUTHENTICATED',
                'data' => null,
            ], 401);
        });

        $exceptions->render(function (AuthorizationException $e, Request $request) use ($renderJson) {
            if (! $renderJson($request)) {
                return null;
            }

            return response()->json([
                'success' => false,
                'message' => 'Forbidden.',
                'error_code' => 'FORBIDDEN',
                'data' => null,
            ], 403);
        });

        $exceptions->render(function (ValidationException $e, Request $request) use ($renderJson) {
            if (! $renderJson($request)) {
                return null;
            }

            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'error_code' => 'VALIDATION_ERROR',
                'errors' => $e->errors(),
                'data' => null,
            ], 422);
        });

        $exceptions->render(function (ModelNotFoundException $e, Request $request) use ($renderJson) {
            if (! $renderJson($request)) {
                return null;
            }

            return response()->json([
                'success' => false,
                'message' => 'Resource not found.',
                'error_code' => 'NOT_FOUND',
                'data' => null,
            ], 404);
        });

        $exceptions->render(function (Throwable $e, Request $request) use ($renderJson) {
            if (! $renderJson($request)) {
                return null;
            }

            $status = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

            return response()->json([
                'success' => false,
                'message' => $status >= 500 ? 'Internal server error.' : ($e->getMessage() ?: 'Request failed.'),
                'error_code' => $status >= 500 ? 'INTERNAL_SERVER_ERROR' : 'REQUEST_FAILED',
                'data' => null,
            ], $status);
        });
    })->create();
