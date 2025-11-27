@extends('layouts.admin')

@section('title', 'Panel de administración')

@section('content')
    <div class="dashboard-wrapper">

        <div class="dashboard-topbar">
            <div class="dashboard-topbar-left">
                <div class="dashboard-logo">
                    <img src="{{ asset('imagenes/Logo ITO.png') }}" alt="Logo" style="width: 45px; height: 45px;">
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
                {{-- Evaluaciones (antes decía Usuarios) --}}
                <a href="{{ route('admin.evaluations.index') }}" class="text-decoration-none text-white">
                    <div class="dashboard-card">
                        <div class="dashboard-card-icon">
                            {{-- Puedes cambiar el icono si quieres --}}
                            <i class="bi bi-clipboard-check"></i>
                        </div>
                        <div class="dashboard-card-title">
                            Evaluaciones
                        </div>
                    </div>
                </a>


            </div>
        </div>

    </div>
@endsection
