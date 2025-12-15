@extends('layouts.admin')

@section('title', 'Crear equipo')

@push('styles')
<style>
    /* ===== ESTILOS GENERALES Y LAYOUT ===== */
    .crear-equipo-fullscreen {
    width: 100%;
    min-height: 100vh;
    margin: 0;
    padding: 0;
    background: #020617;   /* o el fondo general de tu layout */
}


    .panel-card {
    width: 100%;
    max-width: 100%;        /* nada de 1100px */
    min-height: 100vh;      /* ocupa el alto de la pantalla */
    background: transparent;/* quitamos el fondo de tarjeta */
    border-radius: 0;       /* sin esquinas redondeadas */
    box-shadow: none;       /* sin sombra flotante */
    display: flex;
    overflow: hidden;
    color: #e5e7eb;
}

    /* ===== SIDEBAR (Idéntico a listaEquipo) ===== */
    .panel-sidebar {
        width: 250px;
        background: linear-gradient(180deg, #4c1d95, #7c3aed);
        padding: 18px 14px;
        display: flex;
        flex-direction: column;
        border-radius: 24px 0 0 24px;
        flex-shrink: 0;
    }

    .sidebar-top {
        display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px;
    }
    .sidebar-back {
        width: 32px; height: 32px; border-radius: 999px; border: 1px solid rgba(255, 255, 255, 0.35);
        display: flex; align-items: center; justify-content: center; background: transparent; color: #f9fafb; cursor: pointer;
    }
    .sidebar-logo img { height: 40px; }

    .sidebar-middle {
        flex: 1; display: flex; flex-direction: column; gap: 18px; overflow-y: auto;
    }

    .sidebar-section-title {
        font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.06em; color: #c4b5fd; margin-bottom: 4px;
    }

    .sidebar-nav { list-style: none; margin: 0; padding: 0; }
    .sidebar-item { margin-bottom: 6px; }

    .sidebar-link {
        display: flex; align-items: center; gap: 10px; padding: 8px 10px; border-radius: 999px;
        font-size: 0.9rem; text-decoration: none; color: #e5e7eb; transition: background 0.2s, transform 0.2s;
    }
    .sidebar-link i { font-size: 1.1rem; }
    .sidebar-link:hover { background: rgba(15, 23, 42, 0.3); transform: translateX(2px); }
    .sidebar-link.active { background: #020617; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5); }

    .sidebar-bottom { padding-top: 12px; border-top: 1px solid rgba(248, 250, 252, 0.18); }
    .sidebar-logout { display: inline-flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 999px; color: #fecaca; cursor: pointer; }


    /* ===== CONTENIDO PRINCIPAL ===== */
    .panel-main {
        flex: 1;
        padding: 30px;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }

    .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
    .panel-title { font-size: 1.8rem; font-weight: 700; }
    .user-badge { display: inline-flex; align-items: center; gap: 8px; border-radius: 999px; padding: 6px 14px; background: rgba(15, 23, 42, 0.9); border: 1px solid rgba(148, 163, 184, 0.6); font-size: 0.85rem; }


    /* ===== FORMULARIO DE CREAR EQUIPO ===== */
    .create-team-layout {
        display: flex;
        gap: 40px;
        height: 100%;
    }

    .form-section { flex: 3; }

    .form-group { margin-bottom: 24px; }
    .form-label { display: block; font-size: 1.1rem; font-weight: 600; margin-bottom: 12px; color: #f9fafb; }

    .form-input {
        width: 100%;
        background-color: rgba(30, 58, 138, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 16px;
        padding: 14px 20px;
        font-size: 1rem;
        color: #fff;
        outline: none;
        transition: border-color 0.2s, background-color 0.2s;
    }
    .form-input:focus {
        border-color: rgba(255, 255, 255, 0.3);
        background-color: rgba(30, 58, 138, 0.7);
    }

    .input-with-badge-wrapper { position: relative; display: flex; align-items: center; }
    .badge-lider {
        position: absolute; right: 12px;
        background-color: #dc2626; color: white;
        font-size: 0.85rem; font-weight: 600; padding: 4px 12px; border-radius: 8px;
    }

    /* Sección de Botones */
    .actions-section {
        flex: 1;
        display: flex;
        justify-content: flex-end;
        align-items: flex-end;
        padding-bottom: 10px;
    }

    .action-row {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .btn-action {
        border: none; border-radius: 999px; padding: 12px 28px;
        font-size: 0.95rem; font-weight: 600; cursor: pointer;
        transition: transform 0.2s, opacity 0.2s; width: auto;
    }
    .btn-action:hover { transform: scale(1.05); opacity: 0.9; }

    .btn-secondary { background-color: rgba(30, 45, 85, 0.8); color: #e5e7eb; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);}
    .btn-primary-create {
        background: linear-gradient(to right, #8b5cf6, #6366f1); color: white;
        box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4); padding: 14px 32px; font-size: 1rem;
    }


    /* ========================================================== */
    /* ===== AUTO-AJUSTE (RESPONSIVE) - LA MAGIA PARA MÓVIL ===== */
    /* ========================================================== */

    @media (max-width: 1024px) {
        .panel-card { flex-direction: column; min-height: auto; }
        .panel-sidebar { width: 100%; border-radius: 24px 24px 0 0; }
        .sidebar-middle { flex-direction: row; gap: 10px; overflow-x: auto; padding-bottom: 10px; }
        .sidebar-section-title { display: none; }
        .sidebar-nav { display: flex; gap: 8px; }
        .sidebar-link { white-space: nowrap; padding: 6px 12px; font-size: 0.85rem; }
        
        .create-team-layout { flex-direction: column; gap: 30px; }
        .actions-section { 
            align-items: center; justify-content: flex-end; width: 100%;
        }
        .action-row { width: 100%; justify-content: flex-end; }
        .btn-action { width: auto; margin-bottom: 0; }
    }

    @media (max-width: 640px) {
        .panel-wrapper { padding: 10px; }
        .panel-card { border-radius: 16px; }
        .panel-main { padding: 20px; }
        .panel-header { flex-direction: column; align-items: flex-start; gap: 10px; }
        .actions-section { width: 100%; }
        .action-row { width: 100%; flex-direction: column-reverse; gap: 12px; }
        .btn-action { width: 100%; text-align: center; }
    }
</style>
@endpush

@section('content')
<div class="crear-equipo-fullscreen">
    <div class="panel-card">

        {{-- ===== SIDEBAR COMPLETO ===== --}}
        <aside class="panel-sidebar" id="sidebar">
            <div class="sidebar-top">
                <button class="sidebar-back" type="button" onclick="window.location='{{ route('panel.lista-equipo') }}'">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <div class="sidebar-logo">
                    <img src="{{ asset('imagenes/logo-ito.png') }}" alt="Logo">
                </div>
            </div>

            <div class="sidebar-middle">
                <div>
                    <div class="sidebar-section-title">Menú</div>
                    <ul class="sidebar-nav">
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.participante') }}">
                                <i class="bi bi-house-door"></i> <span>Inicio</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.perfil') }}">
                                <i class="bi bi-person"></i> <span>Mi perfil</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div style="margin-top: 20px;">
                    <div class="sidebar-section-title">Equipo</div>
                    <ul class="sidebar-nav">
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.mi-equipo') }}">
                                <i class="bi bi-people"></i> <span>Mi equipo</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.lista-equipo') }}">
                                <i class="bi bi-list-ul"></i> <span>Lista de equipo</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link active" href="{{ route('panel.teams.create') }}">
                                <i class="bi bi-plus-circle"></i> <span>Crear equipo</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.lista-eventos') }}">
                                <i class="bi bi-calendar-week"></i> <span>Lista eventos</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.submission') }}">
                                <i class="bi bi-file-earmark-arrow-up"></i> <span>Submision del proyecto</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-bottom">
                <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                    @csrf
                    <button type="submit" class="sidebar-logout" style="background: none; border: none; width: 100%; text-align: left; font: inherit; cursor: pointer;">
                        <i class="bi bi-box-arrow-right"></i> <span>Salir</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ===== CONTENIDO PRINCIPAL ===== --}}
        <main class="panel-main">

            <header class="panel-header">
                <h1 class="panel-title">Crear equipo</h1>
                <a href="{{ route('panel.perfil') }}" class="user-badge" style="text-decoration: none; color: inherit; cursor: pointer;">
                    <i class="bi bi-person-circle"></i>
                    {{-- Mostrar siempre el usuario autenticado --}}
                    <span>{{ auth()->check() ? auth()->user()->name : 'Usuario' }}</span>
                </a>
            </header>

            <div class="create-team-layout">

                {{-- Columna Izquierda: Formulario SIN DESCRIPCIÓN --}}
                <section class="form-section">
                    <form id="createTeamForm" action="{{ route('panel.teams.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <x-input-label for="team_name" value="Nombre de equipo" class="form-label" />
                            <x-text-input 
                                id="team_name" 
                                name="team_name" 
                                type="text"
                                class="form-input" 
                                placeholder="Introduce el nombre del equipo" 
                                required
                                minlength="3"
                                maxlength="100"
                                pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ0-9\s]+"
                                title="Mínimo 3 caracteres. Solo letras, números y espacios"
                            />
                            <x-input-error :messages="$errors->get('team_name')" class="mt-2" />
                        </div>

                        <div class="form-group">
                            <x-input-label for="user_name" value="Nombre Usuario" class="form-label" />
                            <div class="input-with-badge-wrapper">
                                <x-text-input 
                                    id="user_name" 
                                    name="user_name" 
                                    type="text"
                                    class="form-input" 
                                    :value="auth()->check() ? auth()->user()->name : 'Usuario'" 
                                    readonly 
                                />
                                <span class="badge-lider">Lider</span>
                            </div>
                            <x-input-error :messages="$errors->get('user_name')" class="mt-2" />
                        </div>

                        {{-- ELIMINADO: Campo Descripción --}}
                    </form>
                </section>

                <section class="actions-section">
                    <div class="action-row">
                        <button type="button" class="btn-action btn-secondary" onclick="window.location='{{ route('panel.lista-equipo') }}'">
                            Cancelar
                        </button>
                        <button type="submit" form="createTeamForm" class="btn-action btn-primary-create">
                            Crear Equipo
                        </button>
                    </div>
                </section>

            </div>
        </main>
    </div>
</div>

{{-- Sistema de notificaciones Toast --}}
<x-toast-notification />

@endsection
