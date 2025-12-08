@extends('layouts.admin-panel')

@section('title', 'Asignación de Jurados')

@section('content')
<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 class="admin-card-title" style="margin-bottom: 0;">
            <i class="bi bi-person-badge"></i> Asignación de Jurados a Proyectos
        </h2>
        <button type="button" class="admin-btn-primary" onclick="openAutoAssignModal()">
            <i class="bi bi-magic"></i> Asignación Automática
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success" style="background: #065f46; border: 1px solid #10b981; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger" style="background: #7f1d1d; border: 1px solid #ef4444; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
            {{ session('error') }}
        </div>
    @endif

    @if(session('warning'))
        <div class="alert alert-warning" style="background: #78350f; border: 1px solid #f59e0b; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
            {{ session('warning') }}
        </div>
    @endif

    {{-- Filtros --}}
    <div style="margin-bottom: 20px; display: flex; gap: 12px; align-items: center;">
        <div style="flex: 1;">
            <label style="display: block; margin-bottom: 6px; font-size: 0.9rem;">Filtrar por evento:</label>
            <select id="eventFilter" class="form-select" onchange="filterProjects()" style="background: rgba(15,23,42,0.7); border: 1px solid rgba(148,163,184,0.4); color: #e5e7eb; border-radius: 8px;">
                <option value="">Todos los eventos</option>
                @foreach($events as $event)
                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                @endforeach
            </select>
        </div>
        <div style="flex: 1;">
            <label style="display: block; margin-bottom: 6px; font-size: 0.9rem;">Buscar proyecto:</label>
            <input type="text" id="searchProject" class="form-control" placeholder="Nombre del proyecto o equipo..." onkeyup="filterProjects()" style="background: rgba(15,23,42,0.7); border: 1px solid rgba(148,163,184,0.4); color: #e5e7eb; border-radius: 8px;">
        </div>
    </div>

    {{-- Estadísticas --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 24px;">
        <div style="background: rgba(59,130,246,0.2); border: 1px solid #3b82f6; padding: 16px; border-radius: 12px;">
            <div style="font-size: 0.85rem; color: #93c5fd; margin-bottom: 4px;">Total Proyectos</div>
            <div style="font-size: 1.8rem; font-weight: 700;">{{ $projects->count() }}</div>
        </div>
        <div style="background: rgba(139,92,246,0.2); border: 1px solid #8b5cf6; padding: 16px; border-radius: 12px;">
            <div style="font-size: 0.85rem; color: #c4b5fd; margin-bottom: 4px;">Total Jueces</div>
            <div style="font-size: 1.8rem; font-weight: 700;">{{ $judges->count() }}</div>
        </div>
        <div style="background: rgba(16,185,129,0.2); border: 1px solid #10b981; padding: 16px; border-radius: 12px;">
            <div style="font-size: 0.85rem; color: #6ee7b7; margin-bottom: 4px;">Asignaciones Totales</div>
            <div style="font-size: 1.8rem; font-weight: 700;">
                {{ $projects->sum(function($p) { return $p->judges->count(); }) }}
            </div>
        </div>
    </div>

    {{-- Tabla de proyectos --}}
    <div style="overflow-x: auto;">
        <table class="admin-table" id="projectsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Proyecto</th>
                    <th>Equipo</th>
                    <th>Evento</th>
                    <th>Jueces Asignados</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($projects as $project)
                    <tr class="project-row" data-event-id="{{ $project->event_id }}" data-project-name="{{ strtolower($project->name . ' ' . ($project->team ? $project->team->name : '')) }}">
                        <td>{{ $project->id }}</td>
                        <td>
                            <strong>{{ $project->name }}</strong>
                            <br>
                            <small style="color: #94a3b8;">{{ $project->visibility }}</small>
                        </td>
                        <td>{{ $project->team ? $project->team->name : 'Sin equipo' }}</td>
                        <td>{{ $project->event ? $project->event->name : 'Sin evento' }}</td>
                        <td>
                            <div style="display: flex; flex-wrap: wrap; gap: 6px;">
                                @forelse($project->judges as $judge)
                                    <span style="background: #7c3aed; padding: 4px 10px; border-radius: 999px; font-size: 0.8rem; display: inline-flex; align-items: center; gap: 6px;">
                                        {{ $judge->name }}
                                        <form method="POST" action="{{ route('admin.judge-assignment.remove') }}" style="display: inline; margin: 0;">
                                            @csrf
                                            <input type="hidden" name="project_id" value="{{ $project->id }}">
                                            <input type="hidden" name="judge_id" value="{{ $judge->id }}">
                                            <button type="submit" style="background: none; border: none; color: #fca5a5; cursor: pointer; padding: 0; line-height: 1;" onclick="return confirm('¿Remover este juez?')">×</button>
                                        </form>
                                    </span>
                                @empty
                                    <span style="color: #94a3b8; font-style: italic; font-size: 0.85rem;">Sin jueces asignados</span>
                                @endforelse
                            </div>
                        </td>
                        <td>
                            <button type="button" class="admin-btn-secondary" onclick="openAssignModal({{ $project->id }}, '{{ addslashes($project->name) }}')" style="font-size: 0.85rem; padding: 4px 12px;">
                                <i class="bi bi-plus-circle"></i> Asignar
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: #94a3b8;">
                            No hay proyectos registrados en el sistema.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Modal para asignar juez --}}
