@extends('layouts.admin')

@section('title', 'Mi equipo')

@push('styles')
<style>
.mi-equipo-fullscreen {
    width: 100%;
    min-height: 100vh;
    padding: 0;
    margin: 0;
    background: #020617;
}

/* Reutilizamos el mismo panel-card que en Lista de equipo,
   pero full screen (sin tarjeta flotante) */
.panel-card {
    width: 100%;
    max-width: 100%;
    min-height: 100vh;
    background: transparent;
    border-radius: 0;
    box-shadow: none;
    display: flex;
    overflow: hidden;
    color: #e5e7eb;
}

/* SIDEBAR (igual que en lista-equipo, pero con Mi equipo activo) */
.panel-sidebar {
    width: 250px;
    background: linear-gradient(180deg, #4c1d95, #7c3aed);
    padding: 18px 14px;
    display: flex;
    flex-direction: column;
    flex-shrink: 0;
}

.sidebar-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
.sidebar-back { width: 32px; height: 32px; border-radius: 999px; border: 1px solid rgba(255, 255, 255, 0.35); display: flex; align-items: center; justify-content: center; background: transparent; color: #f9fafb; cursor: pointer; }
.sidebar-logo img { height: 40px; }
.sidebar-middle { flex: 1; display: flex; flex-direction: column; gap: 18px; overflow-y: auto; }
.sidebar-section-title { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.06em; color: #c4b5fd; margin-bottom: 4px; }
.sidebar-nav { list-style: none; margin: 0; padding: 0; }
.sidebar-item { margin-bottom: 6px; }
.sidebar-link { display: flex; align-items: center; gap: 10px; padding: 8px 10px; border-radius: 999px; font-size: 0.9rem; text-decoration: none; color: #e5e7eb; transition: background 0.2s, transform 0.2s; }
.sidebar-link i { font-size: 1.1rem; }
.sidebar-link:hover { background: rgba(15, 23, 42, 0.3); transform: translateX(2px); }
.sidebar-link.active { background: #020617; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5); }
.sidebar-bottom { padding-top: 12px; border-top: 1px solid rgba(248, 250, 252, 0.18); }
.sidebar-logout { display: inline-flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 999px; color: #fecaca; cursor: pointer; }

/* CONTENIDO PRINCIPAL */
.panel-main { flex: 1; padding: 20px 26px 26px; display: flex; flex-direction: column; overflow-x: hidden; }
.panel-main-inner { width: 100%; margin: 0 auto; }
.panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
.panel-title { font-size: 1.6rem; font-weight: 700; }
.user-badge { display: inline-flex; align-items: center; gap: 8px; border-radius: 999px; padding: 6px 14px; background: rgba(15, 23, 42, 0.9); border: 1px solid rgba(148, 163, 184, 0.6); font-size: 0.85rem; white-space: nowrap; }

/* 🔵 BARRA DE EQUIPOS DEL USUARIO */
.user-teams-bar {
    background: rgba(15,23,42,0.7);
    border-radius: 999px;
    padding: 10px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    overflow-x: auto;
    border: 1px solid rgba(148, 163, 184, 0.2);
}

.user-teams-label {
    font-size: 0.85rem;
    font-weight: 600;
    letter-spacing: .05em;
    color: #ffffff;
    margin-right: 8px;
    flex-shrink: 0;
}
.user-teams-label {
    color: #ffffff !important;
}

.user-team-pill {
    padding: 7px 16px;
    border-radius: 999px;
    background: #0f172a;
    border: 1px solid rgba(148, 163, 184, .6);
    font-size: 0.85rem;
    white-space: nowrap;
    cursor: pointer;
    transition: all 0.2s ease;
    color: #ffffff !important;
}

.user-team-pill:hover {
    background: rgba(79, 70, 229, 0.2);
    border-color: rgba(99, 102, 241, 0.8);
    transform: translateY(-1px);
}

.user-team-pill.active {
    background: linear-gradient(90deg, #4f46e5, #6366f1);
    border-color: transparent;
    font-weight: 600;
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
}

/* TARJETA DE MIEMBROS */
.team-box {
    background: rgba(15, 23, 42, 0.65);
    padding: 22px 28px;
    border-radius: 18px;
    max-width: 800px;
    box-shadow: 0 12px 30px rgba(0,0,0,0.45);
}

.team-box-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #cbd5e1;
    margin-bottom: 18px;
    text-transform: uppercase;
    letter-spacing: .08em;
}

.member-item {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 14px;
}

.member-avatar {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #64748b;
}

.member-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.member-name {
    color: #e2e8f0;
    font-size: 1rem;
    font-weight: 500;
}

.member-role {
    color: #94a3b8;
    font-size: 0.75rem;
    font-weight: 400;
}

/* MODALES DE ALERTA */
.error-modal-overlay,
.success-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.75);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes fadeOut {
    from { opacity: 1; }
    to { opacity: 0; }
}

.error-modal,
.success-modal {
    background: #1e293b;
    border-radius: 16px;
    padding: 32px;
    max-width: 420px;
    width: 90%;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.8);
    text-align: center;
    animation: slideIn 0.3s ease;
}

@keyframes slideIn {
    from { 
        transform: translateY(-30px) scale(0.95);
        opacity: 0;
    }
    to { 
        transform: translateY(0) scale(1);
        opacity: 1;
    }
}

.error-modal-icon,
.success-modal-icon {
    font-size: 64px;
    margin-bottom: 20px;
    animation: bounce 0.6s ease;
}

@keyframes bounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.error-modal-title,
.success-modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #e5e7eb;
    margin-bottom: 16px;
}

.error-modal-message,
.success-modal-message {
    font-size: 1rem;
    color: #cbd5e1;
    line-height: 1.6;
    margin-bottom: 24px;
}

.error-modal-btn,
.success-modal-btn {
    padding: 12px 32px;
    border: none;
    border-radius: 8px;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.error-modal-btn {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
}

.error-modal-btn:hover {
    background: linear-gradient(135deg, #dc2626, #b91c1c);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
}

.success-modal-btn {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
}

.success-modal-btn:hover {
    background: linear-gradient(135deg, #059669, #047857);
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
}

/* RESPONSIVE */
@media (max-width: 1024px) {
    .panel-card { flex-direction: column; }
    .panel-sidebar { width: 100%; }
    .sidebar-middle { flex-direction: row; gap: 10px; overflow-x: auto; padding-bottom: 10px; }
    .sidebar-nav { display: flex; gap: 8px; }
    .sidebar-section-title { display: none; }
    .panel-main { padding: 16px; }
    .team-box { max-width: 100%; }
}

@media (max-width: 768px) {
    .panel-header { flex-direction: column; align-items: flex-start; gap: 10px; }
    .user-badge { width: 100%; justify-content: center; }
    .user-teams-bar { border-radius: 14px; }
}
</style>
@endpush
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const pills = document.querySelectorAll('.user-team-pill');
    const title = document.getElementById('teamTitle');
    const memberLists = document.querySelectorAll('.team-members-list');

    function mostrarEquipo(teamId, teamName, isLeader) {
        // Cambiar título con icono de líder o miembro
        if (title && teamName) {
            const icon = isLeader === 'true' ? '🏆' : '👥';
            const role = isLeader === 'true' ? 'Líder' : 'Miembro';
            title.textContent = `${icon} ${teamName} (${role})`;
        }

        // Mostrar solo la lista del equipo seleccionado
        memberLists.forEach(list => {
            if (list.dataset.teamId === teamId) {
                list.style.display = 'block';
            } else {
                list.style.display = 'none';
            }
        });
    }

    // Mostrar el primer equipo activo al cargar
    const activePill = document.querySelector('.user-team-pill.active');
    if (activePill) {
        mostrarEquipo(
            activePill.dataset.teamId, 
            activePill.dataset.teamName,
            activePill.dataset.isLeader
        );
    }

    pills.forEach(pill => {
        pill.addEventListener('click', function () {
            const teamId = this.dataset.teamId;
            const teamName = this.dataset.teamName;
            const isLeader = this.dataset.isLeader;

            // Estilo activo en la pastilla seleccionada
            pills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');

            // Mostrar miembros del equipo
            mostrarEquipo(teamId, teamName, isLeader);
        });
    });
});
</script>
@endpush

@section('content')
<div class="mi-equipo-fullscreen">
    <div class="panel-card">

        {{-- SIDEBAR --}}
        <aside class="panel-sidebar">
            <div class="sidebar-top">
                <button class="sidebar-back" type="button" onclick="window.location='{{ url('/panel') }}'">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <div class="sidebar-logo">
                    <img src="{{ asset('imagenes/logo-ito.png') }}" alt="Logo">
                </div>
            </div>

            <div class="sidebar-middle">
                <div>
                    <div class="sidebar-section-title">Menú</div>
                    <ul class="sidebar-nav">
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.participante') }}"><i class="bi bi-house-door"></i> <span>Inicio</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.eventos') }}"><i class="bi bi-calendar-event"></i> <span>Eventos</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.perfil') }}"><i class="bi bi-person"></i> <span>Mi perfil</span></a></li>
                    </ul>
                </div>

                <div style="margin-top: 20px;">
                    <div class="sidebar-section-title">Equipo</div>
                    <ul class="sidebar-nav">
                        <li class="sidebar-item"><a class="sidebar-link active" href="{{ route('panel.mi-equipo') }}"><i class="bi bi-people"></i> <span>Mi equipo</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.lista-equipo') }}"><i class="bi bi-list-ul"></i> <span>Lista de equipo</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.teams.create') }}"><i class="bi bi-plus-circle"></i> <span>Crear equipo</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.lista-eventos') }}"><i class="bi bi-calendar-week"></i> <span>Lista eventos</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.submission') }}"><i class="bi bi-file-earmark-arrow-up"></i> <span>Submision del proyecto</span></a></li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-bottom">
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="sidebar-logout" style="background: none; border: none; width: 100%; text-align: left; font: inherit; cursor: pointer;">
                        <i class="bi bi-box-arrow-right"></i> <span>Salir</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- CONTENIDO PRINCIPAL --}}
        <main class="panel-main">
            <div class="panel-main-inner">

                <header class="panel-header">
                    <h1 class="panel-title">Mi equipo</h1>
                    <div class="user-badge">
                        <i class="bi bi-person-circle"></i>
                        <span>{{ auth()->user()->name }}</span>
                    </div>
                </header>

                {{-- MODAL DE ALERTA PARA ERRORES --}}
                @if (session('error'))
                    <div id="errorModal" class="error-modal-overlay">
                        <div class="error-modal">
                            <div class="error-modal-icon">
                                @if(str_contains(session('error'), 'lleno') || str_contains(session('error'), 'cupo'))
                                    ⚠️
                                @elseif(str_contains(session('error'), 'rol') || str_contains(session('error'), 'ocupado'))
                                    🚫
                                @else
                                    ❌
                                @endif
                            </div>
                            <h3 class="error-modal-title">
                                @if(str_contains(session('error'), 'lleno') || str_contains(session('error'), 'cupo'))
                                    Equipo lleno
                                @elseif(str_contains(session('error'), 'rol') || str_contains(session('error'), 'ocupado'))
                                    Rol ya asignado
                                @else
                                    Error
                                @endif
                            </h3>
                            <p class="error-modal-message">{{ session('error') }}</p>
                            <button onclick="closeErrorModal()" class="error-modal-btn">Entendido</button>
                        </div>
                    </div>
                @endif

                {{-- MODAL DE ÉXITO --}}
                @if (session('success'))
                    <div id="successModal" class="success-modal-overlay">
                        <div class="success-modal">
                            <div class="success-modal-icon">✅</div>
                            <h3 class="success-modal-title">¡Éxito!</h3>
                            <p class="success-modal-message">{{ session('success') }}</p>
                            <button onclick="closeSuccessModal()" class="success-modal-btn">Aceptar</button>
                        </div>
                    </div>
                @endif

                {{-- 🔵 SEPARACIÓN DE EQUIPOS: LÍDER VS MIEMBRO --}}
                @php
                    // Asegurarnos de que $userTeams sea iterable en la vista para evitar errores
                    $userTeams = $userTeams ?? collect();
                    $team = $team ?? null;
                    
                    // Separar equipos donde es líder vs donde es solo miembro
                    $leaderTeams = $userTeams->filter(function($t) {
                        return $t->leader_id === auth()->id();
                    });
                    
                    $memberTeams = $userTeams->filter(function($t) {
                        return $t->leader_id !== auth()->id();
                    });
                @endphp

                {{-- SECCIÓN: EQUIPOS DONDE SOY LÍDER --}}
                @if($leaderTeams->isNotEmpty())
                <div class="user-teams-bar">
                    <span class="user-teams-label">🏆 Equipos donde soy líder</span>
                    @foreach($leaderTeams as $t)
                        <button type="button"
                                class="user-team-pill {{ $loop->first ? 'active' : '' }}"
                                data-team-id="{{ $t->id }}"
                                data-team-name="{{ $t->name }}"
                                data-is-leader="true">
                            {{ $t->name }}
                        </button>
                    @endforeach
                </div>
                @endif

                {{-- SECCIÓN: EQUIPOS DONDE SOY MIEMBRO --}}
                @if($memberTeams->isNotEmpty())
                <div class="user-teams-bar">
                    <span class="user-teams-label">👥 Equipos donde soy miembro</span>
                    @foreach($memberTeams as $t)
                        <button type="button"
                                class="user-team-pill {{ $leaderTeams->isEmpty() && $loop->first ? 'active' : '' }}"
                                data-team-id="{{ $t->id }}"
                                data-team-name="{{ $t->name }}"
                                data-is-leader="false">
                            {{ $t->name }}
                        </button>
                    @endforeach
                </div>
                @endif

                {{-- MENSAJE SI NO HAY EQUIPOS --}}
                @if($userTeams->isEmpty())
                <div class="user-teams-bar">
                    <span style="color:#9ca3af; font-size:0.85rem;">
                        No participas en ningún equipo todavía.
                    </span>
                </div>
                @endif
{{-- TARJETA CON MIEMBROS DEL EQUIPO SELECCIONADO --}}
@if ($userTeams->isEmpty())
    <div class="team-box">
        <h2 class="team-box-title">No tienes ningún equipo asignado</h2>
        <p style="color:#94a3b8;">Puedes crear uno o unirte desde "Lista de equipo".</p>
    </div>
