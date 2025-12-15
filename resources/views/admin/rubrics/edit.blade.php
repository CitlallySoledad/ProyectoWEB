@extends('layouts.admin-panel')

@section('title', 'Editar rúbrica')

@push('styles')
<style>
    /* ===== COLORES BLANCOS PARA TEXTO ===== */
    .h4, .mb-3, .form-label, .admin-card-title {
        color: #fff !important;
    }
    
    .text-muted {
        color: #94a3b8 !important;
    }
</style>
@endpush

@section('content')
    <h1 class="h4 mb-3">Editar rúbrica</h1>

    @if ($errors->any())
        <div class="alert alert-danger rounded-3 py-2 mb-3">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 py-2 mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="admin-card">
        <form action="{{ route('admin.rubrics.update', $rubric) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre de la rúbrica</label>
                    <input type="text" name="name" class="form-control rounded-pill" value="{{ old('name', $rubric->name) }}" placeholder="Ej: Rúbrica Innovatec 2025" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Evento (opcional)</label>
                    <select name="event_id" class="form-select rounded-pill">
                        <option value="">-- Sin evento específico --</option>
                        @foreach($events as $ev)
                            <option value="{{ $ev->id }}" {{ old('event_id', $rubric->event_id) == $ev->id ? 'selected' : '' }}>{{ $ev->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select rounded-pill" required>
                        <option value="activa" {{ old('status', $rubric->status) == 'activa' ? 'selected' : '' }}>Activa</option>
                        <option value="inactiva" {{ old('status', $rubric->status) == 'inactiva' ? 'selected' : '' }}>Inactiva</option>
                    </select>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <a href="{{ route('admin.rubrics.index', ['rubric' => $rubric->id]) }}" class="admin-btn-secondary text-decoration-none">
                    Cancelar
                </a>
                <button class="admin-btn-primary" type="submit">
                    <i class="bi bi-save"></i> Guardar cambios
                </button>
            </div>
        </form>
    </div>

    {{-- Sección de criterios --}}
    <hr style="border-color: rgba(148, 163, 184, 0.2); margin: 30px 0;">
    
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
                            <i class="bi bi-save"></i> Guardar cambios en criterios
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
@endsection
