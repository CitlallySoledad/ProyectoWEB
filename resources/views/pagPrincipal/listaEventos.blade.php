@extends('layouts.admin')

@section('title', 'Lista eventos')

@push('styles')
<style>
/* ===== CONTENEDOR GENERAL ===== */
.lista-eventos-fullscreen {
    width: 100%;
    min-height: 100vh;
    margin: 0;
    padding: 0;
    background: radial-gradient(circle at top left, #1d4ed8, #0f172a);
}

/* ===== CONTENEDOR PRINCIPAL ===== */
.panel-card {
    width: 100%;
    max-width: 100%;
    min-height: 100vh;
    display: flex;
    overflow: hidden;
    color: #e5e7eb;
}

/* ===== SIDEBAR (MISMO ESTILO QUE LISTA-EQUIPO) ===== */
.panel-sidebar {
    width: 260px;
    background: linear-gradient(180deg, #4c1d95, #7c3aed);
    padding: 18px 14px;
    display: flex;
    flex-direction: column;
    flex-shrink: 0;
}

.sidebar-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 28px;
}

.sidebar-back {
    width: 36px;
    height: 36px;
    border-radius: 999px;
    border: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(15, 23, 42, 0.25);
    color: #e5e7eb;
    cursor: pointer;
}
.sidebar-back:hover { background: rgba(15, 23, 42, 0.4); }

.sidebar-logo img {
    width: 44px;
    height: 44px;
    border-radius: 999px;
    object-fit: cover;
    border: 2px solid rgba(248, 250, 252, 0.8);
}

.sidebar-section-title {
    font-size: 0.78rem;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #c4b5fd;
    margin-bottom: 6px;
}

.sidebar-middle {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 18px;
    overflow-y: auto;
}

.sidebar-nav {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.sidebar-item {}

.sidebar-link {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 10px;
    border-radius: 999px;
    text-decoration: none;
    font-size: 0.9rem;
    color: #e5e7eb;
    opacity: 0.85;
    transition: background 0.18s ease, opacity 0.18s ease, transform 0.1s ease;
}

.sidebar-link i {
    font-size: 1rem;
}

.sidebar-link:hover {
    opacity: 1;
    background: rgba(15, 23, 42, 0.22);
    transform: translateX(2px);
}

.sidebar-link.active {
    background: #0f172a;
    opacity: 1;
}

.sidebar-bottom {
    margin-top: 18px;
}

.sidebar-logout {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 9px 14px;
    border-radius: 999px;
    border: 1px solid rgba(248, 250, 252, 0.25);
    background: transparent;
    color: #fee2e2;
    font-size: 0.9rem;
    width: 100%;
    cursor: pointer;
}
.sidebar-logout i { font-size: 1rem; }
.sidebar-logout:hover { background: rgba(15, 23, 42, 0.35); }

/* ===== CONTENIDO PRINCIPAL ===== */
.panel-main {
    flex: 1;
    padding: 22px 26px 26px;
    display: flex;
    flex-direction: column;
    overflow-x: hidden;
}

.panel-main-inner {
    width: 100%;
    max-width: 1200px;
    margin: 0 auto;
}

/* Header */
.panel-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 16px;
}

.panel-title {
    font-size: 2rem;
    font-weight: 700;
    color: #f9fafb;
}

.user-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 12px;
    border-radius: 999px;
    background: rgba(15, 23, 42, 0.7);
    font-size: 0.85rem;
}

.user-badge i { font-size: 1rem; }

/* ===== LAYOUT IZQUIERDA TABLA / DERECHA INFO ===== */
.events-layout {
    display: grid;
    grid-template-columns: minmax(0, 2.4fr) minmax(260px, 0.9fr);
    gap: 22px;
}

