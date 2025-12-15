<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        // si algún día usas API puedes descomentar esta línea:
        // api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Aquí registramos aliases de middleware por nombre
        $middleware->alias([
        'auth'  => \App\Http\Middleware\Authenticate::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,

        // 👉 AQUI REGISTRAMOS TU MIDDLEWARE
        'is_admin' => \App\Http\Middleware\IsAdmin::class,

        // Middleware de Spatie Permission (con namespace completo)
        'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
        'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
        'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,

    ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        
        // Manejar errores de Spatie Permission (UnauthorizedException)
        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, $request) {
            // Si es una petición AJAX/JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => 'Acceso denegado. No cuentas con los permisos necesarios.'
                ], 403);
            }

            // Para peticiones web normales - redirigir al dashboard correspondiente
            $user = auth()->user();
            $route = 'login'; // Default
            
            if ($user) {
                if ($user->hasRole('admin')) {
                    $route = 'admin.dashboard';
                } elseif ($user->hasRole('judge')) {
                    $route = 'judge.projects.index';
                } elseif ($user->hasRole('student')) {
                    $route = 'panel.participante';
                }
            }
            
            return redirect()->route($route)->with('error', 'Acceso denegado. No cuentas con los permisos necesarios para visualizar esta sección.');
        });
        
        // Manejar errores 403 (Forbidden) con mensajes claros
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            if ($e->getStatusCode() === 403) {
                // Si es una petición AJAX/JSON
                if ($request->expectsJson()) {
                    return response()->json([
                        'error' => 'No tienes permisos para acceder a este recurso.'
                    ], 403);
                }

                // Para peticiones web normales - redirigir al dashboard correspondiente
                $user = auth()->user();
                $route = 'login'; // Default
                
                if ($user) {
                    if ($user->hasRole('admin')) {
                        $route = 'admin.dashboard';
                    } elseif ($user->hasRole('judge')) {
                        $route = 'judge.projects.index';
                    } elseif ($user->hasRole('student')) {
                        $route = 'panel.participante';
                    }
                }
                
                return redirect()->route($route)->with('error', 'Acceso denegado. No cuentas con los permisos necesarios para visualizar esta sección.');
            }
        });

        // Manejar errores de autorización (de FormRequests y Policies)
        $exceptions->render(function (\Illuminate\Auth\Access\AuthorizationException $e, $request) {
            // Si es una petición AJAX/JSON
            if ($request->expectsJson()) {
                return response()->json([
                    'error' => $e->getMessage() ?: 'No autorizado.'
                ], 403);
            }

            // Para peticiones web normales - redirigir al dashboard correspondiente
            $user = auth()->user();
            $route = 'login'; // Default
            
            if ($user) {
                if ($user->hasRole('admin')) {
                    $route = 'admin.dashboard';
                } elseif ($user->hasRole('judge')) {
                    $route = 'judge.projects.index';
                } elseif ($user->hasRole('student')) {
                    $route = 'panel.participante';
                }
            }
            
            return redirect()->route($route)->with('error', 
                $e->getMessage() ?: 'Acceso denegado. No cuentas con los permisos necesarios para realizar esta acción.'
            );
        });

        // Manejar errores 404 (página no encontrada)
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Recurso no encontrado.'], 404);
            }

            // Mostrar vista con solo el mensaje de advertencia
            return response()->view('errors.404', [], 404);
        });
    })
    ->create();
