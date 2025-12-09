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
use App\Http\Controllers\PanelPasswordController;


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
if (!function_exists('getRedirectRouteByRole')) {
    function getRedirectRouteByRole($user)
    {
        if ($user->hasRole('admin')) return 'admin.dashboard';
        if ($user->hasRole('judge')) return 'judge.projects.index';
        return 'panel.participante';
    }
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
// RECUPERACIÓN DE CONTRASEÑA
// ==========================================================
use App\Http\Controllers\PasswordResetController;

Route::get('/forgot-password', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/reset-password', [PasswordResetController::class, 'reset'])->name('password.update');

// ==========================================================
// INVITACIONES DE EQUIPO (sin autenticación requerida)
// ==========================================================
Route::get('/invitacion/{token}', [ParticipantTeamController::class, 'acceptInvitation'])->name('team-invitation.accept');

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
    
    // Rutas para solicitudes de unirse a equipos
    Route::post('/panel/solicitudes/{requestId}/aceptar', [ParticipantTeamController::class, 'acceptJoinRequest'])->name('panel.requests.accept');
    Route::post('/panel/solicitudes/{requestId}/rechazar', [ParticipantTeamController::class, 'rejectJoinRequest'])->name('panel.requests.reject');
    
    // Ruta para eliminar miembros del equipo
    Route::post('/panel/equipo/{teamId}/miembro/{userId}/eliminar', [ParticipantTeamController::class, 'removeMember'])->name('panel.members.remove');

    // Rutas para invitaciones de líder
    Route::post('/panel/equipo/invitar', [ParticipantTeamController::class, 'sendInvitation'])->name('panel.invitations.send');

    Route::get('/eventos', fn() => view('pagPrincipal.eventos'))->name('panel.eventos');
    
    Route::get('/lista-eventos', function() {
        // Mostrar eventos disponibles (publicados y activos) ordenados por fecha
        $events = \App\Models\Event::available()
            ->with('teams')
            ->orderBy('start_date', 'asc')
            ->get();
        return view('pagPrincipal.listaEventos', compact('events'));
    })->name('panel.lista-eventos');

    Route::get('/evento/{event}/equipos', function(\App\Models\Event $event) {
        // Mostrar equipos inscritos en un evento específico
        $teams = $event->teams()->with(['leader', 'members'])->get();
        return view('pagPrincipal.eventTeams', compact('event', 'teams'));
    })->name('panel.event.teams');

    Route::get('/api/user/leader-teams', [App\Http\Controllers\EventParticipantController::class, 'getUserLeaderTeams'])->name('api.user.leader-teams');
    Route::get('/api/user/eligible-teams', [SubmissionController::class, 'getEligibleTeams'])->name('api.user.eligible-teams');
    Route::post('/eventos/{event}/join', [App\Http\Controllers\EventParticipantController::class, 'joinEvent'])->name('panel.events.join');
    Route::delete('/eventos/{event}/leave/{team}', [App\Http\Controllers\EventParticipantController::class, 'leaveEvent'])->name('panel.events.leave');

    Route::get('/panel/perfil', [PanelProfileController::class, 'show'])->name('panel.perfil');
    Route::patch('/panel/perfil/datos', [PanelProfileController::class, 'updateDatos'])->name('panel.perfil.updateDatos');

    Route::get('/cambiar-contrasena', fn() => view('pagPrincipal.cambiarContrasena'))->name('panel.cambiarContrasena');
    Route::post('/cambiar-contrasena', [App\Http\Controllers\PasswordController::class, 'update'])->name('password.update');

    Route::get('/roles', fn() => view('pagPrincipal.rolesParticipants'))->name('roles');

    Route::get('/submision-proyecto', [SubmissionController::class, 'show'])->name('panel.submission');
    Route::post('/submision-proyecto', [SubmissionController::class, 'update'])->name('panel.submission.update');
    Route::post('/submision-proyecto/upload-pdf', [SubmissionController::class, 'uploadPdf'])->name('panel.submission.upload-pdf');
    Route::delete('/submision-proyecto/delete-pdf/{document}', [SubmissionController::class, 'deletePdf'])->name('panel.submission.delete-pdf');
    Route::post('/submision-proyecto/confirm', [SubmissionController::class, 'confirmSubmission'])->name('panel.submission.confirm');
    Route::get('/submision-proyecto/repositorios', [SubmissionController::class, 'repositories'])->name('panel.submission.repositories');

    // Mostrar formulario de cambiar contraseña
    Route::get('/panel/cambiar-contrasena', [PanelPasswordController::class, 'show'])
        ->name('panel.cambiarContrasena');

    // Procesar el cambio de contraseña
    Route::post('/panel/cambiar-contrasena', [PanelPasswordController::class, 'update'])
        ->name('panel.cambiarContrasena.update');
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
    Route::post('/equipo/{team}/join', [ParticipantTeamController::class, 'join'])
    ->name('panel.equipo.join');

    // Eventos
    Route::get('eventos', [AdminEventController::class, 'index'])->name('events.index');
    Route::get('eventos/crear', [AdminEventController::class, 'create'])->name('events.create');
    Route::post('eventos', [AdminEventController::class, 'store'])->name('events.store');
    Route::get('eventos/{event}/editar', [AdminEventController::class, 'edit'])->name('events.edit');
    Route::get('eventos/{event}/resultados', [AdminEventController::class, 'showResults'])->name('events.results');
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

    // Rúbricas (Admin tiene control total)
    Route::get('rubricas', [App\Http\Controllers\Admin\RubricController::class, 'index'])->name('rubrics.index');
    Route::get('rubricas/crear', [App\Http\Controllers\Admin\RubricController::class, 'create'])->name('rubrics.create');
    Route::post('rubricas', [App\Http\Controllers\Admin\RubricController::class, 'store'])->name('rubrics.store');
    Route::get('rubricas/{rubric}/editar', [App\Http\Controllers\Admin\RubricController::class, 'edit'])->name('rubrics.edit');
    Route::put('rubricas/{rubric}', [App\Http\Controllers\Admin\RubricController::class, 'update'])->name('rubrics.update');
    Route::delete('rubricas/{rubric}', [App\Http\Controllers\Admin\RubricController::class, 'destroy'])->name('rubrics.destroy');

    // Criterios de rúbricas
    Route::post('rubricas/{rubric}/criterios', [App\Http\Controllers\Admin\RubricController::class, 'storeCriterion'])->name('rubrics.criteria.store');
    Route::post('rubricas/{rubric}/criterios/guardar', [App\Http\Controllers\Admin\RubricController::class, 'bulkUpdate'])->name('rubrics.criteria.bulkUpdate');
    Route::put('rubricas/criterios/{criterion}', [App\Http\Controllers\Admin\RubricController::class, 'updateCriterion'])->name('rubrics.criteria.update');
    Route::delete('rubricas/criterios/{criterion}', [App\Http\Controllers\Admin\RubricController::class, 'destroyCriterion'])->name('rubrics.criteria.destroy');

    // 👉 NUEVAS RUTAS
    Route::get('eventos/{event}/constancias', [AdminEventController::class, 'generateCertificates'])
        ->name('events.certificates');

    Route::get('eventos/reporte/excel', [AdminEventController::class, 'exportExcel'])
        ->name('events.export_excel');

    Route::put('eventos/{event}', [AdminEventController::class, 'update'])->name('events.update');
    Route::delete('eventos/{event}', [AdminEventController::class, 'destroy'])->name('events.destroy');

    // ...
});

// ==========================================================
// RUTAS JUEZ (role:judge)
// ==========================================================
Route::middleware(['auth', 'role:judge'])->prefix('juez')->name('judge.')->group(function () {
    Route::get('/proyectos', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/proyectos/{project}', [EvaluationController::class, 'show'])->name('evaluations.show');
    Route::post('/proyectos/{project}', [EvaluationController::class, 'store'])->name('evaluations.store');
    Route::get('/evaluaciones', [EvaluationController::class, 'index'])->name('evaluations.index');
    Route::get('/evaluaciones/{evaluation}/ver', [EvaluationController::class, 'viewCompleted'])->name('evaluations.view');
    Route::get('/evaluaciones/{evaluation}/pdf', [EvaluationController::class, 'exportPdf'])->name('evaluations.export-pdf');
    
    // Rúbricas (solo lectura para jueces)
    Route::get('/rubricas', [RubricController::class, 'index'])->name('rubrics.index');
}); 
