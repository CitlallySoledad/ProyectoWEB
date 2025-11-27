@extends('layouts.admin')

@section('title', 'Panel de administración')

@section('content')
<div class="dashboard-wrapper">

    <div class="dashboard-topbar">
        <div class="dashboard-topbar-left">
            <div class="dashboard-logo">
                IT
            </div>
            <div>
                <div class="dashboard-title">
                    Instituto Tecnológico de Oaxaca
                </div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-3">
            <span>{{ auth()->user()->name ?? 'Admin' }}</span>
            <div class="dashboard-user-icon">
                <i class="bi bi-person-fill"></i>
            </div>

            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-light">
                    Cerrar sesión
                </button>
            </form>
        </div>
    </div>

    <div class="dashboard-content">
        <div class="dashboard-cards">

            {{-- Eventos --}}
            <a href="{{ route('admin.events.index') }}" class="text-decoration-none text-white">
                <div class="dashboard-card">
                    <div class="dashboard-card-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <div class="dashboard-card-title">
                        Eventos
                    </div>
                </div>
            </a>

            {{-- Equipos --}}
            <a href="{{ route('admin.teams.index') }}" class="text-decoration-none text-white">
                <div class="dashboard-card">
                    <div class="dashboard-card-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <div class="dashboard-card-title">
                        Equipos
                    </div>
                </div>
            </a>

            {{-- Usuarios (aún sin ruta propia) --}}
            <div class="dashboard-card">
                <div class="dashboard-card-icon">
                    <i class="bi bi-person-lines-fill"></i>
                </div>
                <div class="dashboard-card-title">
                    Usuarios
                </div>
            </div>

        </div>
    </div>

</div>
@endsection

