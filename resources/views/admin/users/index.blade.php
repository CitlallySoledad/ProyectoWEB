@extends('layouts.admin')

@section('title', 'Usuarios')

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
            <div class="admin-sidebar-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="admin-sidebar-icon">
                <i class="bi bi-grid-1x2"></i>
            </div>
            <div class="admin-sidebar-icon active">
                <i class="bi bi-person-badge"></i>
            </div>
        </div>

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="admin-page-main">
            <div class="admin-page-inner">

                <div class="admin-page-header">
                    <h1 class="admin-page-title">Usuarios</h1>

                    <div class="admin-page-user">
                        <i class="bi bi-person-circle"></i>
                        <span>Admin</span>
                    </div>
                </div>

                <div class="admin-page-search-row">
                    <div class="admin-page-search-input-wrapper">
                        <i class="bi bi-search me-2 text-muted"></i>
                        <input type="text" placeholder="Buscar usuario (visual)">
                    </div>

                    <button class="admin-page-create-btn"
                            onclick="window.location='{{ route('admin.users.create') }}'">
                        Crear Usuario
                    </button>
                </div>

                <div class="admin-list">
                    @forelse($users as $user)
                        <div class="admin-list-item">
                            <div class="admin-list-item-title">
                                {{ $user->name }}
                                <span class="d-block" style="font-size: 0.8rem; color:#cbd5f5;">
                                    {{ $user->email }}
                                </span>
                            </div>
                            <div class="admin-list-item-right">
                                <span style="font-size: 0.8rem;">
                                    Creado: {{ $user->created_at->format('d/m/Y') }}
                                </span>
                                {{-- Aquí luego podrías agregar editar/eliminar --}}
                            </div>
                        </div>
                    @empty
                        <p>No hay usuarios registrados.</p>
                    @endforelse
                </div>

            </div>
        </div>

    </div>
</div>
@endsection
