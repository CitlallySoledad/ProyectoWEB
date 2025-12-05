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
    padding: 8px 10px;
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 18px;
    overflow-x: auto;
}

.user-teams-label {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: .08em;
    color: #9ca3af;
    margin-right: 6px;
    flex-shrink: 0;
}

.user-team-pill {
    padding: 6px 14px;
    border-radius: 999px;
    background: #0f172a;
    border: 1px solid rgba(148, 163, 184, .6);
    font-size: 0.85rem;
    white-space: nowrap;
}

.user-team-pill.active {
    background: linear-gradient(90deg, #4f46e5, #6366f1);
    border-color: transparent;
    font-weight: 600;
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

.member-name {
    color: #e2e8f0;
    font-size: 1rem;
    font-weight: 500;
}

.member-role {
    color: #f87171;
    font-size: 0.85rem;
    margin-left: auto;
    background: #7f1d1d;
    padding: 4px 10px;
    border-radius: 6px;
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

    function mostrarEquipo(teamId, teamName) {
        // Cambiar título
        if (title && teamName) {
            title.textContent = 'Miembros — ' + teamName;
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

    pills.forEach(pill => {
        pill.addEventListener('click', function () {
            const teamId = this.dataset.teamId;
            const teamName = this.dataset.teamName;

            // Estilo activo en la pastilla seleccionada
            pills.forEach(p => p.classList.remove('active'));
            this.classList.add('active');

            // Mostrar miembros del equipo
            mostrarEquipo(teamId, teamName);
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
                        <li class="sidebar-item"><a class="sidebar-link" href="#"><i class="bi bi-calendar-event"></i> <span>Eventos</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="#"><i class="bi bi-person"></i> <span>Mi perfil</span></a></li>
                    </ul>
                </div>

                <div style="margin-top: 20px;">
                    <div class="sidebar-section-title">Equipo</div>
                    <ul class="sidebar-nav">
                        <li class="sidebar-item"><a class="sidebar-link active" href="{{ route('panel.mi-equipo') }}"><i class="bi bi-people"></i> <span>Mi equipo</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.lista-equipo') }}"><i class="bi bi-list-ul"></i> <span>Lista de equipo</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.teams.create') }}"><i class="bi bi-plus-circle"></i> <span>Crear equipo</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('roles') }}"><i class="bi bi-person-badge"></i> <span>Rol</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="#"><i class="bi bi-calendar-week"></i> <span>Lista eventos</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="#"><i class="bi bi-file-earmark-arrow-up"></i> <span>Submision del proyecto</span></a></li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-bottom">
                <div class="sidebar-logout">
                    <i class="bi bi-box-arrow-right"></i> <span>Salir</span>
                </div>
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

                {{-- 🔵 BARRA DE EQUIPOS DONDE PARTICIPA --}}
                {{-- 🔵 BARRA DE EQUIPOS DONDE PARTICIPA --}}
{{-- 🔵 BARRA DE EQUIPOS DONDE PARTICIPA --}}
<div class="user-teams-bar">
    <span class="user-teams-label">Equipos donde participas</span>

    @forelse($userTeams as $t)
        <button type="button"
                class="user-team-pill {{ $loop->first ? 'active' : '' }}"
                data-team-id="{{ $t->id }}"
                data-team-name="{{ $t->name }}">
            {{ $t->name }}
        </button>
    @empty
        <span style="color:#9ca3af; font-size:0.85rem;">
            No participas en ningún equipo todavía.
        </span>
    @endforelse
</div>
{{-- TARJETA CON MIEMBROS DEL EQUIPO SELECCIONADO --}}
@if ($userTeams->isEmpty())
    <div class="team-box">
        <h2 class="team-box-title">No tienes ningún equipo asignado</h2>
        <p style="color:#94a3b8;">Puedes crear uno o unirte desde “Lista de equipo”.</p>
    </div>
@else
    <div class="team-box">
        {{-- Título que vamos a ir cambiando con JS --}}
        <h2 class="team-box-title" id="teamTitle">
            Miembros — {{ $userTeams->first()->name }}
        </h2>

        {{-- Contenedor de miembros para CADA equipo (solo se muestra uno a la vez) --}}
        @foreach ($userTeams as $t)
            <div class="team-members-list"
                 data-team-id="{{ $t->id }}"
                 style="{{ $loop->first ? '' : 'display:none;' }}">

                @foreach ($t->members as $member)
                    <div class="member-item">
                        <img
                            src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=random"
                            class="member-avatar"
                            alt="{{ $member->name }}">

                        <span class="member-name">{{ $member->name }}</span>

                        @if ($t->leader && $t->leader->id === $member->id)
                            <span class="member-role">Líder</span>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endif


                </div>

                {{-- TARJETA DE MIEMBROS DEL EQUIPO PRINCIPAL --}}
                @if (!$team)
                    <div class="team-box">
                        <h2 class="team-box-title">No tienes un equipo principal asignado</h2>
                        <p style="color:#94a3b8;">Puedes crear uno o unirte desde “Lista de equipo”.</p>
                    </div>
                @else
                    <div class="team-box">
                        <h2 class="team-box-title">Miembros — {{ $team->name }}</h2>

                        @foreach ($team->members as $member)
                            <div class="member-item">
                                <img 
                                    src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=random"
                                    class="member-avatar"
                                    alt="{{ $member->name }}">
                                
                                <span class="member-name">{{ $member->name }}</span>

                                @if ($team->leader && $team->leader->id === $member->id)
                                    <span class="member-role">Líder</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </main>

    </div>
</div>
@endsection
