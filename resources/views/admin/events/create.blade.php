@extends('layouts.admin-panel')

@section('title', 'Crear evento')

@section('content')
    <h1 class="h4 mb-3">Crear evento</h1>

    <div class="admin-card">
        <form action="{{ route('admin.events.store') }}" method="POST">
            @csrf

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Título del evento</label>
                    <input type="text" name="title" value="{{ old('title') }}"
                           class="form-control rounded-pill" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Capacidad</label>
                    <input type="number" name="capacity" value="{{ old('capacity', 10) }}"
                           class="form-control rounded-pill" required>
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
                    <textarea name="description" rows="3"
                              class="form-control"
                              style="border-radius: 18px;">{{ old('description') }}</textarea>
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

