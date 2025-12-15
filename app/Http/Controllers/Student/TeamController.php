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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    // Muestra la lista de equipos en el panel del participante
    public function index()
    {
        $teams = Team::with(['members', 'leader'])->orderBy('created_at', 'desc')->paginate(6);
        return view('pagPrincipal.listaEquipo', compact('teams'));
    }

    public function miEquipo()
    {
        $user = Auth::user();
        $mainTeam = $user->team_id ? Team::with(['members', 'leader'])->find($user->team_id) : null;

        $userTeams = Team::with(['members', 'leader'])
            ->whereHas('members', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->get();

        $pendingRequests = TeamJoinRequest::with(['team', 'user'])
            ->whereHas('team', function ($q) use ($user) {
                $q->where('leader_id', $user->id);
            })
            ->where('status', 'pending')
            ->get();

        return view('pagPrincipal.miEquipo', [
            'team'             => $mainTeam,
            'userTeams'        => $userTeams,
            'pendingRequests'  => $pendingRequests,
        ]);
    }

    public function create()
    {
        return view('pagPrincipal.crearEquipo');
    }

    public function store(StoreTeamRequest $request)
    {
        $data = $request->validated();
        $user = Auth::user();

        $team = Team::create([
            'name'      => $data['team_name'],
            'leader_id' => $user->id,
        ]);

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
            ->whereHas('event', function ($q) {
                $q->where('status', 'activo');
            })
            ->exists();

        if ($hasActiveEvent) {
            return back()->with('error', 'No puedes enviar solicitudes porque el equipo participa en un evento activo.');
        }

        // Validar que no esté lleno (incluyendo solicitudes pendientes)
        $memberCount  = $team->members()->count();
        $pendingCount = $team->joinRequests()->where('status', 'pending')->count();
        if ($memberCount + $pendingCount >= 4) {
            return back()->with('error', 'El equipo está lleno o hay demasiadas solicitudes pendientes.');
        }

        // Validar que no sea miembro ya
        if ($team->members()->where('user_id', Auth::id())->exists()) {
            return back()->with('error', 'Ya perteneces a este equipo.');
        }

        $role = $request->validated()['role'];

        // Verificar si ya tiene una solicitud pendiente
        $existingRequest = $team->joinRequests()
            ->where('user_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if ($existingRequest) {
            $existingRequest->update(['role' => $role]);
            return back()->with('success', 'Solicitud actualizada con el nuevo rol: ' . $role . '. Espera la respuesta del líder.');
        }

        // Eliminar solicitudes previas para permitir un nuevo intento
        TeamJoinRequest::where('team_id', $team->id)
            ->where('user_id', Auth::id())
            ->delete();

        $joinRequest = TeamJoinRequest::create([
            'team_id' => $team->id,
            'user_id' => Auth::id(),
            'role'    => $role,
            'status'  => 'pending',
        ]);

        // Enviar email al líder del equipo
        $leader = $team->leader;
        if ($leader && $leader->email) {
            try {
                Mail::to($leader->email)->send(new TeamJoinRequestMail($joinRequest));
            } catch (\Exception $e) {
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

        if (Auth::id() !== $team->leader_id) {
            return back()->with('error', 'No tienes permiso para aceptar solicitudes de este equipo.');
        }

        $hasActiveEvent = $team->projects()
            ->whereHas('event', function ($q) {
                $q->where('status', 'activo');
            })
            ->exists();
        if ($hasActiveEvent) {
            return back()->with('error', 'No puedes agregar miembros mientras el equipo está participando en un evento activo.');
        }

        if ($team->members()->count() >= 4) {
            return back()->with('error', 'El equipo está lleno.');
        }

        if ($joinRequest->role) {
            $existingMember = $team->members()
                ->wherePivot('role', $joinRequest->role)
                ->first();
            if ($existingMember) {
                return back()->with('error', 'No puedes aceptar esta solicitud porque el rol de ' . $joinRequest->role . ' ya está ocupado por ' . $existingMember->name . '.');
            }
        }

        $joinRequest->update(['status' => 'accepted']);
        $team->members()->attach($user->id, ['role' => $joinRequest->role]);

        return back()->with('success', $user->name . ' se ha unido al equipo como ' . $joinRequest->role . '.');
    }

    public function rejectJoinRequest($requestId)
    {
        $joinRequest = TeamJoinRequest::findOrFail($requestId);
        $team = $joinRequest->team;
        $user = $joinRequest->user;

        if (Auth::id() !== $team->leader_id) {
            return back()->with('error', 'No tienes permiso para rechazar solicitudes de este equipo.');
        }

        $joinRequest->update(['status' => 'rejected']);

        return back()->with('success', 'Solicitud de ' . $user->name . ' rechazada.');
    }

    public function removeMember($teamId, $userId)
    {
        $team = Team::findOrFail($teamId);
        $user = User::findOrFail($userId);

        if (Auth::id() !== $team->leader_id) {
            return back()->with('error', 'No tienes permiso para eliminar miembros de este equipo.');
        }

        $hasActiveEvent = $team->projects()
            ->whereHas('event', function ($q) {
                $q->where('status', 'activo');
            })
            ->exists();
        if ($hasActiveEvent) {
            return back()->with('error', 'No puedes eliminar miembros mientras el equipo está participando en un evento activo.');
        }

        if ($team->leader_id === $userId) {
            return back()->with('error', 'No puedes eliminarte a ti mismo como líder del equipo.');
        }

        $team->members()->detach($userId);

        return back()->with('success', $user->name . ' ha sido eliminado del equipo.');
    }

    public function show(Team $team)
    {
        $members = $team->members()->get();

        return view('pagPrincipal.listaEquipo', [
            'team'    => $team,
            'members' => $members,
        ]);
    }

    // Enviar invitación por correo
    public function sendInvitation(TeamInvitationRequest $request)
    {
        $team = Team::findOrFail($request->team_id);

        if (Auth::id() !== $team->leader_id) {
            return back()->with('error', 'No tienes permiso para enviar invitaciones en este equipo.');
        }

        // Bloquear invitaciones si el equipo participa en un evento activo
        $hasActiveEvent = $team->projects()
            ->whereHas('event', function ($q) {
                $q->where('status', 'activo');
            })
            ->exists();
        if ($hasActiveEvent) {
            return back()->with('error', 'No puedes enviar invitaciones mientras el equipo participa en un evento activo.');
        }

        $validated = $request->validated();
        $email = $validated['email'];
        $role  = $validated['role'];

        $currentMembersCount = $team->members()->count();
        $pendingInvitationsCount = TeamInvitation::where('team_id', $team->id)
            ->where('status', 'pending')
            ->count();
        if ($currentMembersCount + $pendingInvitationsCount >= 4) {
            return back()->with('error', 'El equipo ya está lleno. No se pueden enviar más invitaciones (máximo 4 miembros).');
        }

        $existingUser = User::where('email', $email)->first();
        if ($existingUser && $existingUser->teams()->where('team_id', $team->id)->exists()) {
            return back()->with('error', 'Este usuario ya es miembro del equipo.');
        }

        $existingInvitation = TeamInvitation::where('team_id', $team->id)
            ->where('email', $email)
            ->whereIn('status', ['pending', 'rejected', 'expired'])
            ->first();

        $acceptedInvitation = TeamInvitation::where('team_id', $team->id)
            ->where('email', $email)
            ->where('status', 'accepted')
            ->first();

        if ($acceptedInvitation) {
            $user = User::where('email', $email)->first();
            if ($user && $team->members->contains($user->id)) {
                return back()->with('error', 'Este email ya ha aceptado una invitación previa.');
            }
            $acceptedInvitation->update([
                'status'     => 'pending',
                'token'      => Str::random(64),
                'role'       => $role,
                'inviter_id' => Auth::id(),
            ]);

            Mail::to($email)->send(new TeamInvitationMail($acceptedInvitation));

            return back()->with('success', 'Nueva invitación enviada correctamente.');
        }

        $token = Str::random(64);

        if ($existingInvitation) {
            if ($existingInvitation->status === 'pending' && $existingInvitation->role === $role) {
                return back()->with('error', 'Ya existe una invitación pendiente para este email con el mismo rol.');
            }
            
            $existingInvitation->update([
                'role'       => $role,
                'token'      => $token,
                'status'     => 'pending',
                'inviter_id' => Auth::id(),
            ]);
            $invitation = $existingInvitation;
        } else {
            $invitation = TeamInvitation::create([
                'team_id'    => $team->id,
                'inviter_id' => Auth::id(),
                'email'      => $email,
                'role'       => $role,
                'token'      => $token,
                'status'     => 'pending',
            ]);
        }

        try {
            Mail::to($email)->send(new TeamInvitationMail($invitation));

            Log::info('Invitación enviada', [
                'email' => $email,
                'team'  => $team->name,
                'token' => $token,
                'url'   => route('team-invitation.accept', ['token' => $token])
            ]);

            $userExists = User::where('email', $email)->exists();
            if ($userExists) {
                $mensaje = $existingInvitation
                    ? "Invitación anterior cancelada. Nueva invitación enviada a {$email} como {$role}."
                    : "Invitación enviada a {$email} como {$role}.";
            } else {
                $mensaje = $existingInvitation
                    ? "Invitación anterior cancelada. Nueva invitación enviada a {$email} como {$role}. El usuario deberá registrarse para aceptar."
                    : "Invitación enviada a {$email} como {$role}. El usuario deberá registrarse para aceptar.";
            }

            return back()->with('success', $mensaje);
        } catch (\Exception $e) {
            Log::error('Error al enviar invitación', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);

            return back()->with('warning', 'Invitación guardada, pero hubo un error al enviar el correo: ' . $e->getMessage());
        }
    }

    public function acceptInvitation($token)
    {
        $invitation = TeamInvitation::where('token', $token)
            ->where('status', 'pending')
            ->firstOrFail();

        $team = $invitation->team;

        $user = User::where('email', $invitation->email)->first();
        if (!$user) {
            return redirect()->route('public.register')
                ->with('info', 'Necesitas crear una cuenta con el email ' . $invitation->email . ' para aceptar la invitación.')
                ->with('email', $invitation->email)
                ->with('token', $token);
        }

        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('info', 'Por favor inicia sesión con tu cuenta para aceptar la invitación.')
                ->with('token', $token);
        }

        if (Auth::user()->email !== $invitation->email) {
            return redirect()->route('panel.mi-equipo')
                ->with('error', 'Esta invitación es para otro email. Debes iniciar sesión con ' . $invitation->email);
        }

        $hasActiveEvent = $team->projects()
            ->whereHas('event', function ($q) {
                $q->where('status', 'activo');
            })
            ->exists();
        if ($hasActiveEvent) {
            return redirect()->route('panel.mi-equipo')
                ->with('error', 'No puedes unirte a este equipo mientras está participando en un evento activo.');
        }

        if ($team->members()->count() >= 4) {
            return redirect()->route('panel.mi-equipo')
                ->with('error', 'El equipo está lleno. No se puede aceptar la invitación.');
        }

        if ($team->members()->where('user_id', $user->id)->exists()) {
            return redirect()->route('panel.mi-equipo')
                ->with('info', 'Ya eres miembro de este equipo.');
        }

        if ($invitation->role) {
            $existingMember = $team->members()
                ->wherePivot('role', $invitation->role)
                ->first();
            if ($existingMember) {
                $invitation->update(['status' => 'rejected']);

                return redirect()->route('panel.mi-equipo')
                    ->with('error', 'El rol de ' . $invitation->role . ' ya está ocupado por ' . $existingMember->name . ' en el equipo ' . $team->name . '.');
            }
        }

        $invitation->update([
            'status'      => 'accepted',
            'accepted_at' => now(),
        ]);

        $team->members()->attach($user->id, ['role' => $invitation->role]);

        return redirect()->route('panel.mi-equipo')
            ->with('success', '¡Bienvenido! Te has unido al equipo ' . $team->name);
    }
}
