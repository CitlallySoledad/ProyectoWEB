@extends('layouts.admin')

@section('title', 'Equipos')

@section('content')
<div class="admin-page-wrapper">
    <div class="admin-page-card">

        {{-- SIDEBAR IZQUIERDA --}}
        <div class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-back">
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

            <div class="admin-page-header">
                <h1 class="admin-page-title">Lista de Equipos</h1>

                <div class="admin-page-user">
                    <i class="bi bi-person-circle"></i>
                    <span>Admin</span>
                </div>
            </div>

            <div class="admin-page-search-row">
                <div class="admin-page-search-input-wrapper">
                    <i class="bi bi-search me-2 text-muted"></i>
                    <input type="text" placeholder="Buscar Equipo">
                </div>

                <button class="admin-page-create-btn"
                        onclick="window.location='{{ route('admin.teams.create') }}'">
                    Crear Equipo
                </button>
            </div>

            {{-- LISTA DE EQUIPOS DESDE BASE DE DATOS --}}
            <div class="admin-list">

                @forelse ($teams as $team)
                    <div class="admin-list-item">

                        {{-- NOMBRE DEL EQUIPO --}}
                        <div class="admin-list-item-title">
                            {{ $team->name }}
                        </div>

                        <div class="admin-list-item-right">

                            {{-- AQUI LLEVARÁ LA CANTIDAD DE MIEMBROS EN EL FUTURO --}}
                            <span class="me-3">-</span>

                            {{-- BOTÓN EDITAR --}}
                            <button class="admin-list-edit-btn"
                                onclick="window.location='{{ route('admin.teams.edit', $team->id) }}'">
                                Editar
                            </button>

                            {{-- BOTÓN ELIMINAR --}}
                            <form action="{{ route('admin.teams.destroy', $team->id) }}"
                                  method="POST"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <button class="admin-list-edit-btn"
                                        onclick="return confirm('¿Eliminar este equipo?')">
                                    Eliminar
                                </button>
                            </form>

                        </div>

                    </div>
                @empty
                    <p class="text-muted">No hay equipos registrados.</p>
                @endforelse
            </div>

        </div>

    </div>
</div>
@endsection

