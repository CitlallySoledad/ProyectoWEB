@extends('layouts.admin')

@section('title', 'Lista de equipo')

@push('styles')
<style>
/* ===== CONTENEDOR GENERAL ======================================= */
.panel-wrapper {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 24px;
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

/* ===== SIDEBAR ================================================== */
.panel-sidebar {
    width: 250px;
    background: linear-gradient(180deg, #4c1d95, #7c3aed);
    padding: 18px 14px;
    display: flex;
    flex-direction: column;
    border-radius: 24px 0 0 24px;
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
    border: 1px solid rgba(255, 255, 255, 0.35);
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    color: #f9fafb;
    cursor: pointer;
}

.sidebar-back i {
    font-size: 1.1rem;
}

.sidebar-logo img {
    height: 40px;
}

.sidebar-middle {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 18px;
}

.sidebar-section-title {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #c4b5fd;
    margin-bottom: 4px;
}

.sidebar-nav {
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
    transition: background 0.2s, transform 0.2s;
}

.sidebar-link i {
    font-size: 1.1rem;
}

.sidebar-link:hover {
    background: rgba(15, 23, 42, 0.3);
    transform: translateX(2px);
}

/* opción activa (Lista de equipo) */
.sidebar-link.active {
    background: #020617;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
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

.sidebar-logout i {
    font-size: 1.15rem;
}

/* ===== CONTENIDO PRINCIPAL ====================================== */
.panel-main {
    flex: 1;
    padding: 20px 26px 26px;
    display: flex;
    flex-direction: column;
}

.panel-main-inner {
    width: 100%;
    max-width: 780px;
    margin: 0 auto;
}

/* header con título + usuario */
.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.panel-title {
    font-size: 1.6rem;
    font-weight: 700;
}

.user-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    border-radius: 999px;
    padding: 6px 14px;
    background: rgba(15, 23, 42, 0.9);
    border: 1px solid rgba(148, 163, 184, 0.6);
    font-size: 0.85rem;
}

.user-badge i {
    font-size: 1.3rem;
}

/* ===== BUSCADOR + FILTROS ====================================== */
.team-search {
    background: rgba(15, 23, 42, 0.3);
    border-radius: 18px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 12px;
    margin-bottom: 16px;
}

.team-search-input-wrapper {
    position: relative;
    width: 100%;
}

.team-search-input-wrapper input {
    width: 100%;
    padding: 10px 14px 10px 38px;
    border-radius: 999px;
    border: none;
    outline: none;
    font-size: 0.9rem;
    background: #f9fafb;
    color: #111827;
}

.team-search-input-wrapper input::placeholder {
    color: #9ca3af;
    font-size: 0.9rem;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
}

.team-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
}

.btn-chip {
    border: none;
    border-radius: 16px;
    padding: 10px 18px;
    background: #0f3355;
    color: #e5e7eb;
    box-shadow: 0 8px 16px rgba(15, 23, 42, 0.45);
    font-size: 0.9rem;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    cursor: pointer;
    white-space: nowrap;
}

.btn-chip.primary {
    background: #0f3c7a;
}

.btn-chip i {
    font-size: 1rem;
}

/* ===== TABLA DE EQUIPOS ======================================== */
.team-table-wrapper {
    background: rgba(15, 23, 42, 0.65);
    border-radius: 18px;
    padding: 14px 16px 18px;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5);
}

.team-table {
    width: 100%;
    border-spacing: 0;
}

.team-table thead th {
    text-align: left;
    padding: 10px 12px;
    font-size: 0.9rem;
    font-weight: 600;
    color: #e5e7eb;
}

.team-table tbody tr {
    border-radius: 12px;
    overflow: hidden;
    transition: background 0.2s;
}

.team-table tbody tr:hover {
    background: rgba(15, 23, 42, 0.7);
}

.team-table tbody td {
    padding: 10px 12px;
    font-size: 0.9rem;
    vertical-align: middle;
}

/* ===== AVATARES + BOTÓN INFO =================================== */
.avatar-stack {
    display: flex;
}

.avatar-stack img {
    width: 28px;
    height: 28px;
    border-radius: 999px;
    border: 2px solid #0b2b4a;
    object-fit: cover;
    margin-left: -8px;
}

.avatar-stack img:first-child {
    margin-left: 0;
}

