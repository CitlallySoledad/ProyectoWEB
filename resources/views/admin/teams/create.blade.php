@extends('layouts.admin')

@section('title', 'Crear equipo')

@section('content')
<div class="admin-page-wrapper">
    <div class="admin-page-card">

        {{-- SIDEBAR IZQUIERDA --}}
        <div class="admin-sidebar">
            <a href="{{ route('admin.teams.index') }}" class="admin-sidebar-back">
                <i class="bi bi-chevron-left"></i>
            </a>

            <div class="admin-sidebar-icon">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="admin-sidebar-icon active">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="admin-sidebar-icon">
                <i class="bi bi-grid-1x2"></i>
            </div>
            <div class="admin-sidebar-icon">
                <i class="bi bi-person-badge"></i>
            </div>
        </div>

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="admin-page-main">
            <div class="admin-page-user">
    <i class="bi bi-person-circle"></i>
    <span>
        @if (request()->routeIs('panel.teams.*'))
            {{ auth()->check() ? auth()->user()->name : 'Usuario' }}
        @else
            Admin
        @endif
    </span>
</div>

            </div>

            <h1 class="admin-form-title">Crear equipo</h1>

            <form action="{{ route('admin.teams.store') }}" method="POST">
                @csrf

                {{-- Nombre de equipo --}}
                <div class="admin-form-row">
                    <label class="admin-form-label" for="name">Nombre de equipo</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="admin-form-input"
                        placeholder="Nombre de equipo"
                        value="{{ old('name') }}"
                    >
                </div>

                {{-- Integrantes / Roles --}}
                <div class="admin-team-members-header">
                    <span>Integrantes</span>
                    <span>Rol</span>
                </div>

                {{-- Líder --}}
                <div class="admin-team-member-row">
                    <div class="admin-team-member-name">
                        <input
                            type="text"
                            name="members[0][name]"
                            class="admin-form-input"
                            placeholder="Nombre Usuario"
                        >
                    </div>
                    <div class="admin-team-member-role">
                        <div class="admin-role-label">Líder</div>
                    </div>
                </div>

                {{-- Pendiente 1 --}}
                <div class="admin-team-member-row">
                    <div class="admin-team-member-name">
                        <input
                            type="text"
                            name="members[1][name]"
                            class="admin-form-input"
                            placeholder="Pendiente"
                        >
                    </div>
                    <div class="admin-team-member-role">
                        <select name="members[1][role]" class="admin-form-select-small">
                            <option value="">Sin asignar</option>
                            <option value="participante">Participante</option>
                            <option value="mentor">Mentor</option>
                        </select>
                    </div>
                </div>

                {{-- Pendiente 2 --}}
                <div class="admin-team-member-row">
                    <div class="admin-team-member-name">
                        <input
                            type="text"
                            name="members[2][name]"
                            class="admin-form-input"
                            placeholder="Pendiente"
                        >
                    </div>
                    <div class="admin-team-member-role">
                        <select name="members[2][role]" class="admin-form-select-small">
                            <option value="">Sin asignar</option>
                            <option value="participante">Participante</option>
                            <option value="mentor">Mentor</option>
                        </select>
                    </div>
                </div>

                {{-- Pendiente 3 --}}
                <div class="admin-team-member-row">
                    <div class="admin-team-member-name">
                        <input
                            type="text"
                            name="members[3][name]"
                            class="admin-form-input"
                            placeholder="Pendiente"
                        >
                    </div>
                    <div class="admin-team-member-role">
                        <select name="members[3][role]" class="admin-form-select-small">
                            <option value="">Sin asignar</option>
                            <option value="participante">Participante</option>
                            <option value="mentor">Mentor</option>
                        </select>
                    </div>
                </div>

                {{-- Botones inferiores --}}
                <div class="admin-form-footer">
                    <a href="{{ route('admin.teams.index') }}" class="btn admin-btn-pill admin-btn-secondary">
                        Cancelar
                    </a>

                    <button type="submit" class="admin-btn-pill admin-btn-primary">
                        Crear equipo
                    </button>

                    <button type="button" class="admin-btn-pill admin-btn-disabled" disabled>
                        Guardar cambios
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
