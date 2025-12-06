@extends('layouts.admin')

@section('title', 'Lista de proyectos a evaluar')

@push('styles')
<style>
    .projects-page-title {
        font-size: 1.8rem;
        font-weight: 600;
    }

    .projects-page-subtitle {
        font-size: 0.95rem;
        color: #cbd5f5;
        margin-top: 4px;
    }

    .projects-filters-row {
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 24px;
        flex-wrap: wrap;
    }

    .projects-filter-btn,
    .projects-time-btn {
        border-radius: 14px;
        border: none;
        padding: 10px 18px;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(15, 23, 42, 0.75);
        color: #e5e7eb;
        box-shadow: 0 12px 25px rgba(15, 23, 42, 0.6);
        cursor: pointer;
    }

    .projects-filter-btn i,
    .projects-time-btn i {
        font-size: 1rem;
    }

    .projects-create-btn {
        border-radius: 14px;
        border: none;
        padding: 10px 26px;
        font-size: 0.9rem;
        font-weight: 500;
        background: #2563eb;
        color: #f9fafb;
        box-shadow: 0 12px 25px rgba(37, 99, 235, 0.7);
        cursor: pointer;
        margin-left: auto;
    }

    .projects-table-card {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95), rgba(15, 23, 42, 0.9));
        border-radius: 22px;
        padding: 18px 22px;
        box-shadow: 0 18px 35px rgba(0, 0, 0, 0.55);
    }

    .projects-table-header,
    .projects-table-row {
        display: grid;
        grid-template-columns: 1.4fr 1.6fr 1fr 1.6fr 1.2fr;
        align-items: center;
        column-gap: 12px;
    }

    .projects-table-header {
        font-size: 0.85rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #9ca3af;
        padding: 4px 4px 10px;
        border-bottom: 1px solid rgba(55, 65, 81, 0.7);
        margin-bottom: 6px;
    }

    .projects-table-row {
        margin-top: 8px;
        padding: 10px 16px;
        border-radius: 999px;
        background: #1d3557;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.7);
        font-size: 0.92rem;
    }

    .projects-status-pill {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .projects-status-pill.pending {
        background: rgba(251, 191, 36, 0.15);
        color: #fbbf24;
    }

    .projects-status-pill.done {
        background: rgba(52, 211, 153, 0.15);
        color: #34d399;
    }

    .projects-members-group {
        display: flex;
        align-items: center;
    }

    .projects-member-avatar {
        width: 28px;
        height: 28px;
        border-radius: 999px;
        border: 2px solid #1d3557;
        overflow: hidden;
        margin-left: -8px;
        box-shadow: 0 3px 8px rgba(15, 23, 42, 0.7);
    }

    .projects-member-avatar:first-child {
        margin-left: 0;
    }

    .projects-member-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .projects-eval-btn {
        border-radius: 999px;
        border: none;
        padding: 7px 18px;
        font-size: 0.8rem;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        font-weight: 600;
        background: #0f172a;
        color: #e5e7eb;
        cursor: pointer;
        white-space: nowrap;
    }
</style>
@endpush

@section('content')
<div class="admin-page-wrapper">
    <div class="admin-page-card">

        {{-- SIDEBAR IZQUIERDA --}}
        <div class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-back">
                <i class="bi bi-chevron-left"></i>
            </a>

            <a href="{{ route('admin.events.index') }}" class="admin-sidebar-icon">
                <i class="bi bi-calendar-event"></i>
            </a>
            <a href="{{ route('admin.evaluations.index') }}" class="admin-sidebar-icon">
                <i class="bi bi-people-fill"></i>
            </a>
            {{-- ICONO ACTIVO: LISTA DE PROYECTOS A EVALUAR --}}
            <a href="{{ route('admin.evaluations.projects_list') }}" class="admin-sidebar-icon active">
                <i class="bi bi-grid-1x2"></i>
            </a>
            <a href="{{ route('admin.users.index') }}" class="admin-sidebar-icon">
                <i class="bi bi-person-badge"></i>
            </a>

            <div class="admin-sidebar-icon">
                <a href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </div>
        </div>

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="admin-page-main">

            <div class="admin-page-header">
                <div>
                    <h1 class="projects-page-title">Lista de proyectos a evaluar</h1>
                    <p class="projects-page-subtitle">
                        Gestiona los proyectos asignados, aplica filtros y accede rápidamente a su evaluación.
                    </p>
                </div>

                <div class="admin-page-user">
                    <i class="bi bi-person-circle"></i>
                    <span>Admin</span>
                </div>
            </div>

            {{-- BUSCADOR PRINCIPAL --}}
            <form method="GET" action="{{ route('admin.evaluations.projects_list') }}" class="admin-page-search-row">
                <div class="admin-page-search-input-wrapper">
                    <i class="bi bi-search me-2 text-muted"></i>
                    <input
                    type="text"
                    name="search"
                    placeholder="Buscar Proyecto"
                    value="{{ request('search') }}"
                    >
                </div>
            </form>


            {{-- FILTROS Y BOTONES --}}
            <div class="projects-filters-row">
                <button type="button" class="projects-filter-btn">
                    <i class="bi bi-funnel-fill"></i>
                    Filter
                    <i class="bi bi-chevron-down ms-1"></i>
                </button>

                <button type="button" class="projects-time-btn">
                    Last 7 days
                    <i class="bi bi-chevron-down ms-1"></i>
                </button>

                <a href="{{ route('admin.projects.create') }}" class="projects-create-btn">
                    Crear
                </a>

            </div>

            {{-- TABLA DE PROYECTOS --}}
            <div class="projects-table-card">

                <div class="projects-table-header">
                    <div>Nombre</div>
                    <div>Proyecto</div>
                    <div>Estado</div>
                    <div>Miembros</div>
                    <div></div>
                </div>

                @forelse ($projects as $project)

                @php
    $isEvaluated = $project['status'] === 'Evaluado';

    // Clase del chip de estado
    $statusClass = $isEvaluated ? 'done' : 'pending';

    // Según el estado, cambiamos texto y ruta del botón
    if ($isEvaluated) {
        // Proyecto ya evaluado → ir a pantalla tipo tabla (Evaluación del proyecto)
        $btnLabel = 'Ver evaluación';
        $btnRoute = route('admin.evaluations.project_evaluations', urlencode($project['project_name']));
    } else {
        // Proyecto pendiente → ir al formulario para evaluarlo
        $btnLabel = 'Evaluar';
        $btnRoute = route('admin.evaluations.show', urlencode($project['project_name']));
    }
@endphp

<div class="projects-table-row">
    <div>{{ $project['team_name'] }}</div>
    <div>{{ $project['project_name'] }}</div>
    <div>
        <span class="projects-status-pill {{ $statusClass }}">
            {{ $project['status'] }}
        </span>
    </div>
    <div>
        {{-- avatares que ya tenías --}}
        <div class="projects-members-group">
            <div class="projects-member-avatar">
                <img src="{{ asset('imagenes/avatar.jpg') }}" alt="Miembro">
            </div>
            <div class="projects-member-avatar">
                <img src="{{ asset('imagenes/avatar.jpg') }}" alt="Miembro">
            </div>
            <div class="projects-member-avatar">
                <img src="{{ asset('imagenes/avatar.jpg') }}" alt="Miembro">
            </div>
        </div>
    </div>
    <div style="text-align:right;">
        <a href="{{ $btnRoute }}" class="projects-eval-btn">
            {{ strtoupper($btnLabel) }} &nbsp;›
        </a>
    </div>
</div>


                @empty
                    <p class="text-muted mt-3 mb-0">No hay proyectos registrados para evaluación.</p>
                @endforelse

            </div>

        </div>
    </div>
</div>
@endsection
