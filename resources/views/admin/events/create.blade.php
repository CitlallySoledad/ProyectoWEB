@extends('layouts.admin-panel')

@section('title', 'Crear evento')

@section('content')
    <h1 class="h4 mb-3">Crear evento</h1>

    {{-- ERRORES DE VALIDACIÓN --}}
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
        <form action="{{ route('admin.events.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Título del evento</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="form-control rounded-pill" placeholder="Hackathon 2025" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Lugar / Campus</label>
                    <input type="text" name="place" value="{{ old('place') }}"
                           class="form-control rounded-pill" placeholder="Ej: Campus Central, Auditorio A">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Capacidad (equipos)</label>
                    <input type="number" name="capacity" value="{{ old('capacity', 10) }}"
                           class="form-control rounded-pill" placeholder="10" min="1">
                    <small class="text-muted">Deja vacío para capacidad ilimitada</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Estado</label>
                    <select name="status" class="form-select rounded-pill">
                        <option value="borrador" {{ old('status', 'borrador') == 'borrador' ? 'selected' : '' }}>Borrador</option>
                        <option value="publicado" {{ old('status') == 'publicado' ? 'selected' : '' }}>Publicado</option>
                        <option value="activo" {{ old('status') == 'activo' ? 'selected' : '' }}>Activo</option>
                        <option value="cerrado" {{ old('status') == 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                    </select>
                    <small class="text-muted"><strong>Publicado:</strong> acepta inscripciones | <strong>Activo:</strong> evento en curso, no acepta inscripciones</small>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Fecha inicio</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}"
                           class="form-control rounded-pill">
                </div>

                <div class="col-md-6">
                    <label class="form-label">Fecha fin</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}"
                           class="form-control rounded-pill">
                </div>

                <div class="col-12">
                    <label class="form-label">Descripción</label>
                    <textarea name="description" rows="4"
                              class="form-control"
                              style="border-radius: 18px;"
                              placeholder="Describe el evento, premios, reglas, etc.">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-between">
                <a href="{{ route('admin.events.index') }}" class="admin-btn-secondary text-decoration-none">
                    Cancelar
                </a>
                <button type="submit" class="admin-btn-primary">
                    Crear evento
                </button>
            </div>
        </form>
    </div>
@endsection

