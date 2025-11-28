<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\AdminTeamController;
use App\Http\Controllers\AdminEvaluationController;
use App\Http\Controllers\AdminUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

// LOGIN ADMIN (sin guest para evitar bucles)
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.post');

// Rutas protegidas
Route::middleware('auth')->group(function () {

    // DASHBOARD
    Route::get('/admin', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
        ->name('admin.logout');

    /*
    |--------------------------------------------------------------------------
    | EQUIPOS (CRUD)
    |--------------------------------------------------------------------------
    */

    // Lista
    Route::get('/admin/equipos', [AdminTeamController::class, 'index'])
        ->name('admin.teams.index');

    // Crear
    Route::get('/admin/equipos/crear', [AdminTeamController::class, 'create'])
        ->name('admin.teams.create');
    Route::post('/admin/equipos', [AdminTeamController::class, 'store'])
        ->name('admin.teams.store');

    // Editar
    Route::get('/admin/equipos/{team}/editar', [AdminTeamController::class, 'edit'])
        ->name('admin.teams.edit');

    // Actualizar
    Route::put('/admin/equipos/{team}', [AdminTeamController::class, 'update'])
        ->name('admin.teams.update');

    // Eliminar
    Route::delete('/admin/equipos/{team}', [AdminTeamController::class, 'destroy'])
        ->name('admin.teams.destroy');

    /*
    |--------------------------------------------------------------------------
    | EVENTOS (CRUD)
    |--------------------------------------------------------------------------
    */

    // Lista
    Route::get('/admin/eventos', [AdminEventController::class, 'index'])
        ->name('admin.events.index');

    // Crear
    Route::get('/admin/eventos/crear', [AdminEventController::class, 'create'])
        ->name('admin.events.create');
    Route::post('/admin/eventos', [AdminEventController::class, 'store'])
        ->name('admin.events.store');

    // Editar
    Route::get('/admin/eventos/{event}/editar', [AdminEventController::class, 'edit'])
        ->name('admin.events.edit');

    // Actualizar
    Route::put('/admin/eventos/{event}', [AdminEventController::class, 'update'])
        ->name('admin.events.update');

    // Eliminar
    Route::delete('/admin/eventos/{event}', [AdminEventController::class, 'destroy'])
        ->name('admin.events.destroy');


        // PANEL DE EVALUACIONES
    Route::get('/admin/evaluaciones', [AdminEvaluationController::class, 'index'])
        ->name('admin.evaluations.index');

    // Crear evaluación de un proyecto (por nombre)
    Route::get('/admin/evaluaciones/{project}', [AdminEvaluationController::class, 'show'])
        ->name('admin.evaluations.show');

    Route::post('/admin/evaluaciones/{project}', [AdminEvaluationController::class, 'store'])
        ->name('admin.evaluations.store');

    // JUZGAMIENTO / RESUMEN DE UNA EVALUATION ya guardada (usa el ID)
    Route::get('/admin/evaluaciones/{evaluation}/juzgar', [AdminEvaluationController::class, 'judgement'])
        ->name('admin.evaluations.judgement');

    Route::post('/admin/evaluaciones/{evaluation}/juzgar', [AdminEvaluationController::class, 'saveJudgement'])
        ->name('admin.evaluations.judgement.store');

     // -------------------- USUARIOS --------------------
    Route::get('/admin/usuarios', [AdminUserController::class, 'index'])
        ->name('admin.users.index');

    Route::get('/admin/usuarios/crear', [AdminUserController::class, 'create'])
        ->name('admin.users.create');

    Route::post('/admin/usuarios', [AdminUserController::class, 'store'])
        ->name('admin.users.store');

});




