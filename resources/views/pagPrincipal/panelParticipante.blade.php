@extends('layouts.admin')

@section('title', 'Panel participante')

@push('styles')
<style>
/* ===== CONTENEDOR GENERAL ===== */
.panel-wrapper {
    min-height: 100vh;
    display: flex;
    justify-content: stretch;
    align-items: stretch;
    padding: 0;
}

/* TARJETA PRINCIPAL (TODO EL PANEL) */
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
    border-radius: 24px 0 0 24px;
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

/* sección navegación */
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

/* opción activa */
.sidebar-link.active {
    background: #111827;
    border-color: rgba(248,250,252,0.25);
}

/* sección inferior (logout) */
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

/* header con botón de usuario */
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

/* contenido */
.panel-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 18px;
}

/* fila de tarjetas superiores */
.cards-row {
    display: grid;
    grid-template-columns: repeat(3, minmax(0, 1fr));
    gap: 16px;
}

/* tarjeta base */
.card {
    background: #0f3b57;
    border-radius: 18px;
    padding: 14px 16px;
    box-shadow: 0 8px 20px rgba(15,23,42,0.45);
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

/* tarjeta con icono */
.card-with-icon {
    display: grid;
    grid-template-columns: auto 1fr;
    gap: 10px;
    align-items: center;
}
.card-icon {
    width: 40px;
    height: 40px;
    border-radius: 14px;
    background: #047857;
    display: flex;
    align-items: center;
    justify-content: center;
}
.card-icon i {
    font-size: 1.4rem;
}

.card-title {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: #bfdbfe;
}
.card-main-text {
    font-size: 1.1rem;
    font-weight: 600;
    color: #e5e7eb;
}
.card-subtext {
    font-size: 0.85rem;
    color: #cbd5f5;
}

/* tarjeta de progreso */
.card-progress {
    background: #0f4c5c;
}
.card-progress-value {
    font-size: 1rem;
    font-weight: 600;
    color: #4ade80;
}

/* tarjeta de actividad */
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
}

@media (max-width: 780px) {
    .cards-row {
        grid-template-columns: 1fr;
    }
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
<aside class="panel-sidebar" id="sidebar">

    {{-- FLECHA ATRÁS + LOGO --}}
    <div class="sidebar-top">
        <button class="sidebar-back" type="button">
            <i class="bi bi-arrow-left"></i>
        </button>
        <div class="sidebar-logo">
            <img src="{{ asset('imagenes/logo-ito.png') }}" alt="Logo">
        </div>
    </div>

    <div class="sidebar-middle">
        {{-- SECCIÓN MENÚ --}}
        <p class="sidebar-section-title">Menú</p>
        <ul class="sidebar-menu">
            {{-- Inicio --}}
            <li class="sidebar-item">
                <a class="sidebar-link active" href="{{ route('panel.participante') }}">
                    <i class="bi bi-house-door-fill"></i>
                    <span>Inicio</span>
                </a>
            </li>

            {{-- Eventos --}}
            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('panel.eventos') }}">
                    <i class="bi bi-calendar-event"></i>
                    <span>Eventos</span>
                </a>
            </li>

            {{-- Mi perfil --}}
            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('panel.perfil') }}">
                    <i class="bi bi-person"></i>
                    <span>Mi perfil</span>
                </a>
            </li>
        </ul>

        {{-- SECCIÓN EQUIPO --}}
        <p class="sidebar-section-title">Equipo</p>
        <ul class="sidebar-menu">
            {{-- Mi equipo --}}
            <li class="sidebar-item">
    <a class="sidebar-link" href="{{ route('panel.mi-equipo') }}">
        <i class="bi bi-people"></i> <span>Mi equipo</span>
    </a>
</li>


            {{-- Lista de equipo --}}
            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('panel.lista-equipo') }}">
                    <i class="bi bi-list-task"></i>
                    <span>Lista de equipo</span>
                </a>
            </li>

            {{-- Crear equipo --}}
            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('panel.teams.create') }}">
                    <i class="bi bi-plus-circle"></i>
                    <span>Crear equipo</span>
                </a>
            </li>

            {{-- Lista eventos --}}
            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('panel.lista-eventos') }}">
                    <i class="bi bi-calendar2-week"></i>
                    <span>Lista eventos</span>
                </a>
            </li>

            {{-- Submisión del proyecto --}}
            <li class="sidebar-item">
                <a class="sidebar-link" href="{{ route('panel.submission') }}">
                    <i class="bi bi-file-earmark-arrow-up"></i>
                    <span>Submisión del proyecto</span>
                </a>
            </li>

        </ul>
    </div>

    {{-- BOTÓN SALIR --}}
    <div class="sidebar-bottom">
         <form method="POST" action="{{ route('logout') }}">
        @csrf
            <button type="submit" class="sidebar-logout" style="background:none;border:none;color:inherit;cursor:pointer;">
                <i class="bi bi-box-arrow-right"></i>
                <span>Salir</span>
            </button>
        </form>
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

            {{-- CONTENIDO --}}
            <section class="panel-content">

                {{-- TARJETAS SUPERIORES --}}
                <div class="cards-row">
                    <div class="card">
                        <div class="card-title">Próximo evento</div>
                        <div class="card-main-text">HackaTec</div>
                        <div class="card-subtext">15 de septiembre</div>
                    </div>

                    <div class="card card-with-icon">
                        <div class="card-icon">
                            <i class="bi bi-calendar-event"></i>
                        </div>
                        <div>
                            <div class="card-title">Próximo evento</div>
                            <div class="card-main-text">HackaTec</div>
                            <div class="card-subtext">15 de septiembre</div>
                        </div>
                    </div>

                    <div class="card card-progress">
                        <div class="card-title">Progreso de roles</div>
                        <div class="card-main-text">4/4 completo</div>
                        <div class="card-progress-value">Completado</div>
                    </div>
                </div>

                {{-- ACTIVIDAD --}}
                <div class="activity-card">
                    <div class="activity-title">Actividad</div>
                    <ul class="activity-list">
                        <li>
                            <span class="emoji">🔴</span>
                            <span>El evento HackaTec está próximo a comenzar.</span>
                        </li>
                        <li>
                            <span class="emoji">❗</span>
                            <span>Se ha unido recientemente Alfredo.</span>
                        </li>
                        <li>
                            <span class="emoji">❗</span>
                            <span>Se ha unido recientemente Liz.</span>
                        </li>
                    </ul>
                </div>

            </section>
        </main>

    </div>
</div>

@if(session('logout_success'))
    {{-- SweetAlert2 desde CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: 'success',
                title: '¡Sesión cerrada!',
                text: "{{ session('logout_success') }}",
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#2563eb'
            });
        });
    </script>
@endif
@endsection
