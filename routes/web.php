<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Controllers\RegistroController;
use App\Http\Controllers\ParticipantTeamController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\PanelProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminEventController;
use App\Http\Controllers\AdminTeamController;
use App\Http\Controllers\AdminEvaluationController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\Judge\ProjectController;
use App\Http\Controllers\Judge\RubricController;
use App\Http\Controllers\Judge\EvaluationController;

// ==========================================================
// PÁGINA PRINCIPAL
// ==========================================================
Route::get('/', fn() => view('pagPrincipal.pagPrincipal'))->name('public.home');
Route::get('/pag-principal', fn() => view('pagPrincipal.pagPrincipal'))->name('pagPrincipal');

// ==========================================================
// LOGIN UNIFICADO
// ==========================================================
Route::match(['get', 'post'], '/login', function (Request $request) {
    if ($request->isMethod('get') && Auth::check()) {
        return redirect()->route(getRedirectRouteByRole(Auth::user()));
    }

    if ($request->isMethod('post')) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->route(getRedirectRouteByRole(Auth::user()));
        }

        return back()->withErrors([
            'email' => 'Las credenciales no son válidas.',
        ])->withInput($request->only('email'));
    }

    return view('pagPrincipal.loginPrin');
})->name('login');

// Función auxiliar para redirigir según rol
function getRedirectRouteByRole($user)
{
    if ($user->hasRole('admin')) return 'admin.dashboard';
    if ($user->hasRole('judge')) return 'judge.projects.index';
    return 'panel.participante';
}

// ==========================================================
// LOGOUT GENERAL
// ==========================================================
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('public.home')->with('logout_success', 'Has cerrado sesión correctamente.');
})->name('logout');

// ==========================================================
// REGISTRO
// ==========================================================
Route::get('/registro', fn() => view('pagPrincipal.crearCuenta'))->name('public.register');
Route::post('/registro', [RegistroController::class, 'store'])->name('registro.store');

// ==========================================================
// PANEL PARTICIPANTE (role:student)
// ==========================================================
Route::middleware(['auth', 'role:student'])->group(function () {
    Route::get('/panel', fn() => view('pagPrincipal.panelParticipante'))->name('panel.participante');

    Route::get('/panel/mi-equipo', [ParticipantTeamController::class, 'miEquipo'])->name('panel.mi-equipo');
    Route::get('/panel/lista-equipo', [ParticipantTeamController::class, 'index'])->name('panel.lista-equipo');
    Route::get('/panel/crear-equipo', [ParticipantTeamController::class, 'create'])->name('panel.teams.create');
    Route::post('/panel/crear-equipo', [ParticipantTeamController::class, 'store'])->name('panel.teams.store');
    Route::post('/panel/unirse-equipo', [ParticipantTeamController::class, 'join'])->name('panel.teams.join');

    Route::get('/eventos', fn() => view('pagPrincipal.eventos'))->name('panel.eventos');

    Route::get('/panel/perfil', [PanelProfileController::class, 'show'])->name('panel.perfil');
    Route::patch('/panel/perfil/datos', [PanelProfileController::class, 'updateDatos'])->name('panel.perfil.updateDatos');

    Route::get('/cambiar-contrasena', fn() => view('pagPrincipal.cambiarContrasena'))->name('panel.cambiarContrasena');
    Route::post('/cambiar-contrasena', [App\Http\Controllers\PasswordController::class, 'update'])->name('password.update');

    Route::get('/roles', fn() => view('pagPrincipal.rolesParticipants'))->name('roles');

    Route::get('/submision-proyecto', [SubmissionController::class, 'show'])->name('panel.submission');
    Route::post('/submision-proyecto', [SubmissionController::class, 'update'])->name('panel.submission.update');
    Route::get('/submision-proyecto/repositorios', [SubmissionController::class, 'repositories'])->name('panel.submission.repositories');
});

