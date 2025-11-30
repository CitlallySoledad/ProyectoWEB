<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\AdminTeamController;
use App\Http\Controllers\AdminEvaluationController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\RegistroController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

// Página principal
Route::get('/', function () {
    return view('pagPrincipal.pagPrincipal');
})->name('public.home');

// Login de usuario normal
// GET  -> muestra el formulario
// POST -> procesa el login (por ahora solo redirige al panel del participante)
Route::match(['get', 'post'], '/login', function (Request $request) {
    if ($request->isMethod('post')) {
        // 🔐 Aquí más adelante puedes validar usuario/contraseña de verdad.
        // Por ahora solo simulamos un login correcto y mandamos al panel.
        return redirect()->route('panel.participante');
    }

    // Si es GET, mostrar el formulario de login
    return view('pagPrincipal.loginPrin');
})->name('public.login');

// Registro usuario normal (solo vista)
Route::get('/registro', function () {
    return view('pagPrincipal.crearCuenta');
})->name('public.register');

// Guardar registro (POST)
Route::post('/registro', [RegistroController::class, 'store'])
    ->name('registro.store');

// Panel del participante (el diseño nuevo)
Route::get('/panel', function () {
    return view('pagPrincipal.panelParticipante');
})->name('panel.participante');

// Panel de Lista de equipos
Route::get('/panel/lista-equipo', function () {
    return view('pagPrincipal.listaEquipo');
})->name('panel.lista-equipo');


// LOGIN ADMIN
Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])
    ->name('admin.login');

Route::post('/admin/login', [AdminAuthController::class, 'login'])
    ->name('admin.login.post');


// 🔒 RUTAS PROTEGIDAS (auth + is_admin)
Route::middleware(['auth', 'is_admin'])->group(function () {

    // DASHBOARD
    Route::get('/admin', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::post('/admin/logout', [AdminAuthController::class, 'logout'])
        ->name('admin.logout');

    // EQUIPOS (CRUD)
    Route::get('/admin/equipos', [AdminTeamController::class, 'index'])
        ->name('admin.teams.index');

    Route::get('/admin/equipos/crear', [AdminTeamController::class, 'create'])
        ->name('admin.teams.create');
    Route::post('/admin/equipos', [AdminTeamController::class, 'store'])
        ->name('admin.teams.store');

    Route::get('/admin/equipos/{team}/editar', [AdminTeamController::class, 'edit'])
        ->name('admin.teams.edit');

    Route::put('/admin/equipos/{team}', [AdminTeamController::class, 'update'])
        ->name('admin.teams.update');

    Route::delete('/admin/equipos/{team}', [AdminTeamController::class, 'destroy'])
        ->name('admin.teams.destroy');

    // EVENTOS (CRUD)
    Route::get('/admin/eventos', [AdminEventController::class, 'index'])
        ->name('admin.events.index');

    Route::get('/admin/eventos/crear', [AdminEventController::class, 'create'])
        ->name('admin.events.create');
    Route::post('/admin/eventos', [AdminEventController::class, 'store'])
        ->name('admin.events.store');

    Route::get('/admin/eventos/{event}/editar', [AdminEventController::class, 'edit'])
        ->name('admin.events.edit');

    Route::put('/admin/eventos/{event}', [AdminEventController::class, 'update'])
        ->name('admin.events.update');

    Route::delete('/admin/eventos/{event}', [AdminEventController::class, 'destroy'])
        ->name('admin.events.destroy');

    // PANEL DE EVALUACIONES
    Route::get('/admin/evaluaciones', [AdminEvaluationController::class, 'index'])
        ->name('admin.evaluations.index');

    Route::get('/admin/evaluaciones/{project}', [AdminEvaluationController::class, 'show'])
        ->name('admin.evaluations.show');

    Route::post('/admin/evaluaciones/{project}', [AdminEvaluationController::class, 'store'])
        ->name('admin.evaluations.store');

    Route::get('/admin/evaluaciones/{evaluation}/juzgar', [AdminEvaluationController::class, 'judgement'])
        ->name('admin.evaluations.judgement');

    Route::post('/admin/evaluaciones/{evaluation}/juzgar', [AdminEvaluationController::class, 'saveJudgement'])
        ->name('admin.evaluations.judgement.store');

    // USUARIOS ADMIN
    Route::get('/admin/usuarios', [AdminUserController::class, 'index'])
        ->name('admin.users.index');

    Route::get('/admin/usuarios/crear', [AdminUserController::class, 'create'])
        ->name('admin.users.create');

    Route::post('/admin/usuarios', [AdminUserController::class, 'store'])
        ->name('admin.users.store');
});
