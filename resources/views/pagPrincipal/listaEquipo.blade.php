@extends('layouts.admin')

@section('title', 'Lista de equipo')

@push('styles')
<style>
/* ===== CONTENEDOR GENERAL PANTALLA COMPLETA ===== */
.lista-equipo-fullscreen {
    width: 100%;
    min-height: 100vh;
    padding: 0;              /* Sin márgenes internos extra */
    margin: 0;
    background: #020617;     /* O transparente, según tu layout */
}

/* Ya no usamos panel-wrapper, lo puedes borrar si quieres */
.panel-wrapper {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 24px;
    background-color: #0f172a; 
    position: relative;
}

/* ===== CONTENEDOR PRINCIPAL SIN TARJETA, FULL WIDTH ===== */
.panel-card {
    width: 100%;
    max-width: 100%;                 /* ❗ Quita límite de 1100px */
    min-height: 100vh;               /* Ocupa toda la pantalla */
    background: transparent;         /* ❗ Sin degradado */
    border-radius: 0;                /* ❗ Sin bordes redondeados */
    box-shadow: none;                /* ❗ Sin sombra de tarjeta */
    display: flex;
    overflow: hidden;
    color: #e5e7eb;
    position: relative;
    z-index: 1;
}

/* ===== SIDEBAR ===== */
.panel-sidebar {
    width: 250px;
    background: linear-gradient(180deg, #4c1d95, #7c3aed);
    padding: 18px 14px;
    display: flex;
    flex-direction: column;
    border-radius: 0;                /* ❗ para que se vea pegado al borde */
    flex-shrink: 0;
}

/* ... TODO LO DEMÁS IGUAL QUE YA TENÍAS ... */

.sidebar-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
.sidebar-back { width: 32px; height: 32px; border-radius: 999px; border: 1px solid rgba(255, 255, 255, 0.35); display: flex; align-items: center; justify-content: center; background: transparent; color: #f9fafb; cursor: pointer; }
.sidebar-logo img { height: 40px; }
.sidebar-middle { flex: 1; display: flex; flex-direction: column; gap: 18px; overflow-y: auto; }
.sidebar-section-title { font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.06em; color: #c4b5fd; margin-bottom: 4px; }
.sidebar-nav { list-style: none; margin: 0; padding: 0; }
.sidebar-item { margin-bottom: 6px; }
.sidebar-link { display: flex; align-items: center; gap: 10px; padding: 8px 10px; border-radius: 999px; font-size: 0.9rem; text-decoration: none; color: #e5e7eb; transition: background 0.2s, transform 0.2s; }
.sidebar-link i { font-size: 1.1rem; }
.sidebar-link:hover { background: rgba(15, 23, 42, 0.3); transform: translateX(2px); }
.sidebar-link.active { background: #020617; box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5); }
.sidebar-bottom { padding-top: 12px; border-top: 1px solid rgba(248, 250, 252, 0.18); }
.sidebar-logout { display: inline-flex; align-items: center; gap: 8px; padding: 8px 10px; border-radius: 999px; color: #fecaca; cursor: pointer; }

/* ===== CONTENIDO PRINCIPAL ===== */
.panel-main { flex: 1; padding: 20px 26px 26px; display: flex; flex-direction: column; overflow-x: hidden; }
.panel-main-inner { width: 100%; margin: 0 auto; }
.panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; }
.panel-title { font-size: 1.6rem; font-weight: 700; }
.user-badge { display: inline-flex; align-items: center; gap: 8px; border-radius: 999px; padding: 6px 14px; background: rgba(15, 23, 42, 0.9); border: 1px solid rgba(148, 163, 184, 0.6); font-size: 0.85rem; white-space: nowrap; }

/* ===== BUSCADOR + FILTROS ===== */
.team-search { background: rgba(15, 23, 42, 0.3); border-radius: 18px; padding: 16px; display: flex; flex-direction: column; gap: 12px; margin-bottom: 16px; }
.team-search-input-wrapper { position: relative; width: 100%; }
.team-search-input-wrapper input { width: 100%; padding: 10px 14px 10px 38px; border-radius: 999px; border: none; outline: none; font-size: 0.9rem; background: #f9fafb; color: #111827; }
.search-icon { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #6b7280; }
.team-filters { display: flex; flex-wrap: wrap; gap: 12px; }
.btn-chip { border: none; border-radius: 16px; padding: 10px 18px; background: #0f3355; color: #e5e7eb; box-shadow: 0 8px 16px rgba(15, 23, 42, 0.45); font-size: 0.9rem; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; text-decoration: none; }
.btn-chip:hover { color: #fff; background: #14426b; }
.btn-chip.primary { background: #0f3c7a; }

/* ===== TABLA ===== */
.team-table-wrapper { background: rgba(15, 23, 42, 0.65); border-radius: 18px; padding: 14px 16px 18px; box-shadow: 0 12px 30px rgba(0, 0, 0, 0.5); }
.team-table { width: 100%; border-spacing: 0; border-collapse: collapse; }
.team-table thead th { text-align: left; padding: 10px 12px; font-size: 0.9rem; font-weight: 600; color: #e5e7eb; border-bottom: 1px solid rgba(255,255,255,0.1); }
.team-table tbody tr { border-radius: 12px; transition: background 0.2s; }
.team-table tbody tr:hover { background: rgba(15, 23, 42, 0.7); }
.team-table tbody td { padding: 12px 12px; font-size: 0.9rem; vertical-align: middle; border-bottom: 1px solid rgba(255,255,255,0.05); }
.team-table tbody tr:last-child td { border-bottom: none; }
.avatar-stack { display: flex; }
.avatar-stack img { width: 28px; height: 28px; border-radius: 999px; border: 2px solid #0b2b4a; object-fit: cover; margin-left: -8px; }
.avatar-stack img:first-child { margin-left: 0; }
.btn-info { border-radius: 999px; border: none; padding: 6px 16px; font-size: 0.8rem; background: #e5e7eb; color: #111827; display: inline-flex; align-items: center; gap: 6px; cursor: pointer; }

/* ===== PANEL DE DETALLES (OVERLAY) ===== */
/* Lo hacemos de pantalla completa también */
.details-overlay {
    position: fixed;              /* ❗ En vez de absolute */
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    z-index: 999;
    display: none;
    justify-content: flex-end;
    border-radius: 0;             /* ❗ Sin esquinas redondeadas */
}
.details-overlay.active { display: flex; }
.join-btn-modern {
    width: 100%;
    padding: 14px 22px;
    border-radius: 14px;
    font-size: 1.05rem;
    font-weight: 600;
    color: #ffffff;
    background: linear-gradient(135deg, #4c1d95, #7c3aed);
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.25s ease-in-out;
    box-shadow: 0 0 12px rgba(124, 58, 237, 0.35);
}

.join-btn-modern i {
    font-size: 1.2rem;
}

.join-btn-modern:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 22px rgba(167, 139, 250, 0.65);
    background: linear-gradient(135deg, #5b21b6, #8b5cf6);
}

.join-btn-modern:active {
    transform: scale(0.97);
}

.join-btn-modern:disabled {
    background: #6b7280 !important;
    cursor: not-allowed;
    box-shadow: none;
    opacity: 0.7;
}

.details-panel {
    width: 320px;
    height: 100%;
    background: #1e293b;
    border-left: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: -10px 0 30px rgba(0,0,0,0.5);
    padding: 24px;
    display: flex;
    flex-direction: column;
    animation: slideIn 0.3s ease-out;
}
@keyframes slideIn {
    from { transform: translateX(100%); }
    to { transform: translateX(0); }
}

/* ... resto de estilos tal cual los tenías ... */

.details-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 10px; }
.details-title { font-size: 1.2rem; font-weight: 700; color: #fff; }
.details-close { background: none; border: none; color: #9ca3af; font-size: 1.5rem; cursor: pointer; }

.alert-full {
    background: linear-gradient(90deg, #7f1d1d, #991b1b);
    color: #fecaca; padding: 10px; border-radius: 8px; font-size: 0.85rem; margin-bottom: 20px;
    display: flex; align-items: center; gap: 8px;
}

.members-list { list-style: none; padding: 0; margin: 0; flex: 1; overflow-y: auto; }
.member-item { display: flex; align-items: center; gap: 12px; margin-bottom: 16px; }
.member-avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid #3b82f6; }
.member-info { display: flex; flex-direction: column; gap: 4px; }
.member-name { color: #f3f4f6; font-weight: 600; font-size: 0.95rem; }
.member-role { color: #94a3b8; font-size: 0.75rem; font-weight: 400; }

.join-btn-container { margin-top: auto; padding-top: 20px; }
.btn-join { width: 100%; padding: 12px; border-radius: 12px; border: none; font-weight: 600; cursor: pointer; background: #2563eb; color: white; display: flex; justify-content: center; align-items: center; gap: 8px; }
.btn-join:hover { background: #1d4ed8; }
.btn-join:disabled { background: #475569; cursor: not-allowed; color: #94a3b8; }

/* ===== PAGINACIÓN COMPACTA ===== */
.pagination-wrapper {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.pagination-info {
    color: #94a3b8;
    font-size: 0.75rem;
    font-weight: 500;
}

.pagination {
    display: flex;
    gap: 4px;
    list-style: none;
    padding: 0;
    margin: 0;
    align-items: center;
}

.pagination .page-item {
    display: inline-block;
}

.pagination .page-link {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    padding: 0 8px;
    background: rgba(30, 41, 59, 0.5);
    border: 1px solid rgba(148, 163, 184, 0.2);
    border-radius: 6px;
    color: #cbd5e1;
    text-decoration: none;
    font-weight: 500;
    font-size: 0.8rem;
    transition: all 0.2s ease;
}

.pagination .page-link:hover {
    background: rgba(79, 70, 229, 0.2);
    border-color: #6366f1;
    color: #fff;
}

.pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #4f46e5, #7c3aed);
    border-color: transparent;
    color: #fff;
    font-weight: 600;
}

.pagination .page-item.disabled .page-link {
    background: rgba(15, 23, 42, 0.3);
    border-color: rgba(148, 163, 184, 0.15);
    color: #475569;
    cursor: not-allowed;
    opacity: 0.5;
}

.pagination .page-item.disabled .page-link:hover {
    background: rgba(15, 23, 42, 0.3);
    border-color: rgba(148, 163, 184, 0.15);
}

/* Puntos suspensivos */
.pagination .page-item span {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 32px;
    height: 32px;
    color: #64748b;
    font-size: 0.8rem;
}

/* ===== MODAL DE SELECCIÓN DE ROL ===== */
.role-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(6px);
    z-index: 2000;
    display: none;
    justify-content: center;
    align-items: center;
}

.role-modal-overlay.active {
    display: flex;
}

.role-modal-content {
    background: linear-gradient(135deg, #1e293b, #0f172a);
    border: 1px solid rgba(148, 163, 184, 0.3);
    border-radius: 20px;
    padding: 32px;
    width: 90%;
    max-width: 500px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
    animation: modalSlideIn 0.3s ease-out;
}

@keyframes modalSlideIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-20px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.role-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(148, 163, 184, 0.2);
}

.role-modal-header h3 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #f1f5f9;
    margin: 0;
}

.role-modal-close {
    background: none;
    border: none;
    font-size: 2rem;
    color: #94a3af;
    cursor: pointer;
    padding: 0;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.2s ease;
}
/* ===== PAGINACIÓN COMPACTA LISTA EQUIPO ===== */
.teams-pagination {
    margin: 24px auto 8px;              /* centrado y separado del contenido */
    padding: 8px 16px;
    border-radius: 999px;
    background: rgba(15, 23, 42, 0.95); /* azul oscuro */
    display: flex;
    align-items: center;
    gap: 18px;
    width: auto;                        /* NO ocupar todo el ancho */
    max-width: 100%;
    font-size: 0.85rem;
    color: #e5e7eb;
}

.teams-pagination-info {
    white-space: nowrap;
    opacity: 0.85;
}

.teams-pagination-pages {
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Botones de página */
.page-number,
.page-arrow {
    min-width: 32px;
    height: 32px;
    padding: 0 10px;
    border-radius: 999px;
    border: 1px solid #1d4ed8;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    text-decoration: none;
    color: #e5e7eb;
    background: transparent;
    transition: background 0.18s ease, color 0.18s ease, transform 0.12s ease;
    cursor: pointer;
}

.page-number:hover,
.page-arrow:hover {
    background: #1d4ed8;
    color: #f9fafb;
    transform: translateY(-1px);
}

/* Página actual */
.page-number.active {
    background: #1d4ed8;
    color: #f9fafb;
    border-color: #2563eb;
    font-weight: 600;
}

/* Deshabilitados */
.page-arrow.disabled {
    opacity: 0.35;
    border-color: #4b5563;
    cursor: default;
    pointer-events: none;
}

.role-modal-close:hover {
    background: rgba(148, 163, 184, 0.15);
    color: #e2e8f0;
}

.role-modal-body {
    padding: 0;
}

.role-options {
    display: grid;
    grid-template-columns: 1fr;
    gap: 12px;
    margin-bottom: 24px;
}

.role-option {
    cursor: pointer;
}

.role-option input[type="radio"] {
    display: none;
}

.role-option input[type="radio"]:checked + .role-card {
    background: linear-gradient(135deg, #4c1d95, #7c3aed);
    border-color: #a78bfa;
    box-shadow: 0 0 20px rgba(124, 58, 237, 0.4);
    transform: translateY(-2px);
}

.role-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 20px;
    background: rgba(15, 23, 42, 0.6);
    border: 2px solid rgba(148, 163, 184, 0.3);
    border-radius: 14px;
    transition: all 0.3s ease;
    color: #cbd5e1;
}

.role-card:hover {
    background: rgba(15, 23, 42, 0.9);
    border-color: rgba(148, 163, 184, 0.5);
}

.role-card i {
    font-size: 2.5rem;
    color: #a78bfa;
}

.role-card span {
    font-size: 1.05rem;
    font-weight: 600;
    color: #e2e8f0;
}

.role-submit-btn {
    width: 100%;
    padding: 14px 24px;
    background: linear-gradient(135deg, #4c1d95, #7c3aed);
    color: white;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    transition: all 0.3s ease;
    box-shadow: 0 0 15px rgba(124, 58, 237, 0.3);
}

.role-submit-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 0 25px rgba(124, 58, 237, 0.5);
    background: linear-gradient(135deg, #5b21b6, #8b5cf6);
}

.role-submit-btn:active {
    transform: scale(0.98);
}

.role-submit-btn i {
    font-size: 1.2rem;
}

/* RESPONSIVE */
@media (max-width: 1024px) {
    .panel-card { flex-direction: column; min-height: auto; }
    .panel-sidebar { width: 100%; border-radius: 0; }
    .sidebar-top { margin-bottom: 10px; }
    .sidebar-middle { flex-direction: row; gap: 10px; overflow-x: auto; padding-bottom: 10px; }
    .sidebar-nav { display: flex; gap: 8px; }
    .sidebar-section-title { display: none; }
    .sidebar-link { white-space: nowrap; font-size: 0.85rem; padding: 6px 12px; }
    .panel-main { padding: 16px; }
    .details-panel { width: 100%; max-width: 300px; }
}

@media (max-width: 768px) {
    .team-table thead { display: none; }
    .team-table tbody, .team-table tr, .team-table td { display: block; width: 100%; }
    .team-table tr { margin-bottom: 16px; background: rgba(255, 255, 255, 0.05); border-radius: 12px; padding: 12px; border: 1px solid rgba(255, 255, 255, 0.1); }
    .team-table td { display: flex; justify-content: space-between; align-items: center; text-align: right; padding: 8px 0; border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
    .team-table td:last-child { border-bottom: none; justify-content: center; padding-top: 12px; }
    .team-table td::before { content: attr(data-label); font-weight: 700; color: #9ca3af; text-transform: uppercase; font-size: 0.75rem; margin-right: 12px; }
    
    .btn-chip { width: 100%; justify-content: center; }
    .team-filters { flex-direction: column; }
    .panel-header { flex-direction: column; align-items: flex-start; gap: 10px; }
    .user-badge { width: 100%; justify-content: center; }
    
    /* Paginación responsive */
    .pagination-wrapper {
        flex-wrap: wrap;
        gap: 6px;
    }
    
    .pagination-info {
        font-size: 0.7rem;
    }
    
    .pagination .page-link {
        min-width: 30px;
        height: 30px;
        font-size: 0.75rem;
        padding: 0 6px;
    }
}
</style>
@endpush


@section('content')
<div class="lista-equipo-fullscreen">
    <div class="panel-card">

        {{-- ===== SIDEBAR ===== --}}
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
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.participante') }}"><i class="bi bi-house-door"></i> <span>Inicio</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.eventos') }}"><i class="bi bi-calendar-event"></i> <span>Eventos</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.perfil') }}"><i class="bi bi-person"></i> <span>Mi perfil</span></a></li>
                    </ul>
                </div>

                <div style="margin-top: 20px;">
                    <div class="sidebar-section-title">Equipo</div>
                    <ul class="sidebar-nav">
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.mi-equipo') }}"><i class="bi bi-people"></i> <span>Mi equipo</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link active" href="{{ route('panel.lista-equipo') }}"><i class="bi bi-list-ul"></i> <span>Lista de equipo</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.teams.create') }}"><i class="bi bi-plus-circle"></i> <span>Crear equipo</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.lista-eventos') }}"><i class="bi bi-calendar-week"></i> <span>Lista eventos</span></a></li>
                        <li class="sidebar-item"><a class="sidebar-link" href="{{ route('panel.submission') }}"><i class="bi bi-file-earmark-arrow-up"></i> <span>Submision del proyecto</span></a></li>
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
            <div class="panel-main-inner">

               <header class="panel-header">
                    <h1 class="panel-title">Lista de equipo</h1>
                    <div class="user-badge">
                        <i class="bi bi-person-circle"></i>
                        {{-- 
                            AQUÍ ESTÁ EL CAMBIO EN LISTA:
                            Se muestra siempre el usuario autenticado sin importar la ruta exacta.
                        --}}
                        <span>{{ auth()->check() ? auth()->user()->name : 'Usuario' }}</span>
                    </div>
                </header>


                <section class="team-search">
                    <div class="team-search-input-wrapper">
                        <span class="search-icon"><i class="bi bi-search"></i></span>
<input type="text" id="teamSearchInput" placeholder="Buscar equipo...">
                    </div>
                    <div class="team-filters">
                        <button class="btn-chip"><i class="bi bi-funnel"></i> Filtrar</button>
                        <a href="{{ route('panel.teams.create') }}" class="btn-chip primary" style="margin-left: auto;">
                            <i class="bi bi-plus-lg"></i> Crear nuevo
                        </a>
                    </div>
                </section>

                <section class="team-table-wrapper">
                    <table class="team-table">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Fecha</th>
                                <th>Miembros</th>
                                <th>Acción</th>
                            </tr>
                        </thead>
                        <tbody>
    @forelse ($teams ?? [] as $team)
        @php
        $membersCount = $team->members ? $team->members->count() : 0;
        $leader       = $team->leader;                 // 👈 ahora viene directo de BD
        $leaderName   = $leader?->name ?? 'Líder';
        $yaEsMiembro  = $team->members ? $team->members->contains(auth()->id()) : false;
        $esLider      = auth()->id() === optional($leader)->id;
        @endphp

        <tr>
            <td data-label="Nombre">{{ $team->name }}</td>
            <td data-label="Fecha">{{ optional($team->created_at)->format('Y-m-d') }}</td>
            
            <td data-label="Miembros">
                <div class="avatar-stack">
                    {{-- Mostrar hasta 4 avatares de miembros reales --}}
                    @foreach (($team->members ?? collect())->take(4) as $member)
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($member->name) }}&background=random" 
                             alt="{{ $member->name }}">
                    @endforeach
                </div>
                <small style="color:#9ca3af; margin-left:5px;">({{ $membersCount }}/4)</small>
            </td>
            
            <td data-label="Acción">
    <button class="btn-info"
        onclick="openTeamDetails(this)"
        data-team-id="{{ $team->id }}"
        data-team-name="{{ $team->name }}"
        data-leader-name="{{ $leaderName }}"
        data-members-count="{{ $membersCount }}"
        data-is-member="{{ $yaEsMiembro ? '1' : '0' }}"
        data-is-leader="{{ $esLider ? '1' : '0' }}"
        data-members='@json(
            ($team->members ?? collect())->map(function($m) use ($leader) {
                $roleText = $m->id === optional($leader)->id ? "Líder" : ($m->pivot->role ?? "");
                return [
                    "name" => $m->name,
                    "role" => $roleText,
                ];
            })
        )'
    >
        Ver Detalles <i class="bi bi-chevron-right"></i>
    </button>
</td>


        </tr>
    @empty
        <tr>
            <td colspan="4" style="text-align: center; padding: 24px; color: #9ca3af;">
                <div style="display:flex; flex-direction:column; align-items:center; gap:10px;">
                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                    <span>No hay equipos creados aún.</span>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
                    </table>

                    {{-- PAGINACIÓN COMPACTA --}}
@if ($teams->hasPages())
    <div class="teams-pagination">
        <span class="teams-pagination-info">
            Mostrando {{ $teams->firstItem() }} - {{ $teams->lastItem() }} de {{ $teams->total() }} equipos
        </span>

        <div class="teams-pagination-pages">
            {{-- Flecha Anterior --}}
            @if ($teams->onFirstPage())
                <span class="page-arrow disabled">&laquo;</span>
            @else
                <a href="{{ $teams->previousPageUrl() }}" class="page-arrow">&laquo;</a>
            @endif

            {{-- Números de página --}}
            @foreach ($teams->getUrlRange(1, $teams->lastPage()) as $page => $url)
                @if ($page == $teams->currentPage())
                    <span class="page-number active">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="page-number">{{ $page }}</a>
                @endif
            @endforeach

            {{-- Flecha Siguiente --}}
            @if ($teams->hasMorePages())
                <a href="{{ $teams->nextPageUrl() }}" class="page-arrow">&raquo;</a>
            @else
                <span class="page-arrow disabled">&raquo;</span>
            @endif
        </div>
    </div>
@endif

                </section>

            </div>
        </main>

        {{-- ===== PANEL DESLIZANTE DE DETALLES (OVERLAY) ===== --}}
        <div class="details-overlay" id="detailsOverlay">
            <div class="details-panel" id="detailsPanel">
                
                {{-- Cabecera del Panel --}}
                <div class="details-header">
                    <span class="details-title" id="panelTeamName">Nombre Equipo</span>
                    <button class="details-close" onclick="closeTeamDetails()">&times;</button>
                </div>

                {{-- Alerta si está lleno (Se muestra con JS) --}}
                <div class="alert-full" id="panelAlertFull" style="display: none;">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                    <span>El equipo esta lleno, no puedes inscribirte intenta otro.</span>
                </div>

                <div style="color: #9ca3af; font-size: 0.8rem; margin-bottom: 10px; text-transform: uppercase; letter-spacing: 1px;">
                    Miembros
                </div>

                {{-- Lista de Miembros (Se llena con JS) --}}
                <ul class="members-list" id="panelMembersList">
                    {{-- Los items se inyectan aquí --}}
                </ul>

              {{-- Botón Unirse --}}
                <div class="join-btn-container">
                    {{-- Botón que abre el modal de selección de rol --}}
                    <button id="btnJoinTeam" class="join-btn-modern" type="button" onclick="openRoleModal()">
                        <i class="bi bi-person-plus-fill"></i> Unirme al equipo
                    </button>
                </div>

            </div>
        </div>

        {{-- ===== MODAL DE SELECCIÓN DE ROL ===== --}}
        <div class="role-modal-overlay" id="roleModalOverlay">
            <div class="role-modal-content">
                <div class="role-modal-header">
                    <h3>Selecciona tu rol en el equipo</h3>
                    <button class="role-modal-close" onclick="closeRoleModal()">&times;</button>
                </div>

                <div class="role-modal-body">
                    <p style="color: #cbd5e1; margin-bottom: 20px;">¿Qué rol deseas desempeñar en este equipo?</p>
                    
                    <form id="roleSelectionForm" action="{{ route('panel.teams.join') }}" method="POST">
                        @csrf
                        <input type="hidden" id="roleTeamId" name="team_id" value="">
                        
                        <div class="role-options">
                            <label class="role-option">
                                <input type="radio" name="role" value="Back" required>
                                <div class="role-card">
                                    <i class="bi bi-cpu"></i>
                                    <span>Back-end</span>
                                </div>
                            </label>

                            <label class="role-option">
                                <input type="radio" name="role" value="Front" required>
                                <div class="role-card">
                                    <i class="bi bi-palette"></i>
                                    <span>Front-end</span>
                                </div>
                            </label>

                            <label class="role-option">
                                <input type="radio" name="role" value="Diseñador" required>
                                <div class="role-card">
                                    <i class="bi bi-pencil-square"></i>
                                    <span>Diseñador</span>
                                </div>
                            </label>
                        </div>

                        <button type="submit" class="role-submit-btn">
                            <i class="bi bi-check-circle"></i> Confirmar rol
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ===== SCRIPTS PARA LA LÓGICA DEL PANEL ===== --}}
{{-- ===== SCRIPTS PARA LA LÓGICA DEL PANEL ===== --}}
<script>
    let currentTeamIdForRole = null;

    function addMemberToList(list, name, role) {
        const li = document.createElement('li');
        li.className = 'member-item';
        li.innerHTML = `
            <img class="member-avatar"
                 src="https://ui-avatars.com/api/?name=${encodeURIComponent(name)}&background=random"
                 alt="${name}">
            <div class="member-info">
                <span class="member-name">${name}</span>
                <span class="member-role">${role}</span>
            </div>
        `;
        list.appendChild(li);
    }

    // btn es el botón que se clicó
    function openTeamDetails(btn) {
        const id          = btn.dataset.teamId;
        const name        = btn.dataset.teamName;
        const leaderName  = btn.dataset.leaderName;
        const count       = parseInt(btn.dataset.membersCount || '0', 10);
        const isMember    = btn.dataset.isMember === '1';
        const isLeader    = btn.dataset.isLeader === '1';
        const members     = JSON.parse(btn.dataset.members || '[]');

        const overlay = document.getElementById('detailsOverlay');
        overlay.classList.add('active');

        document.getElementById('panelTeamName').textContent = name;

        const alertBox = document.getElementById('panelAlertFull');
        const joinBtn  = document.getElementById('btnJoinTeam');

        const maxMembers  = 4;
        const equipoLleno = count >= maxMembers;

        alertBox.style.display = equipoLleno ? 'flex' : 'none';

        if (equipoLleno) {
            joinBtn.disabled         = true;
            joinBtn.textContent      = 'Equipo completo';
            joinBtn.style.background = '#475569';
        } else if (isMember) {
            joinBtn.disabled         = true;
            joinBtn.textContent      = 'Ya eres miembro';
            joinBtn.style.background = '#475569';
        } else if (isLeader) {
            joinBtn.disabled         = true;
            joinBtn.textContent      = 'Eres líder de este equipo';
            joinBtn.style.background = '#475569';
        } else {
            joinBtn.disabled         = false;
            joinBtn.innerHTML        = '<i class="bi bi-person-plus-fill"></i> Unirme al equipo';
            joinBtn.style.background = 'linear-gradient(135deg, #4c1d95, #7c3aed)';
            // Guardar el team_id para usar en openRoleModal
            currentTeamIdForRole = id;
        }

        // Pintar miembros en la lista
        const list = document.getElementById('panelMembersList');
        list.innerHTML = '';

        members.forEach(m => addMemberToList(list, m.name, m.role));
    }

    function closeTeamDetails() {
        document.getElementById('detailsOverlay').classList.remove('active');
    }

    // Abre el modal de selección de rol
    function openRoleModal() {
        const joinBtn = document.getElementById('btnJoinTeam');
        
        // Solo permitir si el botón no está deshabilitado
        if (joinBtn.disabled) {
            return;
        }

        // Establecer el team_id en el form
        document.getElementById('roleTeamId').value = currentTeamIdForRole;
        
        // Mostrar modal
        document.getElementById('roleModalOverlay').classList.add('active');
        
        // Resetear selección de rol
        document.querySelectorAll('input[name="role"]').forEach(input => {
            input.checked = false;
        });
    }

    function closeRoleModal() {
        document.getElementById('roleModalOverlay').classList.remove('active');
    }

    // Cerrar modal si se hace click fuera
    document.getElementById('roleModalOverlay').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRoleModal();
        }
    });

     // --- BÚSQUEDA EN TIEMPO REAL ---
    const searchInput = document.getElementById('teamSearchInput');
    const tableRows   = document.querySelectorAll('.team-table tbody tr');

    if (searchInput) {
        searchInput.addEventListener('input', function () {
            const term = this.value.toLowerCase().trim();

            tableRows.forEach(row => {
                const nameCell = row.querySelector('td[data-label="Nombre"]');
                if (!nameCell) return; // filas de "sin equipos" se ignoran

                const nameText = nameCell.textContent.toLowerCase();
                if (term === '' || nameText.includes(term)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }
</script>
@endsection
