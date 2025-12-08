@extends('layouts.admin')

@section('title', 'Gestión de repositorios')

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

    /* SIDEBAR */
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

    /* CONTENIDO PRINCIPAL */
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
        margin-bottom: 18px;
    }

    .panel-title {
        font-size: 1.8rem;
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

    .repo-header-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .btn-add-repo {
        border-radius: 999px;
        border: none;
        padding: 8px 18px;
        font-size: 0.9rem;
        background: #22c55e;
        color: #0f172a;
        cursor: pointer;
    }

    .repo-table-card {
        background: rgba(15, 23, 42, 0.7);
        border-radius: 20px;
        padding: 16px 18px 20px;
        box-shadow: 0 12px 32px rgba(0, 0, 0, 0.6);
    }

    table.repo-table {
        width: 100%;
        border-collapse: collapse;
    }

    .repo-table th,
    .repo-table td {
        padding: 10px 12px;
        font-size: 0.9rem;
    }

    .repo-table th {
        text-align: left;
        color: #e5e7eb;
        border-bottom: 1px solid rgba(148, 163, 184, 0.4);
    }

    .repo-table tr + tr td {
        border-top: 1px solid rgba(30, 64, 175, 0.4);
    }

    .repo-actions {
        display: flex;
        gap: 8px;
        justify-content: flex-start;
    }

    .btn-small {
        border-radius: 999px;
        border: none;
        padding: 6px 12px;
        font-size: 0.8rem;
        cursor: pointer;
        white-space: nowrap;
    }

    .btn-view {
        background: #0ea5e9;
        color: #0b1120;
    }

    .btn-edit {
        background: #6366f1;
        color: #f9fafb;
    }

    .btn-delete {
        background: #ef4444;
        color: #f9fafb;
    }

    .repo-footer {
        margin-top: 16px;
        display: flex;
        gap: 10px;
    }

    .btn-footer {
        border-radius: 999px;
        border: none;
        padding: 8px 18px;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .btn-back-menu {
        background: #0f172a;
        color: #e5e7eb;
        border: 1px solid rgba(148, 163, 184, 0.6);
    }

    .btn-exit {
        background: #ef4444;
        color: #f9fafb;
    }

    .empty-text {
        font-size: 0.95rem;
        color: #e5e7eb;
        opacity: 0.9;
    }
</style>
@endpush

@section('content')
<div class="panel-wrapper">
    <div class="panel-card">

        {{-- SIDEBAR --}}
        <aside class="panel-sidebar">

            <div class="sidebar-top">
                <form action="{{ route('panel.submission') }}" method="GET">
                    <button type="submit" class="sidebar-back">
                        <i class="bi bi-arrow-left"></i>
                    </button>
                </form>
                <div class="sidebar-logo">
                    <img src="{{ asset('imagenes/logo-ito.png') }}" alt="Logo ITO">
                </div>
            </div>

            <div class="sidebar-middle">
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

                <div>
                    <p class="sidebar-section-title">Equipo</p>
                    <ul class="sidebar-menu">
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="{{ route('panel.mi-equipo') }}">
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
                            <a class="sidebar-link" href="{{ route('panel.lista-eventos') }}">
                                <i class="bi bi-calendar2-week"></i>
                                <span>Lista eventos</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link active" href="{{ route('panel.submission.repositories') }}">
                                <i class="bi bi-file-earmark-arrow-up"></i>
                                <span>Repositorios del proyecto</span>
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

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="panel-main">
            <div class="panel-main-inner">

                <div class="panel-header">
                    <h1 class="panel-title">Gestión de repositorios</h1>

                    <div class="user-badge">
                        <i class="bi bi-person-circle"></i>
                        <span>{{ auth()->user()->name ?? 'Usuario' }}</span>
                    </div>
                </div>

                <div class="repo-header-row">
                    <div>
                        @if($projectName)
                            <div style="font-size:0.9rem; opacity:0.9;">
                                Proyecto: <strong>{{ $projectName }}</strong>
                            </div>
                        @else
                            <div class="empty-text">
                                No hay proyecto asociado todavía.
                            </div>
                        @endif
                    </div>

                    {{-- AQUÍ LE DAMOS ACCIÓN AL BOTÓN --}}
                    <button type="button" class="btn-add-repo" onclick="mostrarTablaRepos()">
                        Agregar repositorio
                    </button>
                </div>

                <div class="repo-table-card">
                    {{-- SI HUBIERA REPOS EN BD, SE MUESTRAN COMO SIEMPRE --}}
                    @if(count($repositories))
                        <table class="repo-table">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>URL</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($repositories as $repo)
                                    <tr>
                                        <td>{{ $repo['name'] }}</td>
                                        <td>
                                            <a href="{{ $repo['url'] }}" target="_blank" style="color:#bfdbfe;">
                                                {{ $repo['url'] }}
                                            </a>
                                        </td>
                                        <td>
                                            <div class="repo-actions">
                                                <a href="{{ $repo['url'] }}" target="_blank" class="btn-small btn-view">Ver</a>
                                                <button type="button" class="btn-small btn-edit">Editar</button>
                                                <button type="button" class="btn-small btn-delete">Eliminar</button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        {{-- ESTADO VACÍO (LO QUE VES AHORA) --}}
                        <p id="mensaje-sin-repos" class="empty-text">
                            Aún no hay repositorios registrados para esta submisión.
                        </p>

                        {{-- TABLA DE EJEMPLO, OCULTA HASTA QUE DES CLIC EN "AGREGAR REPOSITORIO" --}}
                        <div id="tabla-ejemplo-repos" style="display:none;">
                            <table class="repo-table">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>URL</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>EduTrack-Repo</td>
                                        <td><a href="https://github.com/edutrack" target="_blank" style="color:#bfdbfe;">github.com/edutrack</a></td>
                                        <td>
                                            <div class="repo-actions">
                                                <button type="button" class="btn-small btn-view">Ver</button>
                                                <button type="button" class="btn-small btn-edit">Editar</button>
                                                <button type="button" class="btn-small btn-delete">Eliminar</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>GreenTech-Core</td>
                                        <td><a href="https://gitlab.com/gt/core" target="_blank" style="color:#bfdbfe;">gitlab.com/gt/core</a></td>
                                        <td>
                                            <div class="repo-actions">
                                                <button type="button" class="btn-small btn-view">Ver</button>
                                                <button type="button" class="btn-small btn-edit">Editar</button>
                                                <button type="button" class="btn-small btn-delete">Eliminar</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>SafeRoute-Backend</td>
                                        <td><a href="https://bitbucket.org/sr/back" target="_blank" style="color:#bfdbfe;">bitbucket.org/sr/back</a></td>
                                        <td>
                                            <div class="repo-actions">
                                                <button type="button" class="btn-small btn-view">Ver</button>
                                                <button type="button" class="btn-small btn-edit">Editar</button>
                                                <button type="button" class="btn-small btn-delete">Eliminar</button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="repo-footer">
                    <a href="{{ route('panel.submission') }}" class="btn-footer btn-back-menu">
                        Volver al menú
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-footer btn-exit">
                            Salir
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // Cuando no hay repositorios en BD,
    // este botón muestra la tabla de ejemplo (como en tu maqueta).
    function mostrarTablaRepos() {
        const msg = document.getElementById('mensaje-sin-repos');
        const tabla = document.getElementById('tabla-ejemplo-repos');

        if (msg) msg.style.display = 'none';
        if (tabla) tabla.style.display = 'block';
    }
</script>
@endsection
