@extends('layouts.admin')

@section('title', 'Eventos')

@push('styles')
    <style>
        /* ===== CONTENEDOR GENERAL ===== */
        .panel-wrapper {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            padding: 24px;
        }

        /* TARJETA PRINCIPAL (TODO EL PANEL) */
        .panel-card {
            width: 100%;
            max-width: 1200px;  /* Asegura que ocupe un buen tamaño */
            min-height: 540px;
            background: linear-gradient(135deg, #1e3a8a, #1d4ed8);
            border-radius: 24px;
            box-shadow: 0 20px 45px rgba(0,0,0,0.55);
            display: flex;
            flex-direction: row;  /* Permitir que la barra lateral y el contenido se alineen horizontalmente */
            overflow: hidden;
            color: #e5e7eb;
            position: relative;
            padding: 24px;
        }

        /* ===== SIDEBAR ===== */
        .panel-sidebar {
            width: 250px;
            background: linear-gradient(180deg, #4c1d95, #7c3aed);
            padding: 18px 14px;
            display: flex;
            flex-direction: column;
            border-radius: 24px 0 0 24px;
            min-height: 100vh;  /* Asegura que la barra lateral cubra toda la altura */
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

        /* ===== MENU ===== */
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
            transition: background 0.3s ease, color 0.3s ease;
        }

        .sidebar-link i {
            font-size: 1.1rem;
        }

        .sidebar-link.active {
            background: #111827;
            border-color: rgba(248,250,252,0.25);
            color: #f9fafb;
        }

        .sidebar-link:hover {
            background: #111827;
            color: #f9fafb;
        }

        /* ===== CONTENIDO PRINCIPAL ===== */
        .panel-main {
            flex: 1;
            padding: 20px 26px 26px;
            display: flex;
            flex-direction: column;
        }

        /* Header con botón de usuario */
        .panel-header {
            display: flex;
            justify-content: flex-start;
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

        .user-badge {
            margin-left: auto;
        }

        /* Estilo para el contenido */
        .panel-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        /* Actividad */
        .activity-card {
            background: #0f3446;
            border-radius: 18px;
            padding: 16px 18px;
            box-shadow: 0 8px 20px rgba(15,23,42,0.55);
        }
        .activity-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 0.9rem;
        }
        .activity-list li {
            margin-bottom: 4px;
            display: flex;
            align-items: flex-start;
            gap: 6px;
        }
        .activity-list span.emoji {
            font-size: 1.1rem;
            line-height: 1.1;
        }

        /* ===== ESTILOS DEL CONTENIDO DE LOS EVENTOS ===== */
        .events-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            justify-content: space-between;
        }

        .event-card {
            background-color: #111827;
            padding: 24px;
            border-radius: 12px;
            width: 48%;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .event-card h2 {
            font-size: 1.5rem;
            color: #fff;
        }

        .event-card p {
            color: #cbd5f5;
            font-size: 1rem;
        }

        .event-card .emoji {
            font-size: 1.5rem;
        }

        .event-card img {
            width: 100%;
            border-radius: 8px;
            object-fit: cover;
        }

        /* ===== RESPONSIVO ===== */
        @media (max-width: 960px) {
            .panel-card {
                flex-direction: column;
                max-width: 720px;
            }
            .panel-sidebar {
                width: 100%;
                flex-direction: row;
                align-items: center;
                border-radius: 24px 24px 0 0;
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

            .event-card {
                width: 100%;
            }
        }

        @media (max-width: 780px) {
            .panel-main {
                padding: 16px 18px 20px;
            }
        }
    </style>
@endpush

@section('content')
<div class="panel-wrapper">
    <div class="panel-card">

        {{-- SIDEBAR IZQUIERDA --}}
        <aside class="panel-sidebar">
            <div class="sidebar-top">
                <a href="javascript:history.back()">
                    <button class="sidebar-back" type="button">
                        <i class="bi bi-arrow-left"></i>
                    </button>
                </a>
                <div class="sidebar-logo">
                    <img src="{{ asset('imagenes/logo-ito.png') }}" alt="Logo">
                </div>
            </div>

            <div class="sidebar-middle">
                <ul class="sidebar-menu">
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('panel.participante') }}">
                            <i class="bi bi-house-door-fill"></i>
                            <span>Inicio</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link active" href="{{ route('panel.eventos') }}">
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
        </aside>

        {{-- CONTENIDO PRINCIPAL --}}
        <main class="panel-main">

            {{-- HEADER SUPERIOR --}}
            <header class="panel-header">
                <div class="user-badge">
                    <div class="user-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <span>Participante</span>
                </div>
            </header>

            {{-- CONTENIDO DE LOS EVENTOS --}}
            <section class="panel-content">
                <h1>Eventos a los que te puedes inscribir</h1>
                <div class="events-wrapper">
                    <div class="event-card">
                        <h2>🌿 Torneo</h2>
                        <p>Competencia donde programadores resuelven retos o problemas de código para ver quién obtiene la mejor solución.</p>
                    </div>

                    <div class="event-card">
                        <h2>🔥 Feria</h2>
                        <p>Evento donde se presentan proyectos tecnológicos, productos e innovaciones para que el público los conozca.</p>
                    </div>

                    <div class="event-card">
                        <h2>💡 Desarrollo</h2>
                        <p>Actividad o taller donde los programadores crean o mejoran software, aprendiendo nuevas herramientas o tecnologías.</p>
                    </div>

                    <div class="event-card">
                        <h2>🎮 HackaTec / Hackathon</h2>
                        <p>Evento contrarreloj donde equipos desarrollan una idea o proyecto tecnológico desde cero en pocas horas.</p>
                    </div>
                </div>
            </section>

        </main>

    </div>
</div>
@endsection
