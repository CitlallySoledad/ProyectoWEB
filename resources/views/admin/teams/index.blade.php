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

                <button class="admin-page-create-btn">
                    Crear Equipo
                </button>
            </div>

            <div class="admin-list">
                <div class="admin-list-item">
                    <div class="admin-list-item-title">Dinamita</div>
                    <div class="admin-list-item-right">
                        <span>3/4</span>
                        <button class="admin-list-edit-btn">Editar</button>
                    </div>
                </div>

                <div class="admin-list-item">
                    <div class="admin-list-item-title">Escuadrón Suicida</div>
                    <div class="admin-list-item-right">
                        <span>3/4</span>
                        <button class="admin-list-edit-btn">Editar</button>
                    </div>
                </div>

                <div class="admin-list-item">
                    <div class="admin-list-item-title">Buena onda</div>
                    <div class="admin-list-item-right">
                        <span>4/4</span>
                        <button class="admin-list-edit-btn">Editar</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
