@extends('layouts.admin')

@section('title', 'Evaluación del proyecto')

@push('styles')
<style>
    .eval-title {
        font-size: 2rem;
        font-weight: 600;
        color: #dbeafe;
        margin-bottom: 24px;
    }

    .eval-table-card {
        background: rgba(15, 23, 42, 0.95);
        border-radius: 22px;
        padding: 18px 22px;
        box-shadow: 0 18px 35px rgba(0, 0, 0, 0.55);
    }

    .eval-table-header,
    .eval-table-row {
        display: grid;
        grid-template-columns: 2fr 1.5fr 1fr 1.5fr;
        align-items: center;
        column-gap: 12px;
    }

    .eval-table-header {
        font-size: 0.85rem;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: #9ca3af;
        padding: 4px 4px 10px;
        border-bottom: 1px solid rgba(55, 65, 81, 0.7);
        margin-bottom: 6px;
    }

    .eval-table-row {
        margin-top: 8px;
        padding: 10px 16px;
        border-radius: 999px;
        background: #1d3557;
        box-shadow: 0 10px 25px rgba(15, 23, 42, 0.7);
        font-size: 0.92rem;
    }

    .eval-status-pill {
        display: inline-flex;
        align-items: center;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .eval-status-pill.active {
        background: rgba(52, 211, 153, 0.15);
        color: #34d399;
    }

    .eval-status-pill.inactive {
        background: rgba(248, 250, 252, 0.15);
        color: #e5e7eb;
    }

    .eval-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-end;
    }

    .eval-btn {
        border-radius: 999px;
        border: none;
        padding: 4px 12px;
        font-size: 0.8rem;
        background: #0f172a;
        color: #e5e7eb;
        cursor: pointer;
        white-space: nowrap;
    }

    .eval-btn.edit {
        background: #1d4ed8;
    }

    .eval-btn.delete {
        background: #b91c1c;
    }
</style>
@endpush

@section('content')
<div class="admin-page-wrapper">
    <div class="admin-page-card">

        {{-- SIDEBAR --}}
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
            {{-- Sección de proyectos / evaluaciones --}}
            <a href="{{ route('admin.evaluations.projects_list') }}" class="admin-sidebar-icon active">
                <i class="bi bi-grid-1x2"></i>
            </a>
            <a href="{{ route('admin.users.index') }}" class="admin-sidebar-icon">
                <i class="bi bi-person-badge"></i>
            </a>
        </div>

        {{-- CONTENIDO --}}
        <div class="admin-page-main">
            <div class="admin-page-header">
                <h1 class="eval-title">Evaluación del proyecto</h1>

                <div class="admin-page-user">
                    <i class="bi bi-person-circle"></i>
                    <span>Admin</span>
                </div>
            </div>

            <div class="mb-3 text-slate-200">
                Proyecto seleccionado: <strong>{{ $projectName }}</strong>
            </div>

            <div class="eval-table-card">
                <div class="eval-table-header">
                    <div>Nombre</div>
                    <div>Equipo</div>
                    <div>Estado</div>
                    <div>Acciones</div>
                </div>

                    @forelse ($evaluations as $evaluation)
        @php
            // Nombre del proyecto
            $name  = $evaluation->project_name ?? 'Proyecto sin nombre';

            // Equipo
            $team  = $evaluation->team ?? 'Sin equipo';

            // Estado y clase del chip
            if (!empty($evaluation->status)) {
                $status      = $evaluation->status;
                $statusClass = $status === 'Activa' ? 'active' : 'inactive';
            } else {
                $status      = 'Inactiva';
                $statusClass = 'inactive';
            }
        @endphp

        <div class="eval-table-row">
            {{-- Nombre --}}
            <div>{{ $name }}</div>

            {{-- Equipo --}}
            <div>{{ $team }}</div>

            {{-- Estado --}}
            <div>
                <span class="eval-status-pill {{ $statusClass }}">
                    {{ $status }}
                </span>
            </div>

            {{-- Acciones --}}
            <div class="eval-actions">
                @if (!empty($isDemoMode) && $isDemoMode)
                    {{-- MODO DEMO: solo diseño, sin navegación --}}
                    <button type="button" class="eval-btn" disabled>Ver</button>
                    <button type="button" class="eval-btn edit" disabled>Editar</button>
                    <button type="button" class="eval-btn delete" disabled>Eliminar</button>
                @else
                    {{-- DATOS REALES: enlazan con el controlador --}}
                    <a href="{{ route('admin.evaluations.detail', $evaluation) }}" class="eval-btn">
                        Ver
                    </a>

                    <a href="{{ route('admin.evaluations.edit', $evaluation) }}" class="eval-btn edit">
                        Editar
                    </a>

                    <form action="{{ route('admin.evaluations.destroy', $evaluation) }}"
                          method="POST"
                          style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="eval-btn delete"
                                onclick="return confirm('¿Seguro que quieres eliminar esta evaluación?');">
                            Eliminar
                        </button>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <p class="mt-3 text-slate-400">
            No hay evaluaciones registradas para este proyecto.
        </p>
    @endforelse
</div> {{-- cierre de eval-table-card --}}




                @if($evaluations->isEmpty())
                    <p class="text-muted mt-3 mb-0">No hay evaluaciones registradas.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
