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

    {{-- Toolbar de filtros (decorativo por ahora) --}}
    <div class="judge-toolbar mb-3">
        <button class="judge-toolbar-btn" type="button">
            <span><i class="bi bi-funnel me-2"></i>Filtro</span>
            <i class="bi bi-chevron-down"></i>
        </button>
        <button class="judge-toolbar-btn" type="button">
            <span>Últimos 7 días</span>
            <i class="bi bi-chevron-down"></i>
        </button>
        <button class="judge-toolbar-btn" type="button"
                onclick="window.location='{{ route('judge.evaluations.index') }}'">
            <span>Ver mis evaluaciones</span>
            <i class="bi bi-arrow-right-short"></i>
        </button>
    </div>

    <div class="admin-card">
        <div class="admin-card-title">Proyectos</div>

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
            @forelse($projects as $project)
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
                                <a href="{{ asset('storage/' . $doc->file_path) }}" 
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
                        @if($project->rubric_id)
                            <button class="judge-pill-evaluate"
                                    onclick="window.location='{{ route('judge.evaluations.show', $project) }}'">
                                Evaluar
                            </button>
                        @else
                            <button class="judge-pill-evaluate" disabled title="No hay rúbrica asignada">
                                Evaluar
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">No hay proyectos para evaluar.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        {{ $projects->links() }}
    </div>
@endsection
