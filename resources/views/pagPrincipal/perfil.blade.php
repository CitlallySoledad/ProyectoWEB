@extends('layouts.admin')

@section('title', 'Mi perfil')

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
            max-width: 1100px;
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
            width: 250px;
            background: linear-gradient(180deg, #4c1d95, #7c3aed);
            padding: 18px 14px;
            display: flex;
            flex-direction: column;
            border-radius: 24px 0 0 24px;
        }

        /* flecha atrás + logo */
        .sidebar-top {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            margin-bottom: 24px;
        }

        /* Mueve la flecha a la izquierda para regresar */
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

        /* Mueve el logo a la derecha */
        .sidebar-logo {
            margin-left: auto;  /* Esto mueve el logo hacia la derecha */
            padding-left: 20px; /* Espacio entre la flecha y el logo */
        }

        .sidebar-logo img {
            height: 40px;
        }

        /* ===== ESTILO DEL MENÚ DE LA BARRA LATERAL ===== */
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }

        .sidebar-item {
            margin-bottom: 14px;
        }

        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            border-radius: 12px;
            font-size: 1rem;
            text-decoration: none;
            color: #e5e7eb;
            cursor: pointer;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .sidebar-link:hover {
            background-color: #3b0a45;
            color: #ffffff;
        }

        /* Elemento activo */
        .sidebar-link.active {
            background-color: #2d2a61;
            color: #ffffff;
        }

        /* ===== CONTENIDO PRINCIPAL ===== */
        .panel-main {
            flex: 1;
            padding: 20px 26px 26px;
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .panel-header {
            display: flex;
            justify-content: flex-end; /* Mueve el "Participante" a la derecha */
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

        /* Imagen de Perfil y Datos */
        .profile-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 32px;
            margin-top: 20px; /* Baja la imagen */
        }

        .profile-image {
            width: 160px;
            height: 160px;
            border-radius: 8px;
            background: #ddd;
            object-fit: cover;
        }

        .profile-details {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .profile-info {
            background: #111827;
            padding: 24px;
            border-radius: 16px;
        }

        /* RESPONSIVO */
        @media (max-width: 960px) {
            .panel-card {
                flex-direction: column;
                max-width: 720px;
            }

            .sidebar-top {
                margin-bottom: 0;
                gap: 14px;
            }

            .sidebar-bottom {
                margin-left: auto;
            }

            /* Baja la imagen de perfil y la centra */
            .profile-wrapper {
                flex-direction: column;
                align-items: center;
            }

            .profile-image {
                margin-top: 30px;
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
                <a href="javascript:void(0)">
                    <button class="sidebar-back" type="button" onclick="window.history.back();">
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
                        <a class="sidebar-link active" href="javascript:void(0)">
                            <i class="bi bi-person-fill"></i>
                            <span>Mi perfil</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="javascript:void(0)">
                            <i class="bi bi-person-fill"></i>
                            <span>Datos personales</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="javascript:void(0)">
                            <i class="bi bi-person-fill"></i>
                            <span>Mi equipo</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="javascript:void(0)">
                            <i class="bi bi-lock-fill"></i>
                            <span>Cambiar contraseña</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="javascript:void(0)">
                            <i class="bi bi-box-arrow-right"></i>
                            <span>Cerrar sesión</span>
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

            {{-- CONTENIDO DE PERFIL --}}
            <section class="panel-content">
                <h1>Detalles de Mi Perfil</h1>
                <div class="profile-wrapper">
                    <div class="profile-image">
                        <img src="{{ asset('imagenes/foto-perfil.jpg') }}" alt="Imagen de perfil">
                    </div>
                    <div class="profile-details">
                        <div class="profile-info">
                            <p><strong>Nombre:</strong> Dario Ruiz Hernandez</p>
                            <p><strong>CURP:</strong> TARA0401HOTYRL8</p>
                            <p><strong>Fecha de nacimiento:</strong> 12 de octubre de 1998</p>
                            <p><strong>Género:</strong> Masculino</p>
                            <p><strong>Estado civil:</strong> Soltero</p>
                            <p><strong>Teléfono:</strong> 9515670980</p>
                            <p><strong>Correo electrónico:</strong> daris45ruiz212@gmail.com</p>
                        </div>

                        {{-- Mi equipo --}}
                        <div class="profile-info">
                            <h3>Mi equipo</h3>
                            <ul>
                                <li><strong>Alfredo:</strong> Diseñador</li>
                                <li><strong>Liz:</strong> Back</li>
                                <li><strong>Citally:</strong> Front</li>
                                <li><strong>Yo:</strong> Líder</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>

        </main>

    </div>
</div>
@endsection

