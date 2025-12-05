@extends('layouts.admin')

@section('title', 'Submisión del proyecto')

@push('styles')
<style>
    .panel-wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 24px;
        background-color: #0f172a;
    }

    .panel-card {
        width: 100%;
        max-width: 1100px;
        min-height: 540px;
        background: linear-gradient(135deg, #1e3a8a, #1d4ed8);
        border-radius: 24px;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.55);
        display: flex;
        overflow: hidden;
        color: #e5e7eb;
    }

    /* ====== SIDEBAR (igual al resto de tu panel participante) ====== */
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
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }

    .sidebar-back {
        width: 32px;
        height: 32px;
        border-radius: 999px;
        border: 1px solid rgba(248, 250, 252, 0.5);
        background: transparent;
        color: #f9fafb;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .sidebar-logo img {
        height: 40px;
    }

    .sidebar-middle {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 18px;
        overflow-y: auto;
    }

    .sidebar-section-title {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #c4b5fd;
        margin-bottom: 4px;
    }

    .sidebar-menu {
        list-style: none;
        margin: 0;
        padding: 0;
    }

    .sidebar-item {
        margin-bottom: 6px;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 10px;
        border-radius: 999px;
        font-size: 0.9rem;
        text-decoration: none;
        color: #e5e7eb;
        cursor: pointer;
        border: 1px solid transparent;
    }

    .sidebar-link i {
        font-size: 1.1rem;
    }

    .sidebar-link:hover {
        background: rgba(15, 23, 42, 0.3);
        transform: translateX(2px);
    }

    .sidebar-link.active {
        background: #020617;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        border-color: rgba(248, 250, 252, 0.2);
    }

    .sidebar-bottom {
        padding-top: 12px;
        border-top: 1px solid rgba(248, 250, 252, 0.18);
    }

    .sidebar-logout {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 10px;
        border-radius: 999px;
        color: #fecaca;
        cursor: pointer;
    }

    /* ====== CONTENIDO PRINCIPAL ====== */
    .panel-main {
        flex: 1;
        padding: 20px 26px 26px;
        display: flex;
        flex-direction: column;
    }

    .panel-main-inner {
        width: 100%;
        margin: 0 auto;
    }

    .panel-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }

    .panel-title {
        font-size: 1.9rem;
        font-weight: 700;
    }

    .user-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.7);
        border: 1px solid rgba(148, 163, 184, 0.6);
        font-size: 0.85rem;
    }

    .user-badge i {
        font-size: 1rem;
    }

    /* ====== LAYOUT DE SUBMISIÓN (tipo tu maqueta) ====== */
    .submission-layout {
        display: grid;
        grid-template-columns: minmax(0, 2.2fr) minmax(0, 1.1fr);
        gap: 32px;
        align-items: flex-start;
    }

    .submission-left,
    .submission-right {
        background: rgba(15, 23, 42, 0.55);
        border-radius: 20px;
        padding: 20px 22px;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.5);
    }

    /* IZQUIERDA: formulario */
    .submission-label {
        font-size: 0.9rem;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .submission-input {
        width: 100%;
        border-radius: 999px;
        border: none;
        outline: none;
        padding: 10px 16px;
        font-size: 0.95rem;
        background: #0f3b57;
        color: #f9fafb;
        margin-bottom: 18px;
    }

    .submission-input::placeholder {
        color: #9ca3af;
    }

    .visibility-group {
        margin-top: 4px;
    }

    .visibility-options {
        display: inline-flex;
        padding: 4px;
        border-radius: 999px;
        background: #020617;
    }

    .visibility-pill {
        border-radius: 999px;
        padding: 6px 22px;
        font-size: 0.85rem;
        border: none;
        cursor: pointer;
    }

    .visibility-pill.active {
        background: #2563eb;
        color: #f9fafb;
    }

    .visibility-pill.inactive {
        background: #f9fafb;
        color: #111827;
    }

    .submission-hint {
        font-size: 0.8rem;
        color: #e5e7eb;
        opacity: 0.85;
        margin-top: 4px;
    }

    /* DERECHA: botones */
    .submission-right-title {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 14px;
        opacity: 0.9;
    }

    .btn-rounded {
        border-radius: 999px;
        border: none;
        padding: 9px 20px;
        font-size: 0.9rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        white-space: nowrap;
        width: 100%;
    }

    .btn-save {
        background: #2563eb;
        color: #f9fafb;
        margin-bottom: 10px;
    }

    .btn-cancel {
        background: #0f172a;
        color: #e5e7eb;
        border: 1px solid rgba(148, 163, 184, 0.6);
        margin-bottom: 20px;
    }

    .btn-repo {
        background: #a855f7;
        color: #f9fafb;
        margin-top: 10px;
    }

    .submission-right-small {
        font-size: 0.8rem;
        opacity: 0.9;
        margin-bottom: 6px;
    }

    .alert-success {
        margin-bottom: 12px;
        padding: 8px 12px;
        border-radius: 10px;
        background: rgba(22, 163, 74, 0.2);
        border: 1px solid rgba(22, 163, 74, 0.6);
        font-size: 0.85rem;
    }

    .empty-text {
        font-size: 0.9rem;
        color: #e5e7eb;
        opacity: 0.9;
        margin-top: 2px;
    }

    @media (max-width: 900px) {
        .submission-layout {
            grid-template-columns: 1fr;
        }

        .submission-right {
            order: -1;
        }
    }
</style>
@endpush

@section('content')
<div class="panel-wrapper">
    <div class="panel-card">

        {{-- ========== SIDEBAR ========== --}}
        <aside class="panel-sidebar">

            <div class="sidebar-top">
                <form action="{{ route('panel.participante') }}" method="GET">
                    <button type="submit" class="sidebar-back">
                        <i class="bi bi-arrow-left"></i>
                    </button>
                </form>
                <div class="sidebar-logo">
                    <img src="{{ asset('imagenes/logo-ito.png') }}" alt="Logo ITO">
                </div>
            </div>

            <div class="sidebar-middle">
                {{-- MENÚ --}}
                <div>
                    <p class="sidebar-section-title">Menú</p>
                    <ul class="sidebar-menu">
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.participante') }}">
                                <i class="bi bi-house-door"></i>
                                <span>Inicio</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.eventos') }}">
                                <i class="bi bi-calendar2-week"></i>
                                <span>Eventos</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.perfil') }}">
                                <i class="bi bi-person"></i>
                                <span>Mi perfil</span>
                            </a>
                        </li>
                    </ul>
                </div>

                {{-- EQUIPO --}}
                <div>
                    <p class="sidebar-section-title">Equipo</p>
                    <ul class="sidebar-menu">
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#">
                                <i class="bi bi-people"></i>
                                <span>Mi equipo</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.lista-equipo') }}">
                                <i class="bi bi-list-task"></i>
                                <span>Lista de equipo</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.teams.create') }}">
                                <i class="bi bi-plus-circle"></i>
                                <span>Crear equipo</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#">
                                <i class="bi bi-person-badge"></i>
                                <span>Rol</span>
                            </a>
                        </li>

                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#">
                                <i class="bi bi-calendar2-week"></i>
                                <span>Lista eventos</span>
                            </a>
                        </li>

                        {{-- OPCIÓN ACTIVA --}}
                        <li class="sidebar-item">
                            <a class="sidebar-link active" href="{{ route('panel.submission') }}">
                                <i class="bi bi-file-earmark-arrow-up"></i>
                                <span>Submisión del proyecto</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-bottom">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Salir</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- ========== CONTENIDO PRINCIPAL ========== --}}
        <div class="panel-main">
            <div class="panel-main-inner">

                <div class="panel-header">
                    <h1 class="panel-title">Submisión del proyecto</h1>

                    <div class="user-badge">
                        <i class="bi bi-person-circle"></i>
                        <span>{{ auth()->user()->name ?? 'Usuario' }}</span>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @php
                    $projectName = $project['name'] ?? '';
                    $visibility  = old('visibility', $project['visibility'] ?? 'privado');
                @endphp

                <div class="submission-layout">
                    {{-- COLUMNA IZQUIERDA: formulario principal --}}
                    <div class="submission-left">
                        <form action="{{ route('panel.submission.update') }}" method="POST">
                            @csrf

                            {{-- Nombre del proyecto --}}
                            <div class="mb-2">
                                <div class="submission-label">Nombre de proyecto</div>
                                <input
                                    type="text"
                                    name="project_name"
                                    class="submission-input"
                                    placeholder="Nombre de proyecto"
                                    value="{{ old('project_name', $projectName) }}"
                                >
                                @error('project_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                @if(!$projectName)
                                    <div class="submission-hint">
                                        Aún no se ha detectado un proyecto de equipo. Puedes escribir el nombre
                                        manualmente o crearlo desde la sección de equipos.
                                    </div>
                                @endif
                            </div>

                            {{-- Visibilidad --}}
                            <div class="visibility-group">
                                <div class="submission-label">Visibilidad</div>

                                <div class="visibility-options">
                                    <button type="button"
                                            class="visibility-pill {{ $visibility === 'privado' ? 'active' : 'inactive' }}"
                                            onclick="document.getElementById('visibility-privado').checked = true; toggleVisibilityPills();">
                                        Privado
                                    </button>
                                    <button type="button"
                                            class="visibility-pill {{ $visibility === 'publico' ? 'active' : 'inactive' }}"
                                            onclick="document.getElementById('visibility-publico').checked = true; toggleVisibilityPills();">
                                        Público
                                    </button>
                                </div>

                                <input type="radio" id="visibility-privado" name="visibility" value="privado"
                                       style="display:none" {{ $visibility === 'privado' ? 'checked' : '' }}>
                                <input type="radio" id="visibility-publico" name="visibility" value="publico"
                                       style="display:none" {{ $visibility === 'publico' ? 'checked' : '' }}>

                                @error('visibility')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Esta parte se conecta con los botones de la derecha usando JS submit --}}
                            <input type="submit" id="submit-hidden" style="display:none">
                        </form>
                    </div>

                    {{-- COLUMNA DERECHA: acciones --}}
                    <div class="submission-right">
                        <div class="submission-right-title">Acciones de la submisión</div>

                        <p class="submission-right-small">
                            Guarda los cambios del nombre del proyecto y su visibilidad.
                        </p>

                        <button class="btn-rounded btn-save" type="button"
                                onclick="document.getElementById('submit-hidden').click();">
                            Guardar cambios
                        </button>

                        <button class="btn-rounded btn-cancel" type="button"
                                onclick="window.location.href='{{ route('panel.participante') }}'">
                            Cancelar
                        </button>

                        <hr style="border-color: rgba(148,163,184,0.4); margin: 16px 0;">

                        <p class="submission-right-small">
                            Gestiona los repositorios asociados a esta submisión.
                        </p>
                        <a href="{{ route('panel.submission.repositories') }}" class="btn-rounded btn-repo">
                            Gestionar / ver repositorios
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    function toggleVisibilityPills() {
        const pills = document.querySelectorAll('.visibility-pill');
        const privadoChecked = document.getElementById('visibility-privado').checked;

        pills[0].classList.toggle('active', privadoChecked);
        pills[0].classList.toggle('inactive', !privadoChecked);

        pills[1].classList.toggle('active', !privadoChecked);
        pills[1].classList.toggle('inactive', privadoChecked);
    }

    document.addEventListener('DOMContentLoaded', toggleVisibilityPills);
</script>
@endsection
