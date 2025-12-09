@extends('layouts.admin-panel')

@section('title', 'Gestión de rúbricas')

@push('styles')
<style>
    .judge-search-rubric {
        border-radius: 999px;
        border: none;
        padding: 8px 16px;
        font-size: 0.95rem;
        width: 100%;
        max-width: 260px;
    }
    .judge-new-rubric-btn {
        border-radius: 18px;
        background: #065f46;
        color: #e5e7eb;
        padding: 8px 20px;
        border: none;
        font-weight: 600;
        box-shadow: 0 12px 30px rgba(0,0,0,0.4);
        text-decoration: none;
        display: inline-block;
    }
    .judge-new-rubric-btn:hover {
        background: #047857;
        color: #ffffff;
    }
</style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Gestión de rúbricas</h1>
        <a href="{{ route('admin.rubrics.create') }}" class="judge-new-rubric-btn">+ Nueva Rúbrica</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 py-2 mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 py-2 mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="admin-card mb-3">
        <table class="admin-table">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Evento</th>
                <th>Estado</th>
                <th style="width: 220px;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($rubrics as $rubricItem)
                <tr @if($rubric && $rubric->id === $rubricItem->id) style="box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.35);" @endif>
                    <td><strong>{{ $rubricItem->name }}</strong></td>
                    <td>{{ $rubricItem->event?->title ?? '-' }}</td>
                    <td>
                        @if($rubricItem->status === 'activa')
                            <span class="badge bg-success">Activa</span>
                        @else
                            <span class="badge bg-secondary">Inactiva</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
                            <a href="{{ route('admin.rubrics.index', ['rubric' => $rubricItem->id]) }}"
                                class="btn btn-sm btn-light rounded-pill">
                                <i class="bi bi-eye"></i> Ver
                            </a>
                            <a href="{{ route('admin.rubrics.edit', $rubricItem) }}" class="btn btn-sm btn-primary rounded-pill">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <button form="delete-rubric-{{ $rubricItem->id }}" type="submit" class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('¿Eliminar rúbrica? Esta acción no se puede deshacer.')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 2rem; color: #94a3b8;"></i>
                        <p class="text-muted mt-2 mb-0">No hay rúbricas registradas.</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Formularios ocultos para eliminar rúbricas --}}
    @foreach($rubrics as $rubricItem)
        <form id="delete-rubric-{{ $rubricItem->id }}" action="{{ route('admin.rubrics.destroy', $rubricItem) }}" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    {{-- Si hay rúbrica seleccionada, mostramos criterios debajo --}}
    @if($rubric)
        <div class="admin-card">
            <div class="admin-card-title">Criterios de: {{ $rubric->name }}</div>
            
            {{-- Formulario para actualizar todos los criterios en bloque --}}
            <form id="bulk-criteria-form" action="{{ route('admin.rubrics.criteria.bulkUpdate', $rubric) }}" method="POST">
                @csrf
                <div id="selected-rubric-criteria">
                    <table class="admin-table">
                        <thead>
                        <tr>
                            <th>Criterio</th>
                            <th>Descripción</th>
                            <th style="width:100px;">Peso</th>
                            <th style="width:80px;">Min</th>
                            <th style="width:80px;">Max</th>
                            <th style="width:120px;">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($rubric->criteria as $i => $criterion)
                            <tr>
                                <td>
                                    <input type="hidden" name="criteria[{{ $i }}][id]" value="{{ $criterion->id }}">
                                    <input type="text" name="criteria[{{ $i }}][name]" class="form-control form-control-sm rounded-pill" value="{{ old('criteria.'.$i.'.name', $criterion->name) }}" required>
                                </td>
                                <td>
                                    <input type="text" name="criteria[{{ $i }}][description]" class="form-control form-control-sm rounded-pill" value="{{ old('criteria.'.$i.'.description', $criterion->description) }}">
                                </td>
                                <td>
                                    <input type="number" name="criteria[{{ $i }}][weight]" class="form-control form-control-sm rounded-pill" value="{{ old('criteria.'.$i.'.weight', $criterion->weight) }}" min="0" step="0.01">
                                </td>
                                <td>
                                    <input type="number" name="criteria[{{ $i }}][min_score]" class="form-control form-control-sm rounded-pill" value="{{ old('criteria.'.$i.'.min_score', $criterion->min_score) }}" min="0">
                                </td>
                                <td>
                                    <input type="number" name="criteria[{{ $i }}][max_score]" class="form-control form-control-sm rounded-pill" value="{{ old('criteria.'.$i.'.max_score', $criterion->max_score) }}" min="1">
                                </td>
                                <td>
                                    <button type="submit" form="delete-criterion-{{ $criterion->id }}" class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('¿Eliminar criterio?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-3">
                                    <small class="text-muted">No hay criterios definidos. Agrega uno usando el formulario de abajo.</small>
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>

                    @if($rubric->criteria->count() > 0)
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="admin-btn-primary">
                                <i class="bi bi-save"></i> Guardar cambios
                            </button>
                        </div>
                    @endif
                </div>
            </form>

            {{-- Formulario para agregar un nuevo criterio --}}
            <hr style="border-color: rgba(148, 163, 184, 0.2); margin: 20px 0;">
            <div class="admin-card-title mb-3">Agregar nuevo criterio</div>
            <form action="{{ route('admin.rubrics.criteria.store', $rubric) }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <label class="form-label">Nombre del criterio</label>
                    <input type="text" name="name" class="form-control rounded-pill" placeholder="Ej: Creatividad" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Descripción</label>
                    <input type="text" name="description" class="form-control rounded-pill" placeholder="Descripción (opcional)">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Peso</label>
                    <input type="number" name="weight" class="form-control rounded-pill" placeholder="1.0" min="0" step="0.01" value="1" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Min</label>
                    <input type="number" name="min_score" class="form-control rounded-pill" placeholder="0" min="0" value="0" required>
                </div>
                <div class="col-md-1">
                    <label class="form-label">Max</label>
                    <input type="number" name="max_score" class="form-control rounded-pill" placeholder="10" min="1" value="10" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button class="admin-btn-primary w-100" type="submit">
                        <i class="bi bi-plus-circle"></i> Agregar
                    </button>
                </div>
            </form>
        </div>

        {{-- Formularios ocultos para borrar criterios --}}
        @foreach($rubric->criteria as $criterion)
            <form id="delete-criterion-{{ $criterion->id }}" action="{{ route('admin.rubrics.criteria.destroy', $criterion) }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    @endif
@endsection