// ==========================================================
// RUTAS ADMIN (role:admin)
// ==========================================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');

    // Equipos
    Route::get('equipos', [AdminTeamController::class, 'index'])->name('teams.index');
    Route::get('equipos/crear', [AdminTeamController::class, 'create'])->name('teams.create');
    Route::post('equipos', [AdminTeamController::class, 'store'])->name('teams.store');
    Route::get('equipos/{team}/editar', [AdminTeamController::class, 'edit'])->name('teams.edit');
    Route::put('equipos/{team}', [AdminTeamController::class, 'update'])->name('teams.update');
    Route::delete('equipos/{team}', [AdminTeamController::class, 'destroy'])->name('teams.destroy');

    // Eventos
    Route::get('eventos', [AdminEventController::class, 'index'])->name('events.index');
    Route::get('eventos/crear', [AdminEventController::class, 'create'])->name('events.create');
    Route::post('eventos', [AdminEventController::class, 'store'])->name('events.store');
    Route::get('eventos/{event}/editar', [AdminEventController::class, 'edit'])->name('events.edit');
    Route::put('eventos/{event}', [AdminEventController::class, 'update'])->name('events.update');
    Route::delete('eventos/{event}', [AdminEventController::class, 'destroy'])->name('events.destroy');

    // Evaluaciones
    Route::get('evaluaciones', [AdminEvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('evaluaciones/{project}', [AdminEvaluationController::class, 'show'])->name('evaluations.show');
    Route::post('evaluaciones/{project}', [AdminEvaluationController::class, 'store'])->name('evaluations.store');
    Route::get('evaluaciones/{evaluation}/juzgar', [AdminEvaluationController::class, 'judgement'])->name('evaluations.judgement');
    Route::post('evaluaciones/{evaluation}/juzgar', [AdminEvaluationController::class, 'saveJudgement'])->name('evaluations.judgement.store');
    Route::get('evaluaciones/{evaluation}/detalle', [AdminEvaluationController::class, 'detail'])->name('evaluations.detail');
    Route::get('evaluaciones/{evaluation}/editar', [AdminEvaluationController::class, 'edit'])->name('evaluations.edit');
    Route::put('evaluaciones/{evaluation}', [AdminEvaluationController::class, 'update'])->name('evaluations.update');
    Route::delete('evaluaciones/{evaluation}', [AdminEvaluationController::class, 'destroy'])->name('evaluations.destroy');

    // Proyectos a evaluar
    Route::get('proyectos-evaluar', [AdminEvaluationController::class, 'projectsToEvaluate'])->name('evaluations.projects_list');
    Route::get('proyectos-evaluar/crear', [AdminEvaluationController::class, 'createProject'])->name('projects.create');
    Route::post('proyectos-evaluar', [AdminEvaluationController::class, 'storeProject'])->name('projects.store');

    // Usuarios
    Route::get('usuarios', [AdminUserController::class, 'index'])->name('users.index');
    Route::get('usuarios/crear', [AdminUserController::class, 'create'])->name('users.create');
    Route::post('usuarios', [AdminUserController::class, 'store'])->name('users.store');
});

// ==========================================================
// RUTAS JUEZ (role:judge)
// ==========================================================
Route::middleware(['auth', 'role:judge'])->prefix('juez')->name('judge.')->group(function () {
    Route::get('/proyectos', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/proyectos/{project}', [EvaluationController::class, 'show'])->name('evaluations.show');
    Route::post('/proyectos/{project}', [EvaluationController::class, 'store'])->name('evaluations.store');
    Route::get('/evaluaciones', [EvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('/evaluaciones/{evaluation}/pdf', [EvaluationController::class, 'exportPdf'])->name('evaluations.export-pdf');
    Route::get('/rubricas', [RubricController::class, 'index'])->name('rubrics.index');
    Route::get('/rubricas/crear', [RubricController::class, 'create'])->name('rubrics.create');
    Route::post('/rubricas', [RubricController::class, 'store'])->name('rubrics.store');
    Route::get('/rubricas/{rubric}/editar', [RubricController::class, 'edit'])->name('rubrics.edit');
    Route::put('/rubricas/{rubric}', [RubricController::class, 'update'])->name('rubrics.update');
    Route::delete('/rubricas/{rubric}', [RubricController::class, 'destroy'])->name('rubrics.destroy');

    // Rubric criteria
    Route::post('/rubricas/{rubric}/criterios', [RubricController::class, 'storeCriterion'])->name('rubrics.criteria.store');
    Route::post('/rubricas/{rubric}/criterios/guardar', [RubricController::class, 'bulkUpdate'])->name('rubrics.criteria.bulkUpdate');
    Route::post('/rubricas/{rubric}/aplicar', [RubricController::class, 'apply'])->name('rubrics.apply');
    Route::get('/rubricas/criterios/{criterion}/editar', [RubricController::class, 'editCriterion'])->name('rubrics.criteria.edit');
    Route::put('/rubricas/criterios/{criterion}', [RubricController::class, 'updateCriterion'])->name('rubrics.criteria.update');
    Route::delete('/rubricas/criterios/{criterion}', [RubricController::class, 'destroyCriterion'])->name('rubrics.criteria.destroy');

}); 
