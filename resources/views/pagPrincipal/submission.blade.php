@extends('layouts.admin')

@section('title', 'Submisión del proyecto')

@push('styles')
<style>
    /* ===== CONTENEDOR FULLSCREEN ===== */
    .submission-fullscreen {
        width: 100%;
        min-height: 100vh;
        margin: 0;
        padding: 0;
        background: #020617;
    }

    .panel-card {
        width: 100%;
        max-width: 100%;
        min-height: 100vh;
        background: transparent;
        border-radius: 0;
        box-shadow: none;
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

    /* ===== RESPONSIVE ===== */
    @media (max-width: 1024px) {
        .panel-card {
            flex-direction: column;
        }

        .panel-sidebar {
            width: 100%;
        }

        .sidebar-middle {
            flex-direction: row;
            gap: 10px;
            overflow-x: auto;
            padding-bottom: 10px;
        }

        .sidebar-menu {
            display: flex;
            gap: 8px;
        }

        .sidebar-section-title {
            display: none;
        }

        .panel-main {
            padding: 16px;
        }
    }

    @media (max-width: 900px) {
        .submission-layout {
            grid-template-columns: 1fr;
        }

        .submission-right {
            order: -1;
        }
    }

    @media (max-width: 768px) {
        .panel-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }

        .user-badge {
            width: 100%;
            justify-content: center;
        }
    }
</style>
@endpush