/* ===== BUSCADOR Y FILTROS ===== */
.events-search-area {
    margin-bottom: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.events-search-input-wrapper {
    position: relative;
    width: 100%;
}

.events-search-input-wrapper input {
    width: 100%;
    padding: 12px 14px 12px 36px;
    border-radius: 999px;
    border: none;
    outline: none;
    font-size: 0.95rem;
    background: #f9fafb;
    color: #111827;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
}

.events-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.btn-filter-chip {
    border: none;
    border-radius: 18px;
    padding: 10px 20px;
    background: rgba(15, 23, 42, 0.6);
    color: #e5e7eb;
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    box-shadow: 0 10px 25px rgba(15, 23, 42, 0.7);
}
.btn-filter-chip i { font-size: 1.1rem; }
.btn-filter-chip:hover { background: rgba(15, 23, 42, 0.9); }

.btn-filter-chip.primary {
    background: #1d4ed8;
}

/* ===== TABLA DE EVENTOS ===== */
.events-table-wrapper {
    background: rgba(15, 23, 42, 0.7);
    border-radius: 18px;
    padding: 16px 18px 18px;
    box-shadow: 0 18px 35px rgba(0, 0, 0, 0.55);
}

.events-table {
    width: 100%;
    border-collapse: collapse;
}

.events-table thead th {
    text-align: left;
    padding: 10px 12px;
    font-size: 0.95rem;
    font-weight: 600;
    color: #e5e7eb;
    border-bottom: 1px solid rgba(148, 163, 184, 0.4);
}

.events-table tbody tr {
    transition: background 0.18s ease, transform 0.08s ease;
}

.events-table tbody tr:hover {
    background: rgba(15, 23, 42, 0.8);
    transform: translateY(-1px);
}

.events-table tbody td {
    padding: 11px 12px;
    font-size: 0.9rem;
    border-bottom: 1px solid rgba(30, 64, 175, 0.4);
    vertical-align: middle;
}

.events-table tbody tr:last-child td {
    border-bottom: none;
}

.events-table .col-name { width: 40%; }
.events-table .col-date { width: 20%; }
.events-table .col-action { width: 25%; text-align: right; }

/* Fila seleccionada (para mostrar info a la derecha) */
.events-table tbody tr.selected {
    background: rgba(37, 99, 235, 0.45);
}

/* Botón UNIRME */
.btn-join-event {
    border-radius: 999px;
    border: none;
    padding: 7px 18px;
    background: #e5e7eb;
    color: #111827;
    font-size: 0.88rem;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    box-shadow: 0 9px 22px rgba(15, 23, 42, 0.8);
}
.btn-join-event i { font-size: 0.95rem; }
.btn-join-event span { font-weight: 600; }
.btn-join-event:hover {
    background: #f9fafb;
}

/* Lista de equipos inscritos */
.enrolled-teams-list {
    margin-top: 10px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.enrolled-team-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    background: linear-gradient(135deg, #22c55e, #16a34a);
    border-radius: 999px;
    font-size: 0.8rem;
    color: #ffffff;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.3);
    width: fit-content;
}

.enrolled-team-badge i {
    font-size: 0.85rem;
}

.btn-remove-team {
    background: rgba(239, 68, 68, 0.9);
    border: none;
    border-radius: 50%;
    width: 22px;
    height: 22px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #ffffff;
    margin-left: 6px;
    transition: all 0.2s ease;
    padding: 0;
}

.btn-remove-team:hover {
    background: #dc2626;
    transform: scale(1.1);
}

.btn-remove-team i {
    font-size: 0.9rem;
    font-weight: bold;
}

/* ===== PANEL DERECHA INFORMACIÓN ===== */
.events-info-panel {
    background: rgba(15, 23, 42, 0.75);
    border-radius: 18px;
    padding: 16px 18px;
    box-shadow: 0 18px 35px rgba(0, 0, 0, 0.55);
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.events-info-title {
    font-size: 0.9rem;
    letter-spacing: 0.16em;
    text-transform: uppercase;
    color: #bfdbfe;
    margin-bottom: 6px;
}

.events-info-label {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.14em;
    color: #9ca3af;
    margin-top: 8px;
    margin-bottom: 4px;
}

.events-info-text {
    font-size: 0.9rem;
    color: #e5e7eb;
    min-height: 38px;
}

/* Responsive */
@media (max-width: 1024px) {
    .events-layout {
        grid-template-columns: minmax(0, 1fr);
    }
}
</style>
@endpush

@section('content')
<div class="lista-eventos-fullscreen">
    <div class="panel-card">

        {{-- ===== SIDEBAR ===== --}}
        <aside class="panel-sidebar" id="sidebar">
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
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.participante') }}">
                                <i class="bi bi-house-door"></i><span>Inicio</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
    <a class="sidebar-link {{ request()->routeIs('panel.eventos') ? 'active' : '' }}"
       href="{{ route('panel.eventos') }}">
        <i class="bi bi-calendar-event"></i>
        <span>Eventos</span>
    </a>
</li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.perfil') }}"><i class="bi bi-person"></i> <span>Mi perfil</span></a></li>


                    </ul>
                </div>

                <div>
                    <div class="sidebar-section-title">Equipo</div>
                    <ul class="sidebar-nav">
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.mi-equipo') }}">
                                <i class="bi bi-people"></i><span>Mi equipo</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.lista-equipo') }}">
                                <i class="bi bi-list-ul"></i><span>Lista de equipo</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.teams.create') }}">
                                <i class="bi bi-plus-circle"></i><span>Crear equipo</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
    <a class="sidebar-link {{ request()->routeIs('panel.lista-eventos') ? 'active' : '' }}"
       href="{{ route('panel.lista-eventos') }}">
        <i class="bi bi-calendar2-week"></i>
        <span>Lista eventos</span>
    </a>
</li>
<li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.submission') }}">
                                <i class="bi bi-bookmark-arrow-up"></i><span>Submisión del proyecto</span>
                            </a>
                        </li>
                    </ul>
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

        {{-- ===== CONTENIDO PRINCIPAL ===== --}}
        <main class="panel-main">
            <div class="panel-main-inner">

                <header class="panel-header">
                    <h1 class="panel-title">Lista eventos</h1>
                    <div class="user-badge">
                        <i class="bi bi-person-circle"></i>
                        <span>{{ auth()->check() ? auth()->user()->name : 'Usuario' }}</span>
                    </div>
                </header>

                <div class="events-layout">
                    {{-- COLUMNA IZQUIERDA: BUSCADOR + TABLA --}}
                    <section>
                        <div class="events-search-area">
                            <div class="events-search-input-wrapper">
                                <span class="search-icon"><i class="bi bi-search"></i></span>
                                <input type="text" id="eventSearchInput" placeholder="Buscar Evento">
                            </div>

                            <div class="events-filters">
                                <button type="button" class="btn-filter-chip" id="btnFilter">
                                    <i class="bi bi-funnel"></i>
                                    <span>Filter</span>
                                    <i class="bi bi-caret-down-fill"></i>
                                </button>


                            </div>
                        </div>

                        <section class="events-table-wrapper">
                            <table class="events-table" id="eventsTable">
                                <thead>
                                    <tr>
                                        <th class="col-name">Nombre</th>
                                        <th class="col-date">Inicio</th>
                                        <th class="col-date">Fin</th>
                                        <th class="col-action"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($events as $event)
                                        <tr class="event-row"
                                            data-title="{{ $event->title }}"
                                            data-start="{{ optional($event->start_date)->format('Y-m-d') }}"
                                            data-end="{{ optional($event->end_date)->format('Y-m-d') }}"
                                            data-description="{{ $event->description }}"
                                            data-place="{{ $event->place }}"
                                            data-capacity="{{ $event->capacity ?? '∞' }}"
                                            data-enrolled="{{ $event->teams()->count() }}"
                                            data-slots="{{ $event->availableSlots() }}">
                                            <td class="col-name">
                                                {{ $event->title }}
                                                @if($event->isFull())
                                                    <span class="badge bg-warning text-dark ms-2" style="font-size: 0.7rem;">LLENO</span>
                                                @elseif(is_numeric($event->availableSlots()) && $event->availableSlots() <= 3)
                                                    <span class="badge bg-info text-dark ms-2" style="font-size: 0.7rem;">{{ $event->availableSlots() }} cupos</span>
                                                @endif
                                            </td>
                                            <td class="col-date">
                                                {{ optional($event->start_date)->format('d/m/Y') }}
                                            </td>
                                            <td class="col-date">
                                                {{ optional($event->end_date)->format('d/m/Y') }}
                                            </td>
                                            <td class="col-action">
                                                @if($event->status === 'activo')
                                                    {{-- Evento ya comenzó, no acepta inscripciones --}}
                                                    <button type="button" 
                                                            class="btn-join-event" 
                                                            disabled
                                                            style="opacity: 0.6; cursor: not-allowed; background: linear-gradient(135deg, #059669, #047857);">
                                                        <i class="bi bi-play-circle-fill"></i>
                                                        <span>En curso</span>
                                                    </button>
                                                @elseif($event->isFull())
                                                    {{-- Evento lleno --}}
                                                    <button type="button" 
                                                            class="btn-join-event" 
                                                            disabled
                                                            style="opacity: 0.5; cursor: not-allowed;">
                                                        <i class="bi bi-x-circle-fill"></i>
                                                        <span>Lleno</span>
                                                    </button>
                                                @elseif($event->status === 'publicado')
                                                    {{-- Evento publicado, acepta inscripciones --}}
                                                    <button type="button" 
                                                            class="btn-join-event" 
                                                            onclick="openEventJoinModal({{ $event->id }}, '{{ addslashes($event->title) }}'); event.stopPropagation();">
                                                        <i class="bi bi-people-fill"></i>
                                                        <span>Unirme</span>
                                                        <i class="bi bi-chevron-right"></i>
                                                    </button>
                                                @else
                                                    {{-- Otros estados --}}
                                                    <button type="button" 
                                                            class="btn-join-event" 
                                                            disabled
                                                            style="opacity: 0.5; cursor: not-allowed;">
                                                        <i class="bi bi-lock-fill"></i>
                                                        <span>No disponible</span>
                                                    </button>
                                                @endif
                                                
                                                {{-- Equipos inscritos --}}
                                                @if($event->teams && $event->teams->count() > 0)
                                                    <div class="enrolled-teams-list">
                                                        @foreach($event->teams as $team)
                                                            @php
                                                                $isLeader = auth()->id() === $team->leader_id;
                                                            @endphp
                                                            <div class="enrolled-team-badge">
                                                                <i class="bi bi-check-circle-fill"></i>
                                                                <span>{{ $team->name }}</span>
                                                                @if($isLeader)
                                                                    <form action="{{ route('panel.events.leave', ['event' => $event->id, 'team' => $team->id]) }}" 
                                                                          method="POST" 
                                                                          style="display: inline; margin: 0;"
                                                                          onsubmit="return confirm('¿Estás seguro de que deseas retirar el equipo {{ $team->name }} de este evento?');">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn-remove-team" title="Retirar equipo">
                                                                            <i class="bi bi-x"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" style="padding: 14px 12px; text-align: center;">
                                                No hay eventos disponibles por el momento.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </section>
                    </section>

                    {{-- COLUMNA DERECHA: PANEL DE INFORMACIÓN --}}
                    <aside class="events-info-panel">
                        <div class="events-info-title">INFORMACIÓN</div>

                        <div class="events-info-label">Descripción</div>
                        <div class="events-info-text" id="infoEventDescription">
                            Selecciona un evento de la tabla para ver su descripción.
                        </div>

                        <div class="events-info-label">Lugar</div>
                        <div class="events-info-text" id="infoEventPlace">
                            —
                        </div>

                        <div class="events-info-label">Disponibilidad</div>
                        <div class="events-info-text" id="infoEventSlots">
                            —
                        </div>
                    </aside>
                </div>
            </div>
        </main>

    </div>
</div>

{{-- Modal para seleccionar equipo --}}
<div class="event-join-modal-overlay" id="eventJoinModal">
    <div class="event-join-modal-content">
        <div class="event-join-modal-header">
            <h3>Inscribir equipo al evento</h3>
            <button class="event-join-modal-close" onclick="closeEventJoinModal()">&times;</button>
        </div>

        <div class="event-join-modal-body">
            <p style="color: #cbd5e1; margin-bottom: 20px;">
                <strong id="eventNameDisplay"></strong>
            </p>

            <p style="color: #94a3b8; font-size: 0.9rem; margin-bottom: 20px;">
                Selecciona uno de tus equipos (donde eres líder) para inscribirlo en este evento:
            </p>

            <form id="eventJoinForm" method="POST">
                @csrf
                <div class="team-selection-list" id="teamSelectionList">
                    {{-- Los equipos se cargarán aquí con JavaScript --}}
                    <p style="color: #94a3b8; text-align: center; padding: 20px;">
                        Cargando equipos...
                    </p>
                </div>

                <button type="submit" class="event-join-submit-btn" id="submitEventJoin" disabled>
                    <i class="bi bi-check-circle"></i> Inscribir equipo
                </button>
            </form>
        </div>
    </div>
</div>

<style>
/* Modal de selección de equipo */
.event-join-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(6px);
    z-index: 2000;
    display: none;
    justify-content: center;
    align-items: center;
}

.event-join-modal-overlay.active {
    display: flex;
}

.event-join-modal-content {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    border: 1px solid rgba(148, 163, 184, 0.3);
    border-radius: 20px;
    padding: 32px;
    width: 90%;
    max-width: 550px;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.event-join-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(148, 163, 184, 0.2);
}

.event-join-modal-header h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #f1f5f9;
    margin: 0;
}

.event-join-modal-close {
    background: none;
    border: none;
    font-size: 2rem;
    color: #94a3af;
    cursor: pointer;
    padding: 0;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}

.event-join-modal-close:hover {
    background: rgba(148, 163, 184, 0.15);
    color: #e2e8f0;
}

.event-join-modal-body {
    padding: 0;
}

.team-selection-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 24px;
    max-height: 300px;
    overflow-y: auto;
}

