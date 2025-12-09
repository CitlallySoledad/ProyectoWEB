@extends('layouts.admin-panel')

@section('title', 'Editar rúbrica')

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
@endsection
