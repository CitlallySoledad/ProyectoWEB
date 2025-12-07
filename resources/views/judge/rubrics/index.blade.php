@extends('layouts.judge-panel')

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
    }
</style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Gestión de rúbricas</h1>
        <a href="{{ route('judge.rubrics.create') }}" class="judge-new-rubric-btn">+ Nueva Rúbrica</a>
    </div>

    <div class="mb-3">
        <input type="text"
               class="judge-search-rubric"
               placeholder="Buscar rúbrica"
               value="{{ request('q') }}">
    </div>

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
            @foreach($rubrics as $rubricItem)
                <tr @if($rubric && $rubric->id === $rubricItem->id) style="box-shadow: 0 0 0 2px rgba(248,250,252,0.35);" @endif>
                    <td>{{ $rubricItem->name }}</td>
                    <td>{{ $rubricItem->event?->name ?? '-' }}</td>
                    <td class="text-capitalize">{{ $rubricItem->status }}</td>
                    <td>
                        <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
                            <a href="{{ route('judge.rubrics.index', ['rubric' => $rubricItem->id]) }}"
                                class="btn btn-sm btn-light rounded-pill">Ver</a>
                            <button form="apply-rubric-{{ $rubricItem->id }}" type="submit" class="btn btn-sm btn-success rounded-pill" @if($rubricItem->status === 'inactiva') disabled title="No se puede aplicar una rúbrica inactiva" @endif>Aplicar</button>
                            <a href="{{ route('judge.rubrics.edit', $rubricItem) }}" class="btn btn-sm btn-primary rounded-pill">Editar</a>
                            <button form="delete-rubric-{{ $rubricItem->id }}" type="submit" class="btn btn-sm btn-outline-light rounded-pill" onclick="return confirm('¿Eliminar rúbrica? Esta acción no se puede deshacer.')">Eliminar</button>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Formularios ocultos para aplicar y eliminar rúbricas --}}
    @foreach($rubrics as $rubricItem)
        <form id="apply-rubric-{{ $rubricItem->id }}" action="{{ route('judge.rubrics.apply', $rubricItem) }}" method="POST" style="display:none;">
            @csrf
        </form>
        <form id="delete-rubric-{{ $rubricItem->id }}" action="{{ route('judge.rubrics.destroy', $rubricItem) }}" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach

    {{-- Si hay rúbrica seleccionada, mostramos criterios debajo --}}
    @if($rubric)
        {{-- Formulario para actualizar todos los criterios en bloque --}}
        <form id="bulk-criteria-form" action="{{ route('judge.rubrics.criteria.bulkUpdate', $rubric) }}" method="POST">
            @csrf
            <div id="selected-rubric-criteria" class="admin-card">
                <table class="admin-table">
                    <thead>
                    <tr>
                        <th>Criterio</th>
                        <th>Descripción</th>
                        <th>Peso</th>
                        <th>Min</th>
                        <th>Max</th>
                        <th style="width:160px;">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($rubric->criteria as $i => $criterion)
                        <tr>
                            <td>
                                <input type="hidden" name="criteria[{{ $i }}][id]" value="{{ $criterion->id }}">
                                <input type="text" name="criteria[{{ $i }}][name]" class="form-control form-control-sm" value="{{ old('criteria.'.$i.'.name', $criterion->name) }}" required>
                            </td>
                            <td>
                                <input type="text" name="criteria[{{ $i }}][description]" class="form-control form-control-sm" value="{{ old('criteria.'.$i.'.description', $criterion->description) }}">
                            </td>
                            <td>
                                <input type="number" name="criteria[{{ $i }}][weight]" class="form-control form-control-sm" value="{{ old('criteria.'.$i.'.weight', $criterion->weight) }}" min="0">
                            </td>
                            <td>
                                <input type="number" name="criteria[{{ $i }}][min_score]" class="form-control form-control-sm" value="{{ old('criteria.'.$i.'.min_score', $criterion->min_score) }}" min="0">
                            </td>
                            <td>
                                <input type="number" name="criteria[{{ $i }}][max_score]" class="form-control form-control-sm" value="{{ old('criteria.'.$i.'.max_score', $criterion->max_score) }}" min="1">
                            </td>
                            <td>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('judge.rubrics.criteria.edit', $criterion) }}" class="btn btn-sm btn-primary rounded-pill">Editar</a>
                                    <button type="submit" form="delete-criterion-{{ $criterion->id }}" class="btn btn-sm btn-outline-light rounded-pill" onclick="return confirm('¿Eliminar criterio?')">Eliminar</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill">Guardar cambios</button>
                </div>
            </div>
        </form>

        {{-- Formulario para agregar un nuevo criterio (fuera del formulario principal para evitar anidamiento) --}}
        <div class="mt-2">
            <form action="{{ route('judge.rubrics.criteria.store', $rubric) }}" method="POST" class="row g-2 d-inline-flex align-items-center">
                @csrf
                <div class="col-md-3">
                    <input type="text" name="name" class="form-control form-control-sm" placeholder="Nombre del criterio" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="description" class="form-control form-control-sm" placeholder="Descripción (opcional)">
                </div>
                <div class="col-md-1">
                    <input type="number" name="weight" class="form-control form-control-sm" placeholder="Peso" min="0" required>
                </div>
                <div class="col-md-1">
                    <input type="number" name="min_score" class="form-control form-control-sm" placeholder="Min" min="0" required>
                </div>
                <div class="col-md-1">
                    <input type="number" name="max_score" class="form-control form-control-sm" placeholder="Max" min="1" required>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-sm btn-light rounded-pill" type="submit">+ Agregar criterio</button>
                </div>
            </form>
        </div>

        {{-- Formularios ocultos para borrar criterios (evita anidamiento de forms) --}}
        @foreach($rubric->criteria as $criterion)
            <form id="delete-criterion-{{ $criterion->id }}" action="{{ route('judge.rubrics.criteria.destroy', $criterion) }}" method="POST" style="display:none;">
                @csrf
                @method('DELETE')
            </form>
        @endforeach
    @endif

@push('scripts')
<script>
    (function(){
        // Si la URL contiene apply=1, desplazamos la sección de criterios a la vista
        try {
            const params = new URLSearchParams(window.location.search);
            if (params.get('apply') === '1') {
                const el = document.getElementById('selected-rubric-criteria');
                if (el) {
                    setTimeout(() => {
                        el.scrollIntoView({behavior: 'smooth', block: 'start'});
                        el.classList.add('flash-applied');
                        setTimeout(() => el.classList.remove('flash-applied'), 2000);
                    }, 250);
                }
            }
        } catch(e) { console.error(e); }
    })();

    // Cerrar formulario de agregar criterio al hacer submit del formulario en lote
    const bulkForm = document.getElementById('bulk-criteria-form');
    if (bulkForm) {
        bulkForm.addEventListener('submit', function() {
            // Limpiar campos del formulario de agregar criterio
            const addForm = document.querySelector('.mt-2 form');
            if (addForm) {
                addForm.reset();
            }
        });
    }
</script>
<style>
    .flash-applied { box-shadow: 0 0 0 4px rgba(34,197,94,0.18) !important; }
</style>
@endpush
@endsection