.team-option {
    cursor: pointer;
}

.team-option input[type="radio"] {
    display: none;
}

.team-option input[type="radio"]:checked + .team-card {
    background: linear-gradient(135deg, #4c1d95, #7c3aed);
    border-color: #a78bfa;
    box-shadow: 0 0 20px rgba(124, 58, 237, 0.4);
    transform: translateY(-2px);
}

.team-card {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: rgba(15, 23, 42, 0.6);
    border: 2px solid rgba(148, 163, 184, 0.3);
    border-radius: 12px;
    transition: all 0.3s ease;
    color: #cbd5e1;
}

.team-card:hover {
    background: rgba(15, 23, 42, 0.9);
    border-color: rgba(148, 163, 184, 0.5);
}

.team-card i {
    font-size: 2rem;
    color: #a78bfa;
}

.team-info {
    flex: 1;
}

.team-name {
    font-size: 1.05rem;
    font-weight: 600;
    color: #e2e8f0;
    margin-bottom: 4px;
}

.team-members {
    font-size: 0.85rem;
    color: #94a3b8;
}

.event-join-submit-btn {
    width: 100%;
    padding: 14px 24px;
    background: linear-gradient(135deg, #4c1d95, #7c3aed);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 0 15px rgba(124, 58, 237, 0.3);
}

.event-join-submit-btn:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 0 25px rgba(124, 58, 237, 0.5);
    background: linear-gradient(135deg, #5b21b6, #8b5cf6);
}

.event-join-submit-btn:active:not(:disabled) {
    transform: scale(0.98);
}

.event-join-submit-btn:disabled {
    background: #6b7280;
    cursor: not-allowed;
    box-shadow: none;
    opacity: 0.6;
}

.event-join-submit-btn i {
    font-size: 1.2rem;
}
</style>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const rows = Array.from(document.querySelectorAll('.event-row'));
    const searchInput = document.getElementById('eventSearchInput');
    const infoDesc = document.getElementById('infoEventDescription');
    const infoPlace = document.getElementById('infoEventPlace');
    const infoSlots = document.getElementById('infoEventSlots');

    // Al hacer clic en una fila → mostrar info a la derecha
    rows.forEach(row => {
        row.addEventListener('click', () => {
            rows.forEach(r => r.classList.remove('selected'));
            row.classList.add('selected');

            const desc = row.dataset.description || 'Sin descripción.';
            const place = row.dataset.place || 'Sin lugar definido.';
            const capacity = row.dataset.capacity || '∞';
            const enrolled = row.dataset.enrolled || '0';
            const slots = row.dataset.slots || '—';

            infoDesc.textContent = desc;
            infoPlace.textContent = place;
            
            // Mostrar disponibilidad con colores
            let slotsText = `${enrolled} / ${capacity} equipos inscritos`;
            if (slots !== '∞' && slots !== '—') {
                const slotsNum = parseInt(slots);
                if (slotsNum === 0) {
                    slotsText += ' - ⚠️ LLENO';
                } else if (slotsNum <= 3) {
                    slotsText += ` - ⚠️ Quedan ${slotsNum} cupos`;
                } else {
                    slotsText += ` - ✅ ${slotsNum} cupos disponibles`;
                }
            } else if (slots === '∞') {
                slotsText += ' - ✅ Cupos ilimitados';
            }
            
            infoSlots.textContent = slotsText;
        });
    });

    // Buscador por nombre de evento
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const q = this.value.toLowerCase();

            rows.forEach(row => {
                const title = (row.dataset.title || '').toLowerCase();
                row.style.display = title.includes(q) ? '' : 'none';
            });
        });
    }

    // Botón "Last 7 days" (filtro sencillo por fechas, opcional)
    const btnLast7 = document.getElementById('btnLast7');
    if (btnLast7) {
        btnLast7.addEventListener('click', () => {
            const today = new Date();
            const limit = new Date(today);
            limit.setDate(today.getDate() - 7);

            let active = btnLast7.classList.toggle('active-filter');

            rows.forEach(row => {
                const startStr = row.dataset.start;
                if (!startStr) return;

                const d = new Date(startStr);
                const inRange = d >= limit && d <= today;

                if (active) {
                    row.style.display = inRange ? '' : 'none';
                } else {
                    row.style.display = ''; // quitamos filtro
                }
            });
        });
    }
});

