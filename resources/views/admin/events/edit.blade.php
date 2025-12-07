@extends('layouts.admin-panel')

@section('title', 'Editar evento')

@section('content')

    <h1 class="h4 mb-3">Editar evento</h1>

    {{-- ERRORES DE VALIDACIÓN --}}
    @if ($errors->any())
        <div class="alert alert-danger rounded-3 py-2">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-card">
        <form action="{{ route('admin.events.update', $event->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                {{-- COLUMNA IZQUIERDA --}}
                <div class="col-md-6">
                    {{-- Título --}}
                    <div class="mb-3">
                        <label class="form-label" for="title">Título del evento</label>
                        <input
                            type="text"
                            id="title"
                            name="title"
                            class="form-control rounded-pill"
                            placeholder="Título del evento"
                            value="{{ old('title', $event->title) }}"
                            required
                        >
                    </div>

                    {{-- Descripción --}}
                    <div class="mb-3">
                        <label class="form-label" for="description">Descripción</label>
                        <textarea
                            id="description"
                            name="description"
                            class="form-control"
                            style="border-radius: 18px;"
                            rows="3"
                            placeholder="Descripción"
                        >{{ old('description', $event->description) }}</textarea>
                    </div>

                    {{-- Lugar --}}
                    <div class="mb-3">
                        <label class="form-label" for="place">Lugar / campus</label>
                        <input
                            type="text"
                            id="place"
                            name="place"
                            class="form-control rounded-pill"
                            placeholder="Lugar/campus"
                            value="{{ old('place', $event->place) }}"
                        >
                    </div>

                    {{-- Estado --}}
                    <div class="mb-3">
                        <label class="form-label" for="status">Estado</label>
                        <select id="status" name="status" class="form-select rounded-pill">
                            <option value="borrador" {{ old('status', $event->status) == 'borrador' ? 'selected' : '' }}>Borrador</option>
                            <option value="activo"   {{ old('status', $event->status) == 'activo'   ? 'selected' : '' }}>Activo</option>
                            <option value="cerrado"  {{ old('status', $event->status) == 'cerrado'  ? 'selected' : '' }}>Cerrado</option>
                        </select>
                    </div>
                </div>

                {{-- COLUMNA DERECHA --}}
                <div class="col-md-6">
                    {{-- Capacidad --}}
                    <div class="mb-3">
                        <label class="form-label" for="capacity">Capacidad</label>
                        <input
                            type="number"
                            id="capacity"
                            name="capacity"
                            class="form-control rounded-pill"
                            placeholder="10"
                            value="{{ old('capacity', $event->capacity) }}"
                            min="1"
                        >
                    </div>

                    {{-- Fecha inicio --}}
                    <div class="mb-3">
                        <label class="form-label" for="start_date">Fecha inicio</label>
                        <input
                            type="date"
                            id="start_date"
                            name="start_date"
                            class="form-control rounded-pill"
                            value="{{ old('start_date', optional($event->start_date)->format('Y-m-d')) }}"
                        >
                    </div>

                    {{-- Fecha fin --}}
                    <div class="mb-3">
                        <label class="form-label" for="end_date">Fecha fin</label>
                        <input
                            type="date"
                            id="end_date"
                            name="end_date"
                            class="form-control rounded-pill"
                            value="{{ old('end_date', optional($event->end_date)->format('Y-m-d')) }}"
                        >
                    </div>
                </div>
            </div>

            {{-- BOTONES --}}
            <div class="mt-3 d-flex justify-content-between">
                <a href="{{ route('admin.events.index') }}"
                   class="admin-btn-secondary text-decoration-none">
                    Cancelar
                </a>

                <button type="submit" class="admin-btn-primary">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>

@endsection
