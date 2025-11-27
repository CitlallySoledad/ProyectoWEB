@extends('layouts.admin')

@section('title', 'Editar equipo')

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
            <div class="d-flex justify-content-end mb-2">
                <div class="admin-page-user">
                    <i class="bi bi-person-circle"></i>
                    <span>Admin</span>
                </div>
            </div>

            <h1 class="admin-form-title">Editar equipo</h1>

            <form action="{{ route('admin.teams.update', $team->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Nombre de equipo --}}
                <div class="admin-form-row">
                    <label class="admin-form-label" for="name">Nombre de equipo</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        class="admin-form-input"
                        placeholder="Nombre de equipo"
                        value="{{ old('name', $team->name) }}"
                    >
                </div>

                {{-- Aquí más adelante podrías cargar integrantes desde BD --}}

                {{-- Botones inferiores --}}
                <div class="admin-form-footer">
                    <a href="{{ route('admin.teams.index') }}" class="btn admin-btn-pill admin-btn-secondary">
                        Cancelar
                    </a>

                    <button type="submit" class="admin-btn-pill admin-btn-primary">
                        Guardar cambios
                    </button>

                    <button type="button" class="admin-btn-pill admin-btn-disabled" disabled>
                        Crear equipo
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
