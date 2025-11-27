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

                <button class="admin-page-create-btn">
                    Crear Evento
                </button>
            </div>

            <div class="admin-list">
                <div class="admin-list-item">
                    <div class="admin-list-item-title">Torneo de programación</div>
                    <div class="admin-list-item-right">
                        <button class="admin-list-edit-btn">Editar</button>
                    </div>
                </div>

                <div class="admin-list-item">
                    <div class="admin-list-item-title">Feria de tecnología</div>
                    <div class="admin-list-item-right">
                        <button class="admin-list-edit-btn">Editar</button>
                    </div>
                </div>

                <div class="admin-list-item">
                    <div class="admin-list-item-title">Desarrollo de videojuegos</div>
                    <div class="admin-list-item-right">
                        <button class="admin-list-edit-btn">Editar</button>
                    </div>
                </div>

                <div class="admin-list-item">
                    <div class="admin-list-item-title">HackaTec</div>
                    <div class="admin-list-item-right">
                        <button class="admin-list-edit-btn">Editar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