@else
    <div class="team-box">
        {{-- Título que vamos a ir cambiando con JS --}}
        @php
            $firstTeam = $leaderTeams->first() ?? $memberTeams->first();
            $isFirstLeader = $firstTeam && $firstTeam->leader_id === auth()->id();
        @endphp
        <h2 class="team-box-title" id="teamTitle">
            {{ $isFirstLeader ? '🏆' : '👥' }} {{ $firstTeam->name }} ({{ $isFirstLeader ? 'Líder' : 'Miembro' }})
        </h2>

        {{-- Contenedor de miembros para CADA equipo (solo se muestra uno a la vez) --}}
        @foreach ($userTeams ?? [] as $t)
            <div class="team-members-list"
                 data-team-id="{{ $t->id }}"
                 style="{{ $loop->first ? '' : 'display:none;' }}">

                @foreach ($t->members ?? [] as $member)
                    <div class="member-item" style="display: flex; justify-content: space-between; align-items: center;">
                        <div style="display: flex; align-items: center; flex: 1; gap: 12px;">
                            <img
                                src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=random"
                                class="member-avatar"
                                alt="{{ $member->name }}">

                            <div class="member-info">
                                <span class="member-name">{{ $member->name }}</span>
                                {{-- Mostrar rol desde la tabla pivote o Líder si es líder --}}
                                @if ($t->leader && $t->leader->id === $member->id)
                                    <span class="member-role">Líder</span>
                                @elseif($member->pivot->role)
                                    <span class="member-role">{{ $member->pivot->role }}</span>
                                @endif
                            </div>
                        </div>

                        {{-- Botón para eliminar miembro (solo visible para el líder) --}}
                        @if (auth()->id() === $t->leader_id && auth()->id() !== $member->id)
                            <form action="{{ route('panel.members.remove', [$t->id, $member->id]) }}" method="POST" style="display: inline;">
                                @csrf
                                <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.875rem; background: #dc2626; color: white; border: none; border-radius: 0.375rem; cursor: pointer;">
                                    Eliminar
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach

                {{-- BOTÓN PARA INVITAR NUEVOS MIEMBROS (solo para líderes) --}}
                @if (auth()->id() === $t->leader_id)
                    <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #475569;">
                        <button 
                            type="button" 
                            onclick="toggleInvitationForm('{{ $t->id }}')"
                            style="width: 100%; padding: 14px 20px; background: linear-gradient(135deg, #3b82f6, #2563eb); color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 1rem; display: flex; align-items: center; justify-content: center; gap: 10px; transition: all 0.3s ease; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);">
                            <i class="bi bi-envelope-plus" style="font-size: 1.2rem;"></i>
                            <span>Invitar nuevos miembros</span>
                            <i class="bi bi-chevron-down" id="invitation-icon-{{ $t->id }}" style="font-size: 0.9rem; transition: transform 0.3s ease;"></i>
                        </button>

                        {{-- FORMULARIO DE INVITACIÓN (oculto por defecto) --}}
                        <div id="invitation-form-{{ $t->id }}" style="display: none; margin-top: 16px; padding: 20px; background: rgba(15, 23, 42, 0.5); border-radius: 8px; border: 1px solid #475569;">
                            <h3 style="font-size: 0.95rem; font-weight: 600; margin-bottom: 14px; color: #e2e8f0;">
                                <i class="bi bi-envelope"></i> Enviar invitación por correo
                            </h3>
                            <form action="{{ route('panel.invitations.send') }}" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
                                @csrf
                                <input type="hidden" name="team_id" value="{{ $t->id }}">
                                <input 
                                    type="email" 
                                    name="email" 
                                    placeholder="Correo del usuario"
                                    style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.3); border: 1px solid #475569; border-radius: 6px; color: #e2e8f0; font-size: 0.9rem;"
                                    required>
                                <select 
                                    name="role" 
                                    style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.3); border: 1px solid #475569; border-radius: 6px; color: #e2e8f0; font-size: 0.9rem;"
                                    required>
                                    <option value="" disabled selected>Selecciona el rol</option>
                                    <option value="Back">Back-end</option>
                                    <option value="Front">Front-end</option>
                                    <option value="Diseñador">Diseñador</option>
                                </select>
                                <button type="submit" style="padding: 10px 16px; background: #3b82f6; color: white; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; font-size: 0.9rem;">
                                    <i class="bi bi-send"></i> Enviar Invitación
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- INVITACIONES PENDIENTES --}}
                    @php
                        $pendingInvitations = $t->invitations()->where('status', 'pending')->get();
                    @endphp
                    @if ($pendingInvitations->count() > 0)
                        <div style="margin-top: 20px;">
                            <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 12px; color: #fbbf24;">
                                <i class="bi bi-hourglass-split"></i> Invitaciones pendientes
                            </h3>
                            @foreach ($pendingInvitations as $invitation)
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(15, 23, 42, 0.3); border-radius: 8px; margin-bottom: 8px; border-left: 3px solid #fbbf24;">
                                    <div style="flex: 1;">
                                        <p style="color: #e2e8f0; font-weight: 600; margin: 0; font-size: 0.9rem;">{{ $invitation->email }}</p>
                                        <p style="color: #9ca3af; margin: 4px 0 0 0; font-size: 0.8rem;">{{ $invitation->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <button onclick="copiarEnlace('{{ route('team-invitation.accept', ['token' => $invitation->token]) }}')" style="padding: 6px 12px; background: #8b5cf6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.8rem;">
                                        <i class="bi bi-clipboard"></i> Copiar
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif
            </div>
        @endforeach
    </div>
@endif

{{-- TARJETA DE MIEMBROS DEL EQUIPO PRINCIPAL --}}
                @if (!$team)
                    
                @else
                    <div class="team-box">
                        <h2 class="team-box-title">Miembros — {{ $team->name }}</h2>

                        @foreach ($team->members ?? [] as $member)
                            <div class="member-item" style="display: flex; justify-content: space-between; align-items: center;">
                                <div style="display: flex; align-items: center; flex: 1; gap: 12px;">
                                    <img 
                                        src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=random"
                                        class="member-avatar"
                                        alt="{{ $member->name }}">
                                    
                                    <div class="member-info">
                                        <span class="member-name">{{ $member->name }}</span>
                                        {{-- Mostrar rol desde la tabla pivote o Líder si es líder --}}
                                        @if ($team->leader && $team->leader->id === $member->id)
                                            <span class="member-role">Líder</span>
                                        @elseif($member->pivot->role)
                                            <span class="member-role">{{ $member->pivot->role }}</span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Botón para eliminar miembro (solo visible para el líder) --}}
                                @if (auth()->id() === $team->leader_id && auth()->id() !== $member->id)
                                    <form action="{{ route('panel.members.remove', [$team->id, $member->id]) }}" method="POST" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.875rem; background: #dc2626; color: white; border: none; border-radius: 0.375rem; cursor: pointer;">
                                            Eliminar
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- FORMULARIO DE INVITACIÓN (solo para líderes) --}}
                    @if (auth()->id() === $team->leader_id)
                        <div style="margin-top: 20px; padding: 16px; background: rgba(15, 23, 42, 0.3); border-radius: 12px; border-left: 4px solid #3b82f6;">
                            <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 12px; color: #e2e8f0;">
                                <i class="bi bi-envelope"></i> Invitar nuevos miembros
                            </h3>
                            <form action="{{ route('panel.invitations.send') }}" method="POST" style="display: flex; flex-direction: column; gap: 12px;">
                                @csrf
                                <input type="hidden" name="team_id" value="{{ $team->id }}">
                                <input 
                                    type="email" 
                                    name="email" 
                                    placeholder="Correo del usuario a invitar"
                                    style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.3); border: 1px solid #475569; border-radius: 8px; color: #e2e8f0; font-size: 0.95rem;"
                                    required>
                                <select 
                                    name="role" 
                                    style="width: 100%; padding: 10px; background: rgba(15, 23, 42, 0.3); border: 1px solid #475569; border-radius: 8px; color: #e2e8f0; font-size: 0.95rem;"
                                    required>
                                    <option value="" disabled selected>Selecciona el rol para esta invitación</option>
                                    <option value="Back">Back-end</option>
                                    <option value="Front">Front-end</option>
                                    <option value="Diseñador">Diseñador</option>
                                </select>
                                <button type="submit" style="padding: 10px 20px; background: #3b82f6; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; transition: background 0.2s;">
                                    <i class="bi bi-send"></i> Enviar Invitación
                                </button>
                            </form>
                            @if ($errors->has('email'))
                                <p style="color: #fca5a5; margin-top: 8px; font-size: 0.9rem;">{{ $errors->first('email') }}</p>
                            @endif
                        </div>

                        {{-- INVITACIONES PENDIENTES DEL EQUIPO PRINCIPAL --}}
                        @php
                            $pendingInvitations = $team->invitations()->where('status', 'pending')->get();
                        @endphp
                        @if ($pendingInvitations->count() > 0)
                            <div style="margin-top: 20px;">
                                <h3 style="font-size: 1rem; font-weight: 600; margin-bottom: 12px; color: #fbbf24;">
                                    <i class="bi bi-hourglass-split"></i> Invitaciones pendientes
                                </h3>
                                @foreach ($pendingInvitations as $invitation)
                                    <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(15, 23, 42, 0.3); border-radius: 8px; margin-bottom: 8px; border-left: 3px solid #fbbf24;">
                                        <div style="flex: 1;">
                                            <p style="color: #e2e8f0; font-weight: 600; margin: 0; font-size: 0.9rem;">{{ $invitation->email }}</p>
                                            <p style="color: #9ca3af; margin: 4px 0 0 0; font-size: 0.8rem;">{{ $invitation->created_at->format('d/m/Y H:i') }}</p>
                                        </div>
                                        <button onclick="copiarEnlace('{{ route('team-invitation.accept', ['token' => $invitation->token]) }}')" style="padding: 6px 12px; background: #8b5cf6; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 0.8rem;">
                                            <i class="bi bi-clipboard"></i> Copiar
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                @endif

            {{-- SOLICITUDES PENDIENTES --}}
            @php
                $isLeaderOfAnyTeam = $leaderTeams->isNotEmpty();
            @endphp
            @if($isLeaderOfAnyTeam)
                <section style="margin-top: 40px;">
                    <button 
                        type="button" 
                        onclick="toggleRequestsSection()"
                        style="width: 100%; padding: 16px 24px; background: linear-gradient(135deg, #7c3aed, #6d28d9); color: white; border: none; border-radius: 12px; cursor: pointer; font-weight: 700; font-size: 1.2rem; display: flex; align-items: center; justify-content: space-between; transition: all 0.3s ease; box-shadow: 0 6px 20px rgba(124, 58, 237, 0.4);">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <i class="bi bi-inbox" style="font-size: 1.4rem;"></i>
                            <span>Solicitudes de unirse</span>
                            @if($pendingRequests && $pendingRequests->count() > 0)
                                <span style="display: inline-block; background: #ef4444; color: white; font-size: 0.85rem; padding: 4px 12px; border-radius: 999px; font-weight: 600;">
                                    {{ $pendingRequests->count() }}
                                </span>
                            @endif
                        </div>
                        <i class="bi bi-chevron-down" id="requests-icon" style="font-size: 1.1rem; transition: transform 0.3s ease;"></i>
                    </button>

                    <div id="requests-section" style="display: none; margin-top: 16px; background: rgba(15, 23, 42, 0.65); border-radius: 18px; padding: 20px; box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);">
                        @if($pendingRequests && $pendingRequests->count() > 0)
                            @foreach($pendingRequests as $request)
                                @php
                                    // Verificar si el rol ya está ocupado en el equipo
                                    $roleOccupied = $request->team->members()
                                        ->wherePivot('role', $request->role)
                                        ->first();
                                @endphp
                                <div style="display: flex; justify-content: space-between; align-items: center; padding: 16px; background: rgba(15, 23, 42, 0.3); border-radius: 12px; margin-bottom: 12px; border-left: 4px solid {{ $roleOccupied ? '#f59e0b' : '#a78bfa' }};">
                                    <div style="flex: 1;">
                                        <p style="color: #e2e8f0; font-weight: 600; margin: 0 0 8px 0;">{{ $request->user->name }}</p>
                                        <p style="color: #9ca3af; margin: 0; font-size: 0.9rem;">
                                            Quiere unirse como: <strong style="color: #bfdbfe;">{{ $request->role }}</strong>
                                        </p>
                                        <p style="color: #6b7280; margin: 4px 0 0 0; font-size: 0.85rem;">
                                            En el equipo: <strong>{{ $request->team->name }}</strong>
                                        </p>
                                        
                                        @if($roleOccupied)
                                            <div style="display: inline-flex; align-items: center; gap: 6px; margin-top: 8px; padding: 6px 12px; background: rgba(245, 158, 11, 0.15); border: 1px solid #f59e0b; border-radius: 6px;">
                                                <span style="color: #fbbf24; font-size: 0.85rem; font-weight: 600;">
                                                    ⚠️ Rol ocupado por {{ $roleOccupied->name }}
                                                </span>
                                            </div>
                                            <p style="color: #9ca3af; margin: 8px 0 0 0; font-size: 0.8rem; font-style: italic;">
                                                Para aceptar esta solicitud, primero elimina a {{ $roleOccupied->name }} del equipo.
                                            </p>
                                        @endif
                                    </div>
                                    <div style="display: flex; gap: 8px; flex-direction: column;">
                                        <form action="{{ route('panel.requests.accept', $request->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" 
                                                    style="padding: 8px 16px; background: {{ $roleOccupied ? '#6b7280' : '#10b981' }}; color: white; border: none; border-radius: 8px; cursor: {{ $roleOccupied ? 'not-allowed' : 'pointer' }}; font-weight: 600; font-size: 0.9rem; transition: background 0.2s; width: 100%;"
                                                    {{ $roleOccupied ? 'disabled' : '' }}
                                                    title="{{ $roleOccupied ? 'Elimina primero a ' . $roleOccupied->name . ' para aceptar esta solicitud' : '' }}">
                                                <i class="bi bi-check-circle"></i> Aceptar
                                            </button>
                                        </form>
                                        <form action="{{ route('panel.requests.reject', $request->id) }}" method="POST" style="display: inline;">
                                            @csrf
                                            <button type="submit" style="padding: 8px 16px; background: #ef4444; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.9rem; transition: background 0.2s; width: 100%;">
                                                <i class="bi bi-x-circle"></i> Rechazar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div style="text-align: center; padding: 40px 20px;">
                                <div style="font-size: 64px; margin-bottom: 16px; opacity: 0.5;">📭</div>
                                <p style="color: #9ca3af; font-size: 1.1rem; margin: 0;">No hay solicitudes pendientes</p>
                                <p style="color: #6b7280; font-size: 0.9rem; margin-top: 8px;">Las solicitudes para unirse a tus equipos aparecerán aquí.</p>
                            </div>
                        @endif
                    </div>
                </section>
            @endif

        </main>

    </div>
