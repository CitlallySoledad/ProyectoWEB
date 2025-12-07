<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Panel administrador')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Iconos (Bootstrap Icons) --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <style>
        /* ===== CONTENEDOR GENERAL ===== */
        .panel-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 24px;
            background: radial-gradient(circle at top left, #4c1d95, #020617);
        }

        /* TARJETA PRINCIPAL (TODO EL PANEL) */
        .panel-card {
            width: 100%;
            max-width: 1300px;
            min-height: 540px;
            background: linear-gradient(135deg, #1e3a8a, #1d4ed8);
            border-radius: 24px;
            box-shadow: 0 20px 45px rgba(0,0,0,0.55);
            display: flex;
            overflow: hidden;
            color: #e5e7eb;
            position: relative;
        }

        /* ===== SIDEBAR ===== */
        .panel-sidebar {
            width: 260px;
            background: linear-gradient(180deg, #4c1d95, #7c3aed);
            padding: 18px 14px;
            display: flex;
            flex-direction: column;
        }

        /* flecha atrás + logo */
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
            border: 1px solid rgba(255,255,255,0.35);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background: rgba(15,23,42,0.25);
        }
        .sidebar-back i {
            font-size: 1rem;
            color: #f9fafb;
        }
        .sidebar-logo img {
            height: 40px;
        }

        .sidebar-section-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #c4b5fd;
            margin: 18px 0 8px;
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
            background: #111827;
            border-color: rgba(248,250,252,0.25);
        }
        .sidebar-link.active {
            background: #111827;
            border-color: rgba(248,250,252,0.25);
        }

        .sidebar-bottom {
            margin-top: auto;
        }
        .sidebar-logout {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 10px;
            border-radius: 999px;
            color: #fecaca;
            cursor: pointer;
        }
        .sidebar-logout i {
            font-size: 1.15rem;
        }

        /* ===== CONTENIDO PRINCIPAL ===== */
        .panel-main {
            flex: 1;
            padding: 20px 26px 26px;
            display: flex;
            flex-direction: column;
        }

        .panel-header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            margin-bottom: 18px;
        }

        .user-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 12px;
            border-radius: 999px;
            background: rgba(15,23,42,0.6);
            border: 1px solid rgba(148,163,184,0.6);
            font-size: 0.85rem;
        }
        .user-avatar {
            width: 26px;
            height: 26px;
            border-radius: 999px;
            background: #0f172a;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .user-avatar i {
            font-size: 1rem;
        }

        .panel-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* tarjetas genéricas dentro del panel */
        .admin-card {
            background: #0f3b57;
            border-radius: 18px;
            padding: 16px 18px;
            box-shadow: 0 8px 20px rgba(15,23,42,0.45);
        }

        .admin-card-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .admin-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
        }
        .admin-table th,
        .admin-table td {
            padding: 8px 10px;
        }
        .admin-table thead {
            background: rgba(15,23,42,0.7);
        }
        .admin-table tbody tr:nth-child(even) {
            background: rgba(15,23,42,0.25);
        }

        .admin-btn-primary {
            border-radius: 999px;
            padding: 6px 16px;
            border: none;
            background: #60a5fa;
            color: #0f172a;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .admin-btn-secondary {
            border-radius: 999px;
            padding: 6px 16px;
            border: none;
            background: rgba(148,163,184,0.35);
            color: #e5e7eb;
            font-size: 0.9rem;
        }

        @media (max-width: 960px) {
            .panel-card {
                flex-direction: column;
                max-width: 720px;
            }
            .panel-sidebar {
                width: 100%;
                flex-direction: row;
                align-items: center;
            }
            .sidebar-top {
                margin-bottom: 0;
                gap: 14px;
            }
            .sidebar-section-title {
                display: none;
            }
            .sidebar-bottom {
                margin-left: auto;
            }
        }

        @media (max-width: 780px) {
            .panel-main {
                padding: 16px 18px 20px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
@php
    use Illuminate\Support\Facades\Route;
    $routeName = Route::currentRouteName();
@endphp

<div class="panel-wrapper">
    <div class="panel-card">

        {{-- SIDEBAR IZQUIERDA --}}
        <aside class="panel-sidebar">
            <div class="sidebar-top">
                <a href="{{ route('public.home') }}">
                    <button class="sidebar-back" type="button">
                        <i class="bi bi-arrow-left"></i>
                    </button>
                </a>
                <div class="sidebar-logo">
                    <img src="{{ asset('imagenes/logo-ito.png') }}" alt="Logo">
                </div>
            </div>

            <p class="sidebar-section-title">Menú admin</p>
            <ul class="sidebar-menu">
                <li class="sidebar-item">
                    <a class="sidebar-link {{ str_starts_with($routeName, 'admin.dashboard') ? 'active' : '' }}"
                       href="{{ route('admin.dashboard') }}">
                        <i class="bi bi-house-door-fill"></i>
                        <span>Inicio</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link {{ str_starts_with($routeName, 'admin.events.') ? 'active' : '' }}"
                       href="{{ route('admin.events.index') }}">
                        <i class="bi bi-calendar-event"></i>
                        <span>Eventos</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link {{ str_starts_with($routeName, 'admin.teams.') ? 'active' : '' }}"
                       href="{{ route('admin.teams.index') }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Equipos</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link {{ str_starts_with($routeName, 'admin.evaluations.') ? 'active' : '' }}"
                       href="{{ route('admin.evaluations.index') }}">
                        <i class="bi bi-clipboard-check"></i>
                        <span>Evaluaciones</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link {{ str_starts_with($routeName, 'admin.users.') ? 'active' : '' }}"
                       href="{{ route('admin.users.index') }}">
                        <i class="bi bi-person-badge"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
            </ul>

            <div class="sidebar-bottom">
                <form action="{{ route('admin.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="sidebar-logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Salir</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- CONTENIDO PRINCIPAL --}}
        <main class="panel-main">
            <header class="panel-header">
                <div class="user-badge">
                    <div class="user-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <span>Administrador</span>
                </div>
            </header>

            <section class="panel-content">
                @yield('content')
            </section>
        </main>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
