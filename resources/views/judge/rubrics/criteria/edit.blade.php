@extends('layouts.judge-panel')

@section('title', 'Editar criterio')

@section('content')
    <h1 class="h3 mb-3">Editar criterio</h1>

    <form action="{{ route('judge.rubrics.criteria.update', $criterion) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $criterion->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="description" class="form-control">{{ old('description', $criterion->description) }}</textarea>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-md-2">
                <label class="form-label">Peso</label>
                <input type="number" name="weight" min="0" class="form-control" value="{{ old('weight', $criterion->weight) }}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Min</label>
                <input type="number" name="min_score" min="0" class="form-control" value="{{ old('min_score', $criterion->min_score) }}" required>
            </div>
            <div class="col-md-2">
                <label class="form-label">Max</label>
                <input type="number" name="max_score" min="1" class="form-control" value="{{ old('max_score', $criterion->max_score) }}" required>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('judge.rubrics.index', ['rubric' => $rubric->id]) }}" class="admin-btn-secondary">Cancelar</a>
            <div style="display: flex; gap: 12px;">
                <button class="btn btn-outline-secondary" type="submit" name="action" value="save">Guardar</button>
                <button class="admin-btn-primary" type="submit" name="action" value="apply">Guardar y aplicar</button>
            </div>
        </div>
    </form>
@endsection