<div id="assignModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: #1e3a8a; color: #e5e7eb; border: 1px solid rgba(148,163,184,0.3);">
            <div class="modal-header" style="border-bottom: 1px solid rgba(148,163,184,0.3);">
                <h5 class="modal-title">Asignar Juez al Proyecto</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.judge-assignment.assign') }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="project_id" id="assignProjectId">
                    
                    <div style="margin-bottom: 16px;">
                        <label style="font-weight: 600; margin-bottom: 8px; display: block;">Proyecto:</label>
                        <div id="assignProjectName" style="padding: 10px; background: rgba(15,23,42,0.7); border-radius: 8px; border: 1px solid rgba(148,163,184,0.3);"></div>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="judgeSelect" style="font-weight: 600; margin-bottom: 8px; display: block;">Seleccionar Juez:</label>
                        <select name="judge_id" id="judgeSelect" class="form-select" required style="background: rgba(15,23,42,0.7); border: 1px solid rgba(148,163,184,0.4); color: #e5e7eb; border-radius: 8px;">
                            <option value="">-- Selecciona un juez --</option>
                            @foreach($judges as $judge)
                                <option value="{{ $judge->id }}">
                                    {{ $judge->name }} ({{ $judge->projects_count }} proyectos asignados)
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(148,163,184,0.3);">
                    <button type="button" class="admin-btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="admin-btn-primary">Asignar Juez</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal para asignación automática --}}
<div id="autoAssignModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content" style="background: #1e3a8a; color: #e5e7eb; border: 1px solid rgba(148,163,184,0.3);">
            <div class="modal-header" style="border-bottom: 1px solid rgba(148,163,184,0.3);">
                <h5 class="modal-title"><i class="bi bi-magic"></i> Asignación Automática de Jueces</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="{{ route('admin.judge-assignment.auto-assign') }}">
                @csrf
                <div class="modal-body">
                    <p style="margin-bottom: 20px; color: #cbd5e1;">
                        Este proceso distribuirá automáticamente los jueces entre los proyectos de manera equitativa.
                    </p>

                    <div style="margin-bottom: 16px;">
                        <label for="autoEventId" style="font-weight: 600; margin-bottom: 8px; display: block;">Filtrar por evento (opcional):</label>
                        <select name="event_id" id="autoEventId" class="form-select" style="background: rgba(15,23,42,0.7); border: 1px solid rgba(148,163,184,0.4); color: #e5e7eb; border-radius: 8px;">
                            <option value="">Todos los eventos</option>
                            @foreach($events as $event)
                                <option value="{{ $event->id }}">{{ $event->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 16px;">
                        <label for="judgesPerProject" style="font-weight: 600; margin-bottom: 8px; display: block;">Jueces por proyecto:</label>
                        <input type="number" name="judges_per_project" id="judgesPerProject" class="form-control" value="3" min="1" max="5" required style="background: rgba(15,23,42,0.7); border: 1px solid rgba(148,163,184,0.4); color: #e5e7eb; border-radius: 8px;">
                        <small style="color: #94a3b8; margin-top: 4px; display: block;">Se asignarán este número de jueces a cada proyecto</small>
                    </div>

                    <div style="background: rgba(251,191,36,0.1); border: 1px solid #f59e0b; padding: 12px; border-radius: 8px;">
                        <i class="bi bi-info-circle" style="color: #fbbf24;"></i>
                        <strong style="color: #fbbf24;">Nota:</strong> Esta acción respetará las asignaciones existentes y solo agregará jueces faltantes.
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(148,163,184,0.3);">
                    <button type="button" class="admin-btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="admin-btn-primary">Ejecutar Asignación</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function openAssignModal(projectId, projectName) {
        document.getElementById('assignProjectId').value = projectId;
        document.getElementById('assignProjectName').textContent = projectName;
        const modal = new bootstrap.Modal(document.getElementById('assignModal'));
        modal.show();
    }

    function openAutoAssignModal() {
        const modal = new bootstrap.Modal(document.getElementById('autoAssignModal'));
        modal.show();
    }

    function filterProjects() {
        const eventFilter = document.getElementById('eventFilter').value;
        const searchText = document.getElementById('searchProject').value.toLowerCase();
        const rows = document.querySelectorAll('.project-row');

        rows.forEach(row => {
            const eventId = row.getAttribute('data-event-id');
            const projectName = row.getAttribute('data-project-name');

            const matchesEvent = !eventFilter || eventId === eventFilter;
            const matchesSearch = !searchText || projectName.includes(searchText);

            if (matchesEvent && matchesSearch) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endpush
@endsection