</div>

<script>
    // Función para alternar visibilidad de formulario de invitación
    function toggleInvitationForm(teamId) {
        const form = document.getElementById('invitation-form-' + teamId);
        const icon = document.getElementById('invitation-icon-' + teamId);
        
        if (form.style.display === 'none' || form.style.display === '') {
            form.style.display = 'block';
            icon.style.transform = 'rotate(180deg)';
        } else {
            form.style.display = 'none';
            icon.style.transform = 'rotate(0deg)';
        }
    }

    // Función para alternar visibilidad de solicitudes
    function toggleRequestsSection() {
        const section = document.getElementById('requests-section');
        const icon = document.getElementById('requests-icon');
        
        if (section.style.display === 'none' || section.style.display === '') {
            section.style.display = 'block';
            icon.style.transform = 'rotate(180deg)';
        } else {
            section.style.display = 'none';
            icon.style.transform = 'rotate(0deg)';
        }
    }

    // Función para copiar el enlace de invitación al portapapeles
    function copiarEnlace(enlace) {
        navigator.clipboard.writeText(enlace).then(function() {
            alert('Enlace copiado al portapapeles. Puedes compartirlo con el usuario.');
        }, function(err) {
            console.error('Error al copiar:', err);
        });
    }

    // Función para cerrar modal de error
    function closeErrorModal() {
        const modal = document.getElementById('errorModal');
        if (modal) {
            modal.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    }

    // Función para cerrar modal de éxito
    function closeSuccessModal() {
        const modal = document.getElementById('successModal');
        if (modal) {
            modal.style.animation = 'fadeOut 0.3s ease';
            setTimeout(() => {
                modal.style.display = 'none';
            }, 300);
        }
    }

    // Cerrar modal al hacer clic fuera
    document.addEventListener('DOMContentLoaded', function() {
        const errorModal = document.getElementById('errorModal');
        const successModal = document.getElementById('successModal');

        if (errorModal) {
            errorModal.addEventListener('click', function(e) {
                if (e.target === errorModal) {
                    closeErrorModal();
                }
            });
        }

        if (successModal) {
            successModal.addEventListener('click', function(e) {
                if (e.target === successModal) {
                    closeSuccessModal();
                }
            });
        }

        // Auto-cerrar modal de éxito después de 5 segundos
        if (successModal) {
            setTimeout(() => {
                closeSuccessModal();
            }, 5000);
        }
    });
</script>

@endsection