@section('content')
<div class="submission-fullscreen">
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

                    <a href="{{ route('panel.perfil') }}" class="user-badge" style="text-decoration: none; color: inherit; cursor: pointer;">
                        <i class="bi bi-person-circle"></i>
                        <span>{{ auth()->user()->name ?? 'Usuario' }}</span>
                    </a>
                </div>

                @if(session('success'))
                    <div class="alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @php
                    $projectName = $project ? $project->name : '';
                    $selectedTeamId = old('team_id', $project ? $project->team_id : null);
                    $selectedEventId = old('event_id', $project ? $project->event_id : null);
                @endphp

                <div class="submission-layout" id="editFormSection">
                    {{-- COLUMNA IZQUIERDA: formulario principal --}}
                    <div class="submission-left">
                        <form action="{{ route('panel.submission.update') }}" method="POST">
                            @csrf

                            {{-- Selección de equipo --}}
                            <div class="mb-2">
                                <div class="submission-label">Equipo <span style="color: #dc3545;">*</span></div>
                                
                                @if($eligibleTeams->isEmpty())
                                    <div class="alert alert-warning" style="padding: 12px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; margin-bottom: 1rem;">
                                        <strong>⚠️ No tienes equipos</strong>
                                        <p style="margin: 8px 0 0 0; font-size: 14px;">
                                            Para enviar un proyecto debes ser líder de un equipo.
                                        </p>
                                    </div>
                                @else
                                    <select name="team_id" class="submission-input" required>
                                        <option value="">Selecciona un equipo</option>
                                        @foreach($eligibleTeams as $team)
                                            <option value="{{ $team->id }}" 
                                                    {{ $selectedTeamId == $team->id ? 'selected' : '' }}>
                                                {{ $team->name }} 
                                                ({{ $team->members_count }} miembros)
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                                
                                @error('team_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Selección de evento --}}
                            <div class="mb-2">
                                <div class="submission-label">Evento <span style="color: #dc3545;">*</span></div>
                                
                                @if($activeEvents->isEmpty())
                                    <div class="alert alert-warning" style="padding: 12px; background: #fff3cd; border: 1px solid #ffc107; border-radius: 8px; margin-bottom: 1rem;">
                                        <strong>⚠️ No hay eventos activos</strong>
                                        <p style="margin: 8px 0 0 0; font-size: 14px;">
                                            Tu equipo debe estar inscrito en un evento activo para enviar proyectos.
                                        </p>
                                    </div>
                                @else
                                    <select name="event_id" class="submission-input" required>
                                        <option value="">Selecciona un evento</option>
                                        @foreach($activeEvents as $event)
                                            <option value="{{ $event->id }}" 
                                                    {{ ($project && $project->event_id == $event->id) || old('event_id') == $event->id ? 'selected' : '' }}>
                                                {{ $event->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                @endif
                                
                                @error('event_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                                
                                @if($activeEvents->isNotEmpty())
                                    <div class="submission-hint">
                                        Selecciona el evento al cual enviarás tu proyecto. Solo puedes elegir eventos en los que tu equipo esté inscrito.
                                    </div>
                                @endif
                            </div>

                            {{-- Nombre del proyecto --}}
                            <div class="mb-2">
                                <div class="submission-label">Nombre de proyecto</div>
                                <input
                                    type="text"
                                    name="project_name"
                                    class="submission-input"
                                    placeholder="Nombre de proyecto"
                                    value="{{ old('project_name', $projectName) }}"
                                    {{ $eligibleTeams->isEmpty() ? 'disabled' : '' }}
                                    @if($eligibleTeams->isNotEmpty())
                                    minlength="5"
                                    maxlength="255"
                                    title="Mínimo 5 caracteres para el nombre del proyecto"
                                    @endif
                                >
                                @error('project_name')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                @if(!$projectName)
                                    <div class="submission-hint">
                                        Escribe el nombre del proyecto que tu equipo está desarrollando para el evento.
                                    </div>
                                @endif
                            </div>

                            {{-- Esta parte se conecta con los botones de la derecha usando JS submit --}}
                            <input type="submit" id="submit-hidden" style="display:none">
                        </form>
                    </div>

                    {{-- COLUMNA DERECHA: acciones --}}
                    <div class="submission-right">
                        <div class="submission-right-title">Acciones de la submisión</div>

                        <p class="submission-right-small">
                            Selecciona tu equipo, nombra tu proyecto y guarda los cambios.
                        </p>

                        <button class="btn-rounded btn-save" type="button"
                                onclick="document.getElementById('submit-hidden').click();"
                                {{ $eligibleTeams->isEmpty() ? 'disabled style=opacity:0.5;cursor:not-allowed;' : '' }}>
                            Guardar cambios
                        </button>

                        <hr style="border-color: rgba(148,163,184,0.4); margin: 16px 0;">

                        <p class="submission-right-small">
                            Crea un nuevo proyecto o edita uno existente abajo.
                        </p>
                        @if($projects && $projects->count() > 0)
                        <p class="submission-right-small" style="margin-top: 12px; color: #22c55e;">
                            <i class="bi bi-check-circle"></i> Tienes {{ $projects->count() }} proyecto(s) guardado(s)
                        </p>
                        @endif
                        
                        @if($eligibleTeams->isEmpty())
                            <small class="text-muted d-block mt-2" style="font-size: 0.85rem; color: #94a3b8;">
                                Debes cumplir los requisitos para gestionar repositorios.
                            </small>
                        @elseif($project && $project->status === 'enviado')
                            <small class="text-muted d-block mt-2" style="font-size: 0.85rem; color: #94a3b8;">
                                No puedes subir documentos a un proyecto ya enviado.
                            </small>
                        @endif
                    </div>
                </div>

                {{-- Mostrar TODOS los proyectos guardados con sus documentos (ABAJO) --}}
                @if($projects && $projects->count() > 0)
                    @if($projects->count() > 1)
                    <div style="margin-top: 24px; margin-bottom: 16px;">
                        <h2 style="color: #e5e7eb; font-size: 1.1rem; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                            <i class="bi bi-folder-fill" style="color: #8b5cf6;"></i>
                            Tus proyectos ({{ $projects->count() }})
                        </h2>
                        <p style="color: #94a3b8; font-size: 0.9rem; margin: 8px 0 0 0;">
                            Puedes gestionar cada proyecto de forma independiente según el equipo y evento.
                        </p>
                    </div>
                    @endif
                    
                    @foreach($projects as $proj)
                    <div style="background: rgba(34, 197, 94, 0.1); border: 1px solid rgba(34, 197, 94, 0.5); border-radius: 16px; padding: 20px; margin-top: 24px;">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                            <div style="display: flex; align-items: center; gap: 12px;">
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(34, 197, 94, 0.2); display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-check-circle" style="font-size: 24px; color: #22c55e;"></i>
                                </div>
                                <div>
                                    <h3 style="margin: 0; font-size: 1.2rem; font-weight: 600; color: #e5e7eb;">{{ $proj->name }}</h3>
                                    <p style="margin: 4px 0 0 0; font-size: 0.9rem; color: #94a3b8;">
                                        Equipo: {{ $proj->team->name }} | Evento: {{ $proj->event->title ?? 'N/A' }}
                                        @if($proj->status === 'enviado')
                                            <span style="display: inline-flex; align-items: center; gap: 4px; padding: 2px 10px; background: rgba(34, 197, 94, 0.2); border: 1px solid rgba(34, 197, 94, 0.5); border-radius: 999px; font-size: 0.75rem; margin-left: 8px;">
                                                <i class="bi bi-lock-fill"></i> Enviado
                                            </span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                            @if($proj->status !== 'enviado')
                            <button type="button" 
                                    onclick="editProject({{ $proj->id }}, '{{ $proj->name }}', {{ $proj->team_id }}, {{ $proj->event_id }})"
                                    style="padding: 8px 16px; border-radius: 999px; border: 1px solid rgba(148, 163, 184, 0.6); background: rgba(15, 23, 42, 0.7); color: #e5e7eb; cursor: pointer; font-size: 0.9rem; display: flex; align-items: center; gap: 6px;"
                                    onmouseover="this.style.background='rgba(59, 130, 246, 0.2)'; this.style.borderColor='#3b82f6';"
                                    onmouseout="this.style.background='rgba(15, 23, 42, 0.7)'; this.style.borderColor='rgba(148, 163, 184, 0.6)';">
                                <i class="bi bi-pencil"></i>
                                <span>Editar proyecto</span>
                            </button>
                            @endif
                        </div>
                        
                        @if($proj->documents && $proj->documents->count() > 0)
                        <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(148, 163, 184, 0.3);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
                                <p style="margin: 0; font-size: 0.9rem; font-weight: 600; color: #e5e7eb;">
                                    <i class="bi bi-file-earmark-pdf"></i> Documentos subidos ({{ $proj->documents->count() }})
                                </p>
                            </div>
                            <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                                @foreach($proj->documents as $doc)
                                <div style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; background: rgba(15, 23, 42, 0.7); border: 1px solid rgba(148, 163, 184, 0.4); border-radius: 999px;">
                                    <a href="{{ asset('storage/' . $doc->file_path) }}" 
                                       target="_blank"
                                       style="display: inline-flex; align-items: center; gap: 8px; text-decoration: none; color: #fbbf24; font-size: 0.9rem;"
                                       onmouseover="this.style.color='#fde047';"
                                       onmouseout="this.style.color='#fbbf24';">
                                        <i class="bi bi-file-pdf-fill"></i>
                                        <span>{{ $doc->original_name }}</span>
                                        <small style="color: #94a3b8;">({{ number_format($doc->file_size / 1024, 1) }} KB)</small>
                                    </a>
                                    @if($proj->status !== 'enviado')
                                    <form action="{{ route('panel.submission.delete-pdf', $doc->id) }}" method="POST" style="display: inline; margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                onclick="return confirm('¿Estás seguro de eliminar este documento?')"
                                                style="background: none; border: none; color: #ef4444; cursor: pointer; padding: 0; font-size: 18px; line-height: 1;"
                                                title="Eliminar documento">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(148, 163, 184, 0.3);">
                            <p style="margin: 0; font-size: 0.85rem; color: #94a3b8; font-style: italic;">
                                <i class="bi bi-info-circle"></i> No hay documentos PDF subidos aún.
                            </p>
                        </div>
                        @endif
                        
                        {{-- Botones de acción por proyecto --}}
                        <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(148, 163, 184, 0.3); display: flex; gap: 12px; justify-content: center; flex-wrap: wrap;">
                            @if($proj->status !== 'enviado')
                                <button type="button" 
                                        class="btn-rounded btn-repo" 
                                        onclick="openPdfModal({{ $proj->id }})"
                                        style="padding: 10px 24px; border-radius: 999px; border: 1px solid rgba(139, 92, 246, 0.6); background: rgba(139, 92, 246, 0.1); color: #a78bfa; cursor: pointer; font-size: 0.95rem; display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s;"
                                        onmouseover="this.style.background='rgba(139, 92, 246, 0.2)'; this.style.borderColor='#8b5cf6';"
                                        onmouseout="this.style.background='rgba(139, 92, 246, 0.1)'; this.style.borderColor='rgba(139, 92, 246, 0.6)';">
                                    <i class="bi bi-cloud-upload"></i>
                                    <span>Subir PDF</span>
                                </button>
                            @endif
                            
                            @if($proj->documents && $proj->documents->count() > 0 && $proj->status !== 'enviado')
                                <form action="{{ route('panel.submission.confirm') }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de que deseas enviar este proyecto? Una vez enviado, NO PODRÁS editarlo ni subir más documentos.');">
                                    @csrf
                                    <input type="hidden" name="project_id" value="{{ $proj->id }}">
                                    <button type="submit" 
                                            style="padding: 10px 24px; border-radius: 999px; border: none; background: linear-gradient(135deg, #10b981, #059669); color: white; font-size: 0.95rem; font-weight: 600; cursor: pointer; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3); display: inline-flex; align-items: center; gap: 8px; transition: all 0.3s;"
                                            onmouseover="this.style.transform='scale(1.05)'; this.style.boxShadow='0 12px 25px rgba(16, 185, 129, 0.4)';"
                                            onmouseout="this.style.transform='scale(1)'; this.style.boxShadow='0 8px 20px rgba(16, 185, 129, 0.3)';">
                                        <i class="bi bi-send-fill"></i>
                                        <span>Enviar a jueces</span>
                                    </button>
                                </form>
                            @endif
                            
                            @if($proj->status === 'enviado')
                                <div style="padding: 12px 24px; background: rgba(34, 197, 94, 0.15); border: 1px solid rgba(34, 197, 94, 0.4); border-radius: 999px;">
                                    <p style="margin: 0; font-size: 0.9rem; color: #22c55e; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                                        <i class="bi bi-check-circle-fill"></i> Proyecto enviado correctamente
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @endif

            </div>
        </div>
    </div>
</div>

{{-- Modal para subir PDF --}}
<div id="pdfModal" class="modal-overlay" style="display: none;">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3 style="margin: 0; color: #1e293b; font-size: 20px; font-weight: 600;">
                📄 Subir Documento PDF
            </h3>
            <button type="button" class="modal-close" onclick="closePdfModal()">&times;</button>
        </div>
        
        <div class="modal-body" style="padding: 24px;">
            <form id="pdfUploadForm" action="{{ route('panel.submission.upload-pdf') }}" method="POST" enctype="multipart/form-data">
                @csrf
                {{-- El project_id se agrega dinámicamente por JavaScript cuando se abre el modal --}}
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; margin-bottom: 8px; font-weight: 500; color: #334155;">
                        Selecciona el archivo PDF
                    </label>
                    
                    <div class="file-upload-area" id="fileUploadArea">
                        <input type="file" 
                               id="pdfFile" 
                               name="pdf_file" 
                               accept=".pdf"
                               required
                               style="display: none;"
                               onchange="handleFileSelect(event)">
                        
                        <div class="file-upload-content" onclick="document.getElementById('pdfFile').click()">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="margin: 0 auto 12px; color: #8b5cf6;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p style="margin: 0; font-weight: 500; color: #1e293b;">Haz clic para seleccionar un PDF</p>
                            <p style="margin: 8px 0 0 0; font-size: 14px; color: #64748b;">o arrastra y suelta aquí</p>
                        </div>
                        
                        <div class="file-selected" id="fileSelected" style="display: none;">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" style="color: #8b5cf6;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <div style="flex: 1;">
                                <p id="fileName" style="margin: 0; font-weight: 500; color: #1e293b;"></p>
                                <p id="fileSize" style="margin: 4px 0 0 0; font-size: 14px; color: #64748b;"></p>
                            </div>
                            <button type="button" onclick="clearFile()" style="background: none; border: none; color: #ef4444; cursor: pointer; font-size: 20px;">×</button>
                        </div>
                    </div>
                    
                    <p style="margin: 8px 0 0 0; font-size: 13px; color: #64748b;">
                        Tamaño máximo: 10MB
                    </p>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label for="pdfDescription" style="display: block; margin-bottom: 8px; font-weight: 500; color: #334155;">
                        Descripción (opcional)
                    </label>
                    <textarea 
                        id="pdfDescription" 
                        name="description" 
                        rows="3" 
                        class="submission-input"
                        placeholder="Describe brevemente el contenido del documento..."
                        style="resize: vertical; min-height: 80px;"></textarea>
                </div>
                
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <button type="button" class="btn-rounded btn-cancel" onclick="closePdfModal()">
                        Cancelar
                    </button>
                    <button type="submit" class="btn-rounded btn-save">
                        📤 Subir PDF
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.2s ease-out;
}

.modal-content {
    background: white;
    border-radius: 16px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    animation: slideUp 0.3s ease-out;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid #e2e8f0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 28px;
    color: #94a3b8;
    cursor: pointer;
    line-height: 1;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.2s;
}

.modal-close:hover {
    background: #f1f5f9;
    color: #1e293b;
}

.file-upload-area {
    border: 2px dashed #cbd5e1;
    border-radius: 12px;
    transition: all 0.3s;
}

.file-upload-area:hover {
    border-color: #8b5cf6;
    background: #f9f5ff;
}

.file-upload-content {
    padding: 40px 20px;
    text-align: center;
    cursor: pointer;
}

.file-selected {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: #f9f5ff;
    border-radius: 10px;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes slideUp {
    from { 
        transform: translateY(20px);
        opacity: 0;
    }
    to { 
        transform: translateY(0);
        opacity: 1;
    }
}
</style>

<script>
    // Función para editar un proyecto específico
    function editProject(projectId, name, teamId, eventId) {
        // Scroll al formulario
        document.getElementById('editFormSection').scrollIntoView({ behavior: 'smooth', block: 'start' });
        
        // Llenar los campos del formulario
        document.querySelector('input[name="project_name"]').value = name;
        document.querySelector('select[name="team_id"]').value = teamId;
        document.querySelector('select[name="event_id"]').value = eventId;
    }

    function openPdfModal(projectId) {
        // Guardar el project_id en el formulario
        const form = document.getElementById('pdfUploadForm');
        let projectIdInput = form.querySelector('input[name="project_id"]');
        
        if (!projectIdInput) {
            projectIdInput = document.createElement('input');
            projectIdInput.type = 'hidden';
            projectIdInput.name = 'project_id';
            form.appendChild(projectIdInput);
        }
        
        projectIdInput.value = projectId;
        
        document.getElementById('pdfModal').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closePdfModal() {
        document.getElementById('pdfModal').style.display = 'none';
        document.body.style.overflow = 'auto';
        clearFile();
    }

    function handleFileSelect(event) {
        const file = event.target.files[0];
        if (file) {
            if (file.type !== 'application/pdf') {
                alert('Por favor selecciona un archivo PDF válido');
                event.target.value = '';
                return;
            }
            
            if (file.size > 10 * 1024 * 1024) { // 10MB
                alert('El archivo es demasiado grande. Tamaño máximo: 10MB');
                event.target.value = '';
                return;
            }
            
            document.getElementById('fileName').textContent = file.name;
            document.getElementById('fileSize').textContent = formatFileSize(file.size);
            document.querySelector('.file-upload-content').style.display = 'none';
            document.getElementById('fileSelected').style.display = 'flex';
        }
    }

    function clearFile() {
        document.getElementById('pdfFile').value = '';
        document.querySelector('.file-upload-content').style.display = 'block';
        document.getElementById('fileSelected').style.display = 'none';
    }

    function formatFileSize(bytes) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
    }

    // Drag and drop functionality
    const fileUploadArea = document.getElementById('fileUploadArea');
    
    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, preventDefaults, false);
    });

    function preventDefaults(e) {
        e.preventDefault();
        e.stopPropagation();
    }

    ['dragenter', 'dragover'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, () => {
            fileUploadArea.style.borderColor = '#8b5cf6';
            fileUploadArea.style.background = '#f9f5ff';
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        fileUploadArea.addEventListener(eventName, () => {
            fileUploadArea.style.borderColor = '#cbd5e1';
            fileUploadArea.style.background = 'transparent';
        }, false);
    });

    fileUploadArea.addEventListener('drop', (e) => {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            document.getElementById('pdfFile').files = files;
            handleFileSelect({ target: { files: files } });
        }
    }, false);

    // Close modal on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && document.getElementById('pdfModal').style.display === 'flex') {
            closePdfModal();
        }
    });

    // Close modal on overlay click
    document.getElementById('pdfModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'pdfModal') {
            closePdfModal();
        }
    });
    
    // Función para alternar entre vista de proyecto y formulario de edición
    function toggleEditMode() {
        const editSection = document.getElementById('editFormSection');
        if (editSection.style.display === 'none' || editSection.style.display === '') {
            editSection.style.display = 'grid';
            window.scrollTo({ top: 0, behavior: 'smooth' });
        } else {
            editSection.style.display = 'none';
            window.scrollTo({ top: document.body.scrollHeight, behavior: 'smooth' });
        }
    }
</script>
@endsection
