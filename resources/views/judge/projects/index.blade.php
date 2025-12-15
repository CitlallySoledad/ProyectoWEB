@extends('layouts.judge-panel')

@section('title', 'Proyectos a evaluar')

@push('styles')
<style>
    .judge-search-bar {
        border-radius: 999px;
        border: none;
        padding: 10px 16px;
        font-size: 0.95rem;
        width: 100%;
    }
    .judge-toolbar {
        display: flex;
        gap: 16px;
        margin-bottom: 18px;
    }
    .judge-toolbar-btn {
        flex: 1;
        background: #0f3b57;
        border-radius: 16px;
        padding: 10px 16px;
        border: none;
        display: flex;
        align-items: center;
        justify-content: space-between;
        color: #e5e7eb;
        box-shadow: 0 10px 25px rgba(0,0,0,0.4);
        font-size: 0.95rem;
    }
    .judge-pill-evaluate {
        border-radius: 999px;
        padding: 6px 18px;
        border: none;
        background: #fbbf24;
        font-weight: 600;
        color: #111827;
        font-size: 0.9rem;
    }
</style>
@endpush

@section('content')
    <h1 class="h3 mb-3">Lista de proyectos a evaluar</h1>

    {{-- Buscador --}}
    <div class="mb-3">
        <input type="text"
               class="judge-search-bar"
               placeholder="Buscar proyecto"
               value="{{ request('q') }}"
               onkeydown="if(event.key==='Enter'){window.location='?q='+encodeURIComponent(this.value)}">
    </div>

    {{-- Toolbar de acciones --}}
    <div class="judge-toolbar mb-3">
        <button class="judge-toolbar-btn" type="button"
                onclick="window.location='{{ route('judge.evaluations.index') }}'">
            <span>Ver mis evaluaciones</span>
            <i class="bi bi-arrow-right-short"></i>
        </button>
    </div>

    @forelse($projectsByEvent as $eventId => $projects)
        @php
            $event = $events->get($eventId);
        @endphp
        
        <div class="admin-card mb-4">
            <div class="admin-card-title" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem 1.5rem; border-radius: 8px 8px 0 0; display: flex; justify-content: space-between; align-items: center;">
                <span><i class="bi bi-calendar-event me-2"></i>{{ $event ? $event->title : 'Evento sin nombre' }}</span>
                <span class="badge" style="background-color: rgba(255,255,255,0.2); padding: 0.4rem 0.8rem; font-size: 0.85rem;">
                    {{ $projects->count() }} {{ $projects->count() == 1 ? 'proyecto' : 'proyectos' }}
                </span>
            </div>

            <table class="admin-table">
                <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Rúbrica</th>
                    <th>Proyecto</th>
                    <th>Estado</th>
                    <th>Miembros</th>
                    <th>Documentos</th>
                    <th style="width: 150px;">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $project->team?->name ?? '-' }}</td>
                        <td>
                            {{ $project->rubric?->name ?? '—' }}
                        </td>
                        <td>{{ $project->name }}</td>
                        <td class="text-capitalize">{{ $project->status }}</td>
                        <td>
                            @if($project->team && $project->team->members->isNotEmpty())
                                {{ $project->team->members->pluck('name')->join(', ') }}
                            @else
                                <span style="color: #94a3b8; font-style: italic;">Sin miembros</span>
                            @endif
                        </td>
                        <td>
                            @if($project->documents->isNotEmpty())
                                @foreach($project->documents as $doc)
                                    <a href="{{ asset($doc->file_path) }}" 
                                       target="_blank" 
                                       class="judge-pill-evaluate" 
                                       style="display: inline-block; margin: 2px; padding: 4px 10px; font-size: 0.85rem; text-decoration: none;">
                                        <i class="bi bi-file-pdf"></i> {{ $doc->original_name }}
                                    </a>
                                @endforeach
                            @else
                                <span style="color: #94a3b8; font-style: italic; font-size: 0.85rem;">Sin documentos</span>
                            @endif
                        </td>
                        <td>
                            @if($project->already_evaluated ?? false)
                                <button class="judge-pill-evaluate" 
                                        style="background: #22c55e; cursor: default;"
                                        title="Ya evaluaste este proyecto">
                                    <i class="bi bi-check-circle"></i> Evaluado
                                </button>
                            @elseif($project->rubric_id)
                                <button class="judge-pill-evaluate"
                                        onclick="window.location='{{ route('judge.evaluations.show', $project) }}'">
                                    Evaluar
                                </button>
                            @else
                                <button class="judge-pill-evaluate" disabled title="No hay rúbrica asignada">
                                    Sin rúbrica
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @empty
        <div class="admin-card">
            <div class="admin-card-title">Proyectos</div>
            <p class="text-center py-4" style="color: #94a3b8; font-style: italic;">
                No hay proyectos para evaluar.
            </p>
        </div>
    @endforelse
@endsection
