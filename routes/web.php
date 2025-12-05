<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\AdminTeamController;
use App\Http\Controllers\AdminEvaluationController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\RegistroController;
use App\Http\Controllers\ParticipantTeamController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Team;
use App\Http\Controllers\SubmissionController;

// =======================
// PÁGINA PRINCIPAL
// =======================
Route::get('/', function () {
    return view('pagPrincipal.pagPrincipal');
})->name('public.home');

Route::get('/pag-principal', function () {
    return view('pagPrincipal.pagPrincipal');
})->name('pagPrincipal');

// Ruta de logout
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect()->route('public.home')
        ->with('logout_success', 'Has cerrado sesión correctamente.');
})->name('logout');
Route::post('/panel/teams/join', [AdminTeamController::class, 'join'])
    ->name('panel.teams.join')
    ->middleware('auth');


// =======================
// LOGIN ÚNICO (USUARIOS + ADMIN)
// =======================
Route::match(['get', 'post'], '/login', function (Request $request) {
    // Si ya está logueado y entra a /login, lo mandamos a su panel
    if ($request->isMethod('get') && Auth::check()) {
        $user = Auth::user();
        return $user->is_admin
            ? redirect()->route('admin.dashboard')
            : redirect()->route('panel.participante');
    }

    if ($request->isMethod('post')) {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            // ADMIN -> dashboard admin
            if ($user->is_admin) {
                return redirect()->route('admin.dashboard');
            }

            // USUARIO NORMAL -> panel participante
            return redirect()->route('panel.participante');
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son válidas.',
        ])->withInput($request->only('email'));
    }

    // GET: mostrar formulario de login (segunda pantalla)
    return view('pagPrincipal.loginPrin');
})->name('login');

// =======================
// REGISTRO PARTICIPANTE
// =======================
Route::get('/registro', function () {
    return view('pagPrincipal.crearCuenta');
})->name('public.register');

Route::post('/registro', [RegistroController::class, 'store'])
    ->name('registro.store');

// =======================
// PANEL PARTICIPANTE
// =======================
Route::get('/panel', function () {
    return view('pagPrincipal.panelParticipante');
})->name('panel.participante');

// Mi equipo
Route::get('/panel/mi-equipo', [ParticipantTeamController::class, 'miEquipo'])
    ->name('panel.mi-equipo')
    ->middleware('auth');

// Lista de equipos (usando controlador)
Route::get('/panel/lista-equipo', [ParticipantTeamController::class, 'index'])
    ->name('panel.lista-equipo')
    ->middleware('auth');

// Crear equipo
Route::get('/panel/crear-equipo', [ParticipantTeamController::class, 'create'])
    ->name('panel.teams.create');

Route::post('/panel/crear-equipo', [ParticipantTeamController::class, 'store'])
    ->name('panel.teams.store');

// Unirse a equipo
Route::post('/panel/unirse-equipo', [ParticipantTeamController::class, 'join'])
    ->name('panel.teams.join');

// Eventos
Route::get('/eventos', function () {
    return view('pagPrincipal.eventos');
})->name('panel.eventos');

// Perfil
Route::get('/perfil', function () {
    return view('pagPrincipal.perfil');
})->name('panel.perfil');

// Cambiar contraseña
Route::get('/cambiar-contrasena', function () {
    return view('pagPrincipal.cambiarContrasena');
})->name('panel.cambiarContrasena');

Route::post('/cambiar-contrasena', [App\Http\Controllers\PasswordController::class, 'update'])
    ->name('password.update');

// Route to the page that displays roles and participants
// Ruta para mostrar los roles del participante
Route::get('/roles', function () {
    return view('pagPrincipal.rolesParticipants');
})->name('roles');

// Submisión del proyecto
Route::get('/submision-proyecto', [SubmissionController::class, 'show'])
    ->name('panel.submission');

Route::post('/submision-proyecto', [SubmissionController::class, 'update'])
    ->name('panel.submission.update');

// Gestión de repositorios de la submisión
Route::get('/submision-proyecto/repositorios', [SubmissionController::class, 'repositories'])
    ->name('panel.submission.repositories');



// =======================
// LOGOUT GENERAL
// =======================
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('public.home')
        ->with('logout_success', 'Has cerrado sesión correctamente.');
})->name('logout');

// =======================
// RUTAS ADMIN (PROTEGIDAS)
// =======================
Route::middleware(['auth', 'is_admin'])->group(function () {
    // DASHBOARD
    Route::get('/admin', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::post('/admin/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    })->name('admin.logout');

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

    // EVALUACIONES
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
