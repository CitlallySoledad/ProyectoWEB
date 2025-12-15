@extends('layouts.admin')

@section('title', 'Cambiar contraseña')

@push('styles')
<style>
    /* ===== CONTENEDOR GENERAL ===== */
    .panel-wrapper {
        min-height: 100vh;
        display: flex;
        justify-content: stretch;
        align-items: stretch;
        padding: 0;
        margin: 0;
        background: #020617;
    }

    /* TARJETA PRINCIPAL */
    .panel-card {
        width: 100%;
        max-width: none;
        min-height: 100vh;
        background: linear-gradient(135deg, #1e3a8a, #1d4ed8);
        border-radius: 0;
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
    }

    .sidebar-top {
        display: flex;
        align-items: center;
        justify-content: flex-start;
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

    .sidebar-logo {
        margin-left: auto;
        padding-left: 20px;
    }

    .sidebar-logo img {
        height: 40px;
    }

    .sidebar-middle {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

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
        color: #ffffff;
        cursor: pointer;
        transition: background-color 0.3s ease, color 0.3s ease, transform 0.2s ease;
    }

    .sidebar-link span,
    .sidebar-link i {
        color: #ffffff !important;
    }

    .sidebar-link:hover {
        background-color: rgba(255,255,255,0.15);
        transform: translateX(2px);
    }

    .sidebar-link.active {
        background-color: rgba(15,23,42,0.9);
        box-shadow: 0 0 0 1px rgba(15,23,42,0.6);
    }

    /* icono salir abajo */
.logout-btn {
    width: 100%;
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 12px 16px;
    border-radius: 12px; /* igual que los otros botones */
    background: transparent;
    border: none;
    color: #ffffff;
    cursor: pointer;
    font-size: 1rem;
    text-align: left;
    transition: background-color 0.3s ease;
}

.logout-btn:hover {
    background-color: rgba(255,255,255,0.15);
}

.logout-btn i,
.logout-btn span {
    color: #ffffff !important;
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
        justify-content: flex-end;
        align-items: center;
        margin-bottom: 8px;
    }

    .user-badge {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 14px;
        border-radius: 999px;
        background: rgba(15,23,42,0.9);
        border: 1px solid rgba(148,163,184,0.9);
        font-size: 0.85rem;
        color: #e5e7eb;
    }

    .user-avatar {
        width: 26px;
        height: 26px;
        border-radius: 999px;
        background: #020617;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .user-avatar i {
        font-size: 1rem;
        color: #e5e7eb;
    }

    .panel-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    /* ===== LAYOUT CAMBIAR CONTRASEÑA ===== */
    .password-layout {
        display: flex;
        align-items: flex-start;
        gap: 24px;
    }

    .password-main {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 18px;
    }

    /* Imagen del candado */
    .password-hero {
        background: rgba(15,23,42,0.55);
        border-radius: 18px;
        padding: 24px 32px;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .password-hero img {
        max-width: 260px;
        height: auto;
    }

    /* Banda de texto informativo */
    .password-info-banner {
        background: #065f46;
        border-radius: 14px;
        padding: 10px 18px;
        text-align: center;
        font-size: 0.9rem;
        line-height: 1.4;
        color: #e5f9f0;
        margin-top: 4px;
    }

    /* Columna derecha: mensaje de éxito */
    .password-status {
        width: 260px;
    }

    .status-card {
        background: #dbeafe;
        color: #111827;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 0.9rem;
        box-shadow: 0 4px 12px rgba(15,23,42,0.35);
    }

    .status-card p {
        margin: 0;
    }

    /* FORMULARIO */
    .password-form {
        margin-top: 10px;
    }

    .password-label {
        display: block;
        font-weight: 700;
        font-size: 1.02rem;
        color: #f9fafb;
        margin-bottom: 6px;
        margin-top: 4px;
    }

    .password-input {
        width: 100%;
        border-radius: 10px;
        border: none;
        padding: 10px 14px;
        font-size: 1rem;
        color: #111827;
        margin-bottom: 14px;
    }

    .password-input:focus {
        outline: 2px solid #2563eb;
        box-shadow: 0 0 0 2px rgba(37,99,235,0.4);
    }

    .password-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 10px;
    }

    .password-btn {
        border: none;
        border-radius: 8px;
        padding: 8px 28px;
        background: #020617;
        color: #f9fafb;
        font-size: 0.95rem;
        cursor: pointer;
        font-weight: 500;
        transition: background-color 0.25s ease, transform 0.15s ease, box-shadow 0.15s ease;
    }

    .password-btn:hover {
        background: #0f172a;
        box-shadow: 0 8px 18px rgba(15,23,42,0.4);
        transform: translateY(-1px);
    }

    /* RESPONSIVO */
    @media (max-width: 960px) {
        .panel-card {
            flex-direction: column;
        }

        .password-layout {
            flex-direction: column;
        }

        .password-status {
            width: 100%;
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
                <button class="sidebar-back" type="button" onclick="window.history.back();">
                    <i class="bi bi-arrow-left"></i>
                </button>

                <div class="sidebar-logo">
                    <img src="{{ asset('imagenes/logo-ito.png') }}" alt="Logo">
                </div>
            </div>

            <div class="sidebar-middle">
                <ul class="sidebar-menu">
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="{{ route('panel.perfil') }}">
                            <i class="bi bi-person-fill"></i>
                            <span>Mi perfil</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="javascript:void(0)">
                            <i class="bi bi-file-earmark-person-fill"></i>
                            <span>Datos personales</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        <a class="sidebar-link" href="javascript:void(0)">
                            <i class="bi bi-people-fill"></i>
                            <span>Mi equipo</span>
                        </a>
                    </li>
                    <li class="sidebar-item">
                        {{-- 👇 ahora sí apunta a la ruta propia --}}
                        <a class="sidebar-link active" href="{{ route('panel.cambiarContrasena') }}">
                            <i class="bi bi-lock-fill"></i>
                            <span>Cambiar contraseña</span>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="sidebar-bottom">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Cerrar sesión</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- CONTENIDO PRINCIPAL --}}
        <main class="panel-main">

            {{-- HEADER SUPERIOR --}}
            <header class="panel-header">
                <a href="{{ route('panel.perfil') }}" class="user-badge" style="text-decoration: none; color: inherit; cursor: pointer;">
                    <div class="user-avatar">
                        <i class="bi bi-person-fill"></i>
                    </div>
                    <span>{{ auth()->user()->name ?? 'Usuario' }}</span>
                </a>
            </header>

            {{-- Botón para volver al panel --}}
            <div style="padding: 20px 40px;">
                <a href="{{ route('panel.participante') }}" class="btn btn-secondary" style="display: inline-flex; align-items: center; gap: 8px;">
                    <i class="bi bi-arrow-left"></i>
                    Volver al Panel
                </a>
            </div>

            <section class="panel-content">
                <div class="password-layout">

                    {{-- COLUMNA IZQUIERDA: IMAGEN + TEXTO + FORM --}}
                    <div class="password-main">
                        <div class="password-hero">
                            <img src="{{ asset('imagenes/candado-password.png') }}" alt="Cambiar contraseña">
                        </div>

                        <div class="password-info-banner">
                            En seguida podrás cambiar tu contraseña actual del sistema,
                            recuerda que debe tener 8 caracteres como mínimo, 1 número y 1 carácter especial.
                        </div>

                        {{-- FORMULARIO REAL --}}
                        <form class="password-form" action="{{ route('panel.cambiarContrasena.update') }}" method="POST">
                            @csrf

                            <label class="password-label" for="current_password">Contraseña actual</label>
                            <input
                                type="password"
                                id="current_password"
                                class="password-input"
                                name="current_password"
                                required
                            >

                            <label class="password-label" for="new_password">Contraseña nueva</label>
                            <input
                                type="password"
                                id="new_password"
                                class="password-input"
                                name="new_password"
                                required
                            >

                            <label class="password-label" for="new_password_confirmation">Repite la contraseña nueva</label>
                            <input
                                type="password"
                                id="new_password_confirmation"
                                class="password-input"
                                name="new_password_confirmation"
                                required
                            >

                            <div class="password-actions">
                                <button type="submit" class="password-btn">
                                    Cambiar
                                </button>
                            </div>
                        </form>

                        {{-- ERRORES DE VALIDACIÓN --}}
                        @if ($errors->any())
                            <div class="status-card" style="margin-top: 10px; background:#fee2e2; color:#111;">
                                <ul style="margin:0; padding-left:18px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>

                    {{-- COLUMNA DERECHA: MENSAJE DE ÉXITO / ERROR --}}
                    <aside class="password-status">
                        @if (session('success'))
                            <div class="status-card">
                                <p>{{ session('success') }}</p>
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="status-card" style="background:#fed7d7;">
                                <p>{{ session('error') }}</p>
                            </div>
                        @endif
                    </aside>

                </div>
            </section>

        </main>

    </div>
</div>
@endsection