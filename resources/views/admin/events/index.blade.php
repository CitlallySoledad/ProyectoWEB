@extends('layouts.admin')

@section('title', 'Eventos')

@section('content')
<div class="admin-page-wrapper">
    <div class="admin-page-card">

        {{-- SIDEBAR IZQUIERDA --}}
        <div class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-back">
                <i class="bi bi-chevron-left"></i>
            </a>

            <div class="admin-sidebar-icon active">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="admin-sidebar-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="admin-sidebar-icon">
                <i class="bi bi-grid-1x2"></i>
            </div>
            <div class="admin-sidebar-icon">
                <i class="bi bi-person-badge"></i>
            </div>
            <!-- Icono de salir (Logout) -->
                <div class="admin-sidebar-icon">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-left"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
        </div>

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="admin-page-main">
            <div class="admin-page-inner">

                <div class="admin-page-header">
                    <h1 class="admin-page-title">Eventos</h1>

                    <div class="admin-page-user">
                        <i class="bi bi-person-circle"></i>
                        <span>Admin</span>
                    </div>
                </div>

                <div class="admin-page-search-row">
                    <div class="admin-page-search-input-wrapper">
                        <i class="bi bi-search me-2 text-muted"></i>
                        <input type="text" placeholder="Buscar Evento">
                    </div>

                    <button class="admin-page-create-btn"
                            onclick="window.location='{{ route('admin.events.create') }}'">
                        Crear Evento
                    </button>
                </div>

                <div class="admin-list">
                    @foreach($events as $event)
                        <div class="admin-list-item">
                            <div class="admin-list-item-title">{{ $event->title }}</div>
                            <div class="admin-list-item-right">
                                <button class="admin-list-edit-btn"
                                        onclick="window.location='{{ route('admin.events.edit', $event->id) }}'">
                                    Editar
                                </button>

                                <form action="{{ route('admin.events.destroy', $event->id) }}"
                                      method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="admin-list-edit-btn"
                                            onclick="return confirm('¿Eliminar este evento?')">
                                        Eliminar
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>

    </div>
</div>
@endsection


