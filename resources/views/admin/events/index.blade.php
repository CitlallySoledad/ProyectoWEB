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
        </div>

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="admin-page-main">

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

                <button class="admin-page-create-btn" onclick="window.location='{{ route('admin.events.create') }}'">
                    Crear Evento
                </button>
            </div>

            {{-- LISTA DE EVENTOS DESDE BD --}}
            <div class="admin-list">
                @forelse ($events as $event)
                    <div class="admin-list-item">

                        {{-- TÍTULO DEL EVENTO --}}
                        <div class="admin-list-item-title">
                            {{ $event->title }}
                        </div>

                        <div class="admin-list-item-right">

                            {{-- BOTÓN EDITAR --}}
                            <button class="admin-list-edit-btn"
                                onclick="window.location='{{ route('admin.events.edit', $event->id) }}'">
                                Editar
                            </button>

                            {{-- BOTÓN ELIMINAR --}}
                            <form action="{{ route('admin.events.destroy', $event->id) }}"
                                  method="POST"
                                  style="display:inline;">
                                @csrf
                                @method('DELETE')

                                <button class="admin-list-edit-btn"
                                        onclick="return confirm('¿Eliminar este evento?')">
                                    Eliminar
                                </button>
                            </form>

                        </div>

                    </div>
                @empty
                    <p class="text-muted mt-3">No hay eventos registrados.</p>
                @endforelse
            </div>

        </div>

    </div>
</div>
@endsection