// Variables globales para el modal de inscripción
let currentEventId = null;
let userLeaderTeams = [];

// Función para abrir el modal de inscripción
async function openEventJoinModal(eventId, eventTitle) {
    currentEventId = eventId;
    
    // Actualizar título del evento
    document.getElementById('eventNameDisplay').textContent = eventTitle;
    
    // Mostrar modal
    document.getElementById('eventJoinModal').classList.add('active');
    
    // Cargar equipos donde el usuario es líder
    await loadUserLeaderTeams();
}

// Función para cerrar el modal
function closeEventJoinModal() {
    document.getElementById('eventJoinModal').classList.remove('active');
    currentEventId = null;
    userLeaderTeams = [];
}

// Cargar equipos donde el usuario es líder
async function loadUserLeaderTeams() {
    const container = document.getElementById('teamSelectionList');
    const submitBtn = document.getElementById('submitEventJoin');
    
    try {
        // Enviar event_id para filtrar equipos ya inscritos
        const response = await fetch(`/api/user/leader-teams?event_id=${currentEventId}`);
        const data = await response.json();
        
        userLeaderTeams = data.teams || [];
        
        // Filtrar equipos disponibles (no inscritos)
        const availableTeams = userLeaderTeams.filter(team => !team.is_enrolled);
        const enrolledTeams = userLeaderTeams.filter(team => team.is_enrolled);
        
        if (userLeaderTeams.length === 0) {
            container.innerHTML = `
                <div style="text-align: center; padding: 30px; color: #94a3b8;">
                    <i class="bi bi-exclamation-circle" style="font-size: 3rem; color: #f59e0b; margin-bottom: 10px;"></i>
                    <p style="font-size: 1rem; margin-bottom: 8px;">No tienes equipos completos</p>
                    <p style="font-size: 0.85rem;">Solo puedes inscribir equipos con 4 miembros completos.</p>
                    <p style="font-size: 0.85rem; margin-top: 8px;">Completa tu equipo o espera a que los miembros acepten las invitaciones.</p>
                </div>
            `;
            submitBtn.disabled = true;
            return;
        }
        
        if (availableTeams.length === 0 && enrolledTeams.length > 0) {
            container.innerHTML = `
                <div style="text-align: center; padding: 30px; color: #94a3b8;">
                    <i class="bi bi-check-circle-fill" style="font-size: 3rem; color: #10b981; margin-bottom: 10px;"></i>
                    <p style="font-size: 1rem; margin-bottom: 8px;">Todos tus equipos ya están inscritos</p>
                    <p style="font-size: 0.85rem;">Ya has inscrito todos tus equipos completos en este evento.</p>
                </div>
            `;
            submitBtn.disabled = true;
            return;
        }
        
        // Renderizar equipos disponibles
        container.innerHTML = '';
        
        // Mostrar equipos disponibles primero
        availableTeams.forEach((team, index) => {
            const teamOption = document.createElement('label');
            teamOption.className = 'team-option';
            teamOption.innerHTML = `
                <input type="radio" name="team_id" value="${team.id}" ${index === 0 ? 'checked' : ''} onchange="enableSubmitButton()">
                <div class="team-card">
                    <i class="bi bi-people-fill"></i>
                    <div class="team-info">
                        <div class="team-name">${team.name}</div>
                        <div class="team-members">${team.members_count} miembros</div>
                    </div>
                </div>
            `;
            container.appendChild(teamOption);
        });
        
        // Mostrar equipos ya inscritos (deshabilitados)
        if (enrolledTeams.length > 0) {
            const separator = document.createElement('div');
            separator.style.cssText = 'margin: 20px 0; padding: 10px; background: rgba(16, 185, 129, 0.1); border-radius: 8px; text-align: center;';
            separator.innerHTML = '<small style="color: #10b981; font-weight: 600;"><i class="bi bi-check-circle-fill"></i> Equipos ya inscritos:</small>';
            container.appendChild(separator);
            
            enrolledTeams.forEach((team) => {
                const teamOption = document.createElement('label');
                teamOption.className = 'team-option';
                teamOption.style.opacity = '0.5';
                teamOption.style.cursor = 'not-allowed';
                teamOption.innerHTML = `
                    <input type="radio" name="team_id" value="${team.id}" disabled>
                    <div class="team-card" style="background: rgba(16, 185, 129, 0.1); border-color: #10b981;">
                        <i class="bi bi-check-circle-fill" style="color: #10b981;"></i>
                        <div class="team-info">
                            <div class="team-name">${team.name}</div>
                            <div class="team-members" style="color: #10b981;">Ya inscrito en este evento</div>
                        </div>
                    </div>
                `;
                container.appendChild(teamOption);
            });
        }
        
        // Habilitar botón si hay equipos disponibles
        if (availableTeams.length > 0) {
            submitBtn.disabled = false;
        } else {
            submitBtn.disabled = true;
        }
        
    } catch (error) {
        console.error('Error al cargar equipos:', error);
        container.innerHTML = `
            <div style="text-align: center; padding: 30px; color: #ef4444;">
                <i class="bi bi-exclamation-triangle" style="font-size: 3rem; margin-bottom: 10px;"></i>
                <p>Error al cargar los equipos. Intenta de nuevo.</p>
            </div>
        `;
        submitBtn.disabled = true;
    }
}

// Habilitar botón de submit
function enableSubmitButton() {
    document.getElementById('submitEventJoin').disabled = false;
}

// Manejar envío del formulario
document.getElementById('eventJoinForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const selectedTeam = document.querySelector('input[name="team_id"]:checked');
    if (!selectedTeam) {
        alert('Por favor selecciona un equipo');
        return;
    }
    
    const teamId = selectedTeam.value;
    
    // Crear formulario y enviarlo
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/eventos/${currentEventId}/join`;
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]');
    if (csrfToken) {
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken.content;
        form.appendChild(csrfInput);
    }
    
    const teamInput = document.createElement('input');
    teamInput.type = 'hidden';
    teamInput.name = 'team_id';
    teamInput.value = teamId;
    form.appendChild(teamInput);
    
    document.body.appendChild(form);
    form.submit();
});

// Cerrar modal al hacer clic fuera
document.getElementById('eventJoinModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEventJoinModal();
    }
});
</script>
@endpush
