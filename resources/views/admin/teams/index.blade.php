@extends('layouts.admin-panel')

@section('title', 'Equipos')

@push('styles')
<style>
/* ===== COLORES BLANCOS PARA TEXTO ===== */
.h4, .mb-0, .admin-card-title, .admin-table th, .admin-table td {
    color: #fff !important;
}

/* ===== PAGINACIÓN COMPACTA EQUIPOS ===== */
.teams-pagination {
    margin: 24px auto 8px;
    padding: 8px 16px;
    border-radius: 999px;
    background: rgba(37, 99, 235, 0.1);
    display: flex;
    align-items: center;
    gap: 18px;
    width: auto;
    max-width: 100%;
    font-size: 0.85rem;
    color: #e5e7eb;
}

.teams-pagination-info {
    white-space: nowrap;
    opacity: 0.85;
    font-weight: 500;
}

.teams-pagination-pages {
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Botones de página */
.page-number,
.page-arrow {
    min-width: 32px;
    height: 32px;
    padding: 0 10px;
    border-radius: 999px;
    border: 1px solid #2563eb;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    text-decoration: none;
    color: #e5e7eb;
    background: transparent;
    transition: background 0.18s ease, color 0.18s ease, transform 0.12s ease;
    cursor: pointer;
}

.page-number:hover,
.page-arrow:hover {
    background: #2563eb;
    color: #ffffff;
    transform: translateY(-1px);
}

/* Página actual */
.page-number.active {
    background: #2563eb;
    color: #ffffff;
    border-color: #1d4ed8;
    font-weight: 600;
}

/* Deshabilitados */
.page-arrow.disabled {
    opacity: 0.35;
    border-color: #94a3b8;
    cursor: default;
    pointer-events: none;
}
</style>
@endpush

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Equipos</h1>
        <a href="{{ route('admin.teams.create') }}" class="admin-btn-primary text-decoration-none">
            <i class="bi bi-plus-circle me-1"></i> Crear equipo
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-title">Lista de equipos</div>

        @if($teams->isEmpty())
            <p class="mb-0">No hay equipos registrados.</p>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Integrantes</th>
                        <th style="width: 180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teams as $team)
                        <tr>
                            <td>{{ $team->name }}</td>

                            {{-- 🔹 Mostrar integrantes reales --}}
                            <td>
                                @php
                                    $members = $team->members ?? collect();
                                    $printMembers = $members->map(function($m) {
                                        $role = $m->pivot->role ?? null;
                                        $labelRole = null;
                                        if ($role === 'lider') $labelRole = 'Líder';
                                        elseif ($role === 'backend') $labelRole = 'Backend';
                                        elseif ($role === 'frontend') $labelRole = 'Front-end';
                                        elseif ($role === 'disenador') $labelRole = 'Diseñador';
                                        elseif ($role) $labelRole = ucfirst(str_replace('_',' ',$role));

                                        return trim($m->name . ($labelRole ? " ({$labelRole})" : ''));
                                    })->filter()->toArray();
                                @endphp

                                {{ count($printMembers) ? implode(', ', $printMembers) : '-' }}
                            </td>

                            <td>
                                <a href="{{ route('admin.teams.edit', $team) }}"
                                   class="btn btn-sm btn-light rounded-pill me-1">
                                    Editar
                                </a>

                                <form action="{{ route('admin.teams.destroy', $team) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este equipo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger rounded-pill">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- PAGINACIÓN COMPACTA --}}
            @if ($teams->hasPages())
                <div class="teams-pagination">
                    <span class="teams-pagination-info">
                        Mostrando {{ $teams->firstItem() }} - {{ $teams->lastItem() }} de {{ $teams->total() }} equipos
                    </span>

                    <div class="teams-pagination-pages">
                        {{-- Flecha Anterior --}}
                        @if ($teams->onFirstPage())
                            <span class="page-arrow disabled">&laquo;</span>
                        @else
                            <a href="{{ $teams->previousPageUrl() }}" class="page-arrow">&laquo;</a>
                        @endif

                        {{-- Números de página --}}
                        @foreach ($teams->getUrlRange(1, $teams->lastPage()) as $page => $url)
                            @if ($page == $teams->currentPage())
                                <span class="page-number active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-number">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Flecha Siguiente --}}
                        @if ($teams->hasMorePages())
                            <a href="{{ $teams->nextPageUrl() }}" class="page-arrow">&raquo;</a>
                        @else
                            <span class="page-arrow disabled">&raquo;</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>

@endsection