.btn-info {
    border-radius: 999px;
    border: none;
    padding: 6px 16px;
    font-size: 0.8rem;
    background: #e5e7eb;
    color: #111827;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
}

/* ===== RESPONSIVE ============================================== */
@media (max-width: 1024px) {
    .panel-card {
        flex-direction: column;
    }

    .panel-sidebar {
        width: 100%;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        border-radius: 24px 24px 0 0;
    }

    .sidebar-middle {
        flex-direction: row;
        align-items: center;
        gap: 18px;
    }

    .sidebar-section-title {
        margin: 0;
    }

    .panel-main {
        padding-top: 16px;
    }
}

@media (max-width: 640px) {
    .panel-wrapper {
        padding: 12px;
    }

    .panel-card {
        border-radius: 18px;
    }

    .panel-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .team-filters {
        flex-direction: column;
        align-items: stretch;
    }
}
</style>
@endpush

@section('content')
<div class="panel-wrapper">
    <div class="panel-card">

        {{-- ===== SIDEBAR IZQUIERDA ===== --}}
        <aside class="panel-sidebar" id="sidebar">
            <div class="sidebar-top">
                <button class="sidebar-back" type="button" onclick="window.location='{{ url('/panel') }}'">
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
                                <i class="bi bi-house-door"></i>
                                <span>Inicio</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#">
                                <i class="bi bi-calendar-event"></i>
                                <span>Eventos</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#">
                                <i class="bi bi-person"></i>
                                <span>Mi perfil</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <div class="sidebar-section-title">Equipo</div>
                    <ul class="sidebar-nav">
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#">
                                <i class="bi bi-people"></i>
                                <span>Mi equipo</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link active" href="{{ route('panel.lista-equipo') }}">
                                <i class="bi bi-list-check"></i>
                                <span>Lista de equipo</span>
                            </a>
                        </li>
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#">
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
                        <li class="sidebar-item">
                            <a class="sidebar-link" href="#">
                                <i class="bi bi-file-earmark-arrow-up"></i>
                                <span>Submision del proyecto</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="sidebar-bottom">
                <div class="sidebar-logout">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Salir</span>
                </div>
            </div>
        </aside>

        {{-- ===== CONTENIDO PRINCIPAL ===== --}}
        <main class="panel-main">
            <div class="panel-main-inner">

                <header class="panel-header">
                    <h1 class="panel-title">Lista de equipo</h1>
                    <div class="user-badge">
                        <i class="bi bi-person-circle"></i>
                        <span>Usuario</span>
                    </div>
                </header>

                {{-- Buscador + filtros --}}
                <section class="team-search">
                    <div class="team-search-input-wrapper">
                        <span class="search-icon">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" placeholder="Buscar equipo...">
                    </div>
                    <div class="team-filters">
                        <button class="btn-chip">
                            <i class="bi bi-funnel"></i>
                            Filter
                        </button>
                        <button class="btn-chip primary">
                            Last 7 days
                            <i class="bi bi-caret-down-fill"></i>
                        </button>
                        <button class="btn-chip primary">
                            Crear
                        </button>
                    </div>
                </section>

                {{-- Tabla de equipos --}}
                <section class="team-table-wrapper">
                    <table class="team-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Fecha</th>
                                <th>Descripción</th>
                                <th>Miembros</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ([
                                ['Dinamita', '2025-05-23', 'Descripción'],
                                ['Escuadrón', '2025-05-23', 'Descripción'],
                                ['Buena onda', '2025-05-23', 'Descripción'],
                                ['Alfa lobo', '2025-05-23', 'Descripción'],
                            ] as $team)
                                <tr>
                                    <td>{{ $team[0] }}</td>
                                    <td>{{ $team[1] }}</td>
                                    <td>{{ $team[2] }}</td>
                                    <td>
                                        <div class="avatar-stack">
                                            <img src="https://i.pravatar.cc/40?img=1" alt="">
                                            <img src="https://i.pravatar.cc/40?img=2" alt="">
                                            <img src="https://i.pravatar.cc/40?img=3" alt="">
                                        </div>
                                    </td>
                                    <td>
                                        <button class="btn-info">
                                            INFO <i class="bi bi-chevron-right"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </section>

            </div>
        </main>

    </div>
</div>
@endsection
