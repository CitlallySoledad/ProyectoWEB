<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\JoinTeamRequest;
use App\Http\Requests\TeamInvitationRequest;
use App\Models\Team;
use App\Models\TeamJoinRequest;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Mail\TeamInvitationMail;
use App\Mail\TeamJoinRequestMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    // Muestra la lista de equipos en el panel del participante
    public function index()
    {
        // Obtener equipos ordenados y cargar miembros + líder para mostrarlos en la vista
        // Paginación de 10 equipos por página
        $teams = Team::with(['members', 'leader'])->orderBy('created_at', 'desc')->paginate(6);

        return view('pagPrincipal.listaEquipo', compact('teams'));
    }

    public function miEquipo()
    {
        $user = auth()->user();
        // Equipo principal (si usas team_id en users)
        $mainTeam = null;
        if ($user->team_id) {
            $mainTeam = Team::with(['members', 'leader'])->find($user->team_id);
        }

        // TODOS los equipos donde participa (por la relación members)
        // Cargamos también los miembros y el líder para que las vistas puedan mostrar nombres
        $userTeams = Team::with(['members', 'leader'])
            ->whereHas('members', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->get();

        // Solicitudes pendientes para los equipos que lidera
        $pendingRequests = TeamJoinRequest::with(['team', 'user'])
            ->whereHas('team', function ($q) use ($user) {
                $q->where('leader_id', $user->id);
            })
            ->where('status', 'pending')
            ->get();

        return view('pagPrincipal.miEquipo', [
            'team'      => $mainTeam,   // equipo principal
            'userTeams' => $userTeams,  // todos los equipos donde está
            'pendingRequests' => $pendingRequests, // solicitudes pendientes de sus equipos
        ]);
    }

    // Muestra el formulario de "Crear Equipo" (Diseño Participante)
    public function create()
    {
        return view('pagPrincipal.crearEquipo'); 
    }

    // Guarda el equipo creado por el participante
    public function store(StoreTeamRequest $request)
    {
        $data = $request->validated();

        // Usuario autenticado
        $user = auth()->user();

        // 1️⃣ Crear el equipo
        $team = Team::create([
            'name' => $data['team_name'],
            'leader_id' => $user->id,  
        ]);

        // 2️⃣ Agregar automáticamente al creador a la tabla pivote CON ROLE NULL (será mostrado como "Líder")
        $team->members()->attach($user->id, ['role' => null]);

        return redirect()
            ->route('panel.lista-equipo')
            ->with('success', 'Equipo creado y te has unido automáticamente.');
    }

    // Lógica para enviar solicitud de unirse a un equipo
    public function join(JoinTeamRequest $request)
    {
        $team = Team::findOrFail($request->team_id);

        // Bloquear nuevas solicitudes si el equipo participa en un evento activo
        $hasActiveEvent = $team->projects()
            ->whereHas('event', function($q) {
                $q->where('status', 'activo');
            })
            ->exists();

        if ($hasActiveEvent) {
            return back()->with('error', 'No puedes enviar solicitudes porque el equipo participa en un evento activo.');
        }

        // Validar que no esté lleno (incluyendo solicitudes pendientes)
        $memberCount = $team->members()->count();
        $pendingCount = $team->joinRequests()->where('status', 'pending')->count();
        
        if ($memberCount + $pendingCount >= 4) {
            return back()->with('error', 'El equipo está lleno o hay demasiadas solicitudes pendientes.');
        }

        // Validar que no sea miembro ya
        if ($team->members()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'Ya perteneces a este equipo.');
        }

        // Validar y obtener el rol (Back, Front, Diseñador)
        $role = $request->validated()['role'];

        // Verificar si ya tiene una solicitud pendiente
        $existingRequest = $team->joinRequests()
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            // Si existe una solicitud pendiente, actualizarla con el nuevo rol
            $existingRequest->update(['role' => $role]);
            
            return back()->with('success', 'Solicitud actualizada con el nuevo rol: ' . $role . '. Espera la respuesta del líder.');
        }

        // NOTA: Ya no bloqueamos si el rol está ocupado - el líder decidirá si acepta
        // La validación se hará al momento de aceptar la solicitud

        // Eliminar solicitudes previas (rechazadas o aceptadas) para permitir un nuevo intento
        TeamJoinRequest::where('team_id', $team->id)
            ->where('user_id', auth()->id())
            ->delete();

        // Crear solicitud de unirse
        $joinRequest = TeamJoinRequest::create([
            'team_id' => $team->id,
            'user_id' => auth()->id(),
            'role' => $role,
            'status' => 'pending',
        ]);

        // Enviar email al líder del equipo
        $leader = $team->leader;
        if ($leader && $leader->email) {
            try {
                Mail::to($leader->email)->send(new TeamJoinRequestMail($joinRequest));
            } catch (\Exception $e) {
                // Log error pero no detener el proceso
                \Log::error('Error enviando email de solicitud de equipo: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Solicitud enviada al líder del equipo. Espera su respuesta.');
    }

    public function acceptJoinRequest($requestId)
    {
        $joinRequest = TeamJoinRequest::findOrFail($requestId);
        $team = $joinRequest->team;
        $user = $joinRequest->user;

        // Verificar que el usuario autenticado sea el líder del equipo
        if (auth()->id() !== $team->leader_id) {
            return back()->with('error', 'No tienes permiso para aceptar solicitudes de este equipo.');
        }

        // Verificar si el equipo está participando en un evento activo
        $hasActiveEvent = $team->projects()
            ->whereHas('event', function($q) {
                $q->where('status', 'activo');
            })
            ->exists();

        if ($hasActiveEvent) {
            return back()->with('error', 'No puedes agregar miembros mientras el equipo está participando en un evento activo.');
        }

        // Verificar que no esté lleno
        if ($team->members()->count() >= 4) {
            return back()->with('error', 'El equipo está lleno.');
        }

        // Verificar que no haya otro miembro con el mismo rol (excepto si es Líder/null)
        if ($joinRequest->role) {
            $existingMember = $team->members()
                ->wherePivot('role', $joinRequest->role)
                ->first();
            
            if ($existingMember) {
                return back()->with('error', 'No puedes aceptar esta solicitud porque el rol de ' . $joinRequest->role . ' ya está ocupado por ' . $existingMember->name . '. Elimina primero a ese miembro para liberar el rol.');
            }
        }

        // Aceptar la solicitud
        $joinRequest->update(['status' => 'accepted']);

        // Agregar usuario al equipo con el rol especificado
        $team->members()->attach($user->id, ['role' => $joinRequest->role]);

        return back()->with('success', $user->name . ' se ha unido al equipo como ' . $joinRequest->role . '.');
    }

    public function rejectJoinRequest($requestId)
    {
        $joinRequest = TeamJoinRequest::findOrFail($requestId);
        $team = $joinRequest->team;
        $user = $joinRequest->user;

        // Verificar que el usuario autenticado sea el líder del equipo
        if (auth()->id() !== $team->leader_id) {
            return back()->with('error', 'No tienes permiso para rechazar solicitudes de este equipo.');
        }

        // Rechazar la solicitud
        $joinRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Solicitud de ' . $user->name . ' rechazada.');
    }

    public function removeMember($teamId, $userId)
    {
        $team = Team::findOrFail($teamId);
        $user = User::findOrFail($userId);

        // Verificar que el usuario autenticado sea el líder del equipo
        if (auth()->id() !== $team->leader_id) {
            return back()->with('error', 'No tienes permiso para eliminar miembros de este equipo.');
        }

        // Verificar si el equipo está participando en un evento activo
        $hasActiveEvent = $team->projects()
            ->whereHas('event', function($q) {
                $q->where('status', 'activo');
            })
            ->exists();

        if ($hasActiveEvent) {
            return back()->with('error', 'No puedes eliminar miembros mientras el equipo está participando en un evento activo.');
        }

        // No permitir que el líder se elimine a sí mismo
        if ($team->leader_id === $userId) {
            return back()->with('error', 'No puedes eliminarte a ti mismo como líder del equipo.');
        }

        // Eliminar el usuario del equipo
        $team->members()->detach($userId);

        return back()->with('success', $user->name . ' ha sido eliminado del equipo.');
    }

    public function show(Team $team)
    {
        // Cargar los miembros del equipo
        $members = $team->members()->get();

        return view('pagPrincipal.listaEquipo', [
            'team' => $team,
            'members' => $members,
        ]);
    }

    // Enviar invitación por correo
    public function sendInvitation(TeamInvitationRequest $request)
    {
        $team = Team::findOrFail($request->team_id);

        // Verificar que sea el líder del equipo
        if (auth()->id() !== $team->leader_id) {
            return back()->with('error', 'No tienes permiso para enviar invitaciones en este equipo.');
        }

        // Bloquear invitaciones si el equipo participa en un evento activo
        $hasActiveEvent = $team->projects()
            ->whereHas('event', function($q) {
                $q->where('status', 'activo');
            })
            ->exists();

        if ($hasActiveEvent) {
            return back()->with('error', 'No puedes enviar invitaciones mientras el equipo participa en un evento activo.');
        }

        // Validar el email y rol
        $validated = $request->validated();

        $email = $validated['email'];
        $role = $validated['role'];

        // Verificar cupo del equipo (máximo 4 miembros)
        $currentMembersCount = $team->members()->count();
        $pendingInvitationsCount = TeamInvitation::where('team_id', $team->id)
            ->where('status', 'pending')
            ->count();
        
        if ($currentMembersCount + $pendingInvitationsCount >= 4) {
            return back()->with('error', 'El equipo ya está lleno. No se pueden enviar más invitaciones (máximo 4 miembros).');
        }

        // Verificar que no sea miembro ya (solo si el usuario existe)
        $existingUser = User::where('email', $email)->first();
        if ($existingUser && $existingUser->teams()->where('team_id', $team->id)->exists()) {
            return back()->with('error', 'Este usuario ya es miembro del equipo.');
        }

        // Buscar cualquier invitación existente (pendiente, rechazada, expirada)
        $existingInvitation = TeamInvitation::where('team_id', $team->id)
            ->where('email', $email)
            ->whereIn('status', ['pending', 'rejected', 'expired'])
            ->first();

        // Verificar que no haya invitación ya aceptada Y que el usuario siga siendo miembro activo
        // Si el usuario ya no está en el equipo, puede recibir una nueva invitación
        $acceptedInvitation = TeamInvitation::where('team_id', $team->id)
            ->where('email', $email)
            ->where('status', 'accepted')
            ->first();

        if ($acceptedInvitation) {
            // Verificar si el usuario aún está en el equipo
            $user = User::where('email', $email)->first();
            if ($user && $team->members->contains($user->id)) {
                return back()->with('error', 'Este email ya ha aceptado una invitación previa.');
            }
            // Si no está en el equipo, permitir nueva invitación actualizando la anterior
            $acceptedInvitation->update([
                'status' => 'pending',
                'token' => Str::random(64),
                'role' => $role,
                'inviter_id' => auth()->id(),
            ]);
            
            Mail::to($email)->send(new TeamInvitationMail($acceptedInvitation));
            
            return back()->with('success', 'Nueva invitación enviada correctamente.');
        }

        // Generar nuevo token
        $token = Str::random(64);

        if ($existingInvitation) {
            // Si existe una invitación pendiente con el mismo rol, no hacer nada
            if ($existingInvitation->status === 'pending' && $existingInvitation->role === $role) {
                return back()->with('error', 'Ya existe una invitación pendiente para este email con el mismo rol.');
            }
            
            // Actualizar la invitación existente con el nuevo rol y token
            $existingInvitation->update([
                'role' => $role,
                'token' => $token,
                'status' => 'pending',
                'inviter_id' => auth()->id(),
            ]);
            $invitation = $existingInvitation;
        } else {
            // Crear una nueva invitación
            $invitation = TeamInvitation::create([
                'team_id' => $team->id,
                'inviter_id' => auth()->id(),
                'email' => $email,
                'role' => $role,
                'token' => $token,
                'status' => 'pending',
            ]);
        }

        // Enviar correo de invitación
        try {
            Mail::to($email)->send(new TeamInvitationMail($invitation));
            
            // Log para debug (revisa storage/logs/laravel.log)
            \Log::info('✅ Invitación enviada', [
                'email' => $email,
                'team' => $team->name,
                'token' => $token,
                'url' => route('team-invitation.accept', ['token' => $token])
            ]);
            
            $linkInvitacion = route('team-invitation.accept', ['token' => $token]);
            
            // Mensaje diferenciado si el usuario no existe
            $userExists = User::where('email', $email)->exists();
            if ($userExists) {
                $mensaje = $existingInvitation 
                    ? "✅ Invitación anterior cancelada. Nueva invitación enviada a {$email} como {$role}." 
                    : "✅ Invitación enviada a {$email} como {$role}.";
            } else {
                $mensaje = $existingInvitation 
                    ? "✅ Invitación anterior cancelada. Nueva invitación enviada a {$email} como {$role}. El usuario deberá registrarse para aceptar." 
                    : "✅ Invitación enviada a {$email} como {$role}. El usuario deberá registrarse para aceptar.";
            }
            
            return back()->with('success', $mensaje);
        } catch (\Exception $e) {
            // Si hay error en el correo, aún así la invitación se guardó
            \Log::error('❌ Error al enviar invitación', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            
            return back()->with('warning', 'Invitación guardada, pero hubo un error al enviar el correo: ' . $e->getMessage());
        }
    }

    // Aceptar invitación por token
    public function acceptInvitation($token)
    {
        $invitation = TeamInvitation::where('token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        $team = $invitation->team;

        // Obtener o buscar el usuario por email
        $user = User::where('email', $invitation->email)->first();
        
        // Si el usuario no existe, redirigir a registro
        if (!$user) {
            return redirect()->route('public.register')
                ->with('info', 'Necesitas crear una cuenta con el email ' . $invitation->email . ' para aceptar la invitación.')
                ->with('email', $invitation->email)
                ->with('token', $token);
        }

        // Si el usuario no está autenticado, pedirle que inicie sesión
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('info', 'Por favor inicia sesión con tu cuenta para aceptar la invitación.')
                ->with('token', $token);
        }

        // Verificar que el usuario autenticado sea el invitado
        if (auth()->user()->email !== $invitation->email) {
            return redirect()->route('panel.mi-equipo')
                ->with('error', 'Esta invitación es para otro email. Debes iniciar sesión con ' . $invitation->email);
        }

        // Verificar si el equipo está participando en un evento activo
        $hasActiveEvent = $team->projects()
            ->whereHas('event', function($q) {
                $q->where('status', 'activo');
            })
            ->exists();

        if ($hasActiveEvent) {
            return redirect()->route('panel.mi-equipo')
                ->with('error', 'No puedes unirte a este equipo mientras está participando en un evento activo.');
        }

        // Verificar que no esté lleno
        if ($team->members()->count() >= 4) {
            return redirect()->route('panel.mi-equipo')
                ->with('error', 'El equipo está lleno. No se puede aceptar la invitación.');
        }

        // Verificar que ya no sea miembro
        if ($team->members()->where('user_id', $user->id)->exists()) {
            return redirect()->route('panel.mi-equipo')
                ->with('info', 'Ya eres miembro de este equipo.');
        }

        // Verificar que el rol no esté ocupado (si tiene rol asignado)
        if ($invitation->role) {
            $existingMember = $team->members()
                ->wherePivot('role', $invitation->role)
                ->first();
            
            if ($existingMember) {
                // Rechazar la invitación automáticamente
                $invitation->update([
                    'status' => 'rejected',
                ]);

                return redirect()->route('panel.mi-equipo')
                    ->with('error', 'Lo sentimos, el rol de ' . $invitation->role . ' ya está ocupado por ' . $existingMember->name . ' en el equipo ' . $team->name . '. La invitación ha sido rechazada automáticamente. Contacta al líder del equipo para más información.');
            }
        }

        // Aceptar la invitación
        $invitation->update([
            'status' => 'accepted',
            'accepted_at' => now(),
        ]);

        // Agregar al usuario al equipo con el rol especificado en la invitación
        $team->members()->attach($user->id, ['role' => $invitation->role]);

        return redirect()->route('panel.mi-equipo')
            ->with('success', '¡Bienvenido! Te has unido al equipo ' . $team->name);
    }
}
