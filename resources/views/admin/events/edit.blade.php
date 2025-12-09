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
                            <option value="publicado" {{ old('status', $event->status) == 'publicado' ? 'selected' : '' }}>Publicado</option>
                            <option value="activo"   {{ old('status', $event->status) == 'activo'   ? 'selected' : '' }}>Activo</option>
                            <option value="cerrado"  {{ old('status', $event->status) == 'cerrado'  ? 'selected' : '' }}>Cerrado</option>
                        </select>
                        <small class="text-muted d-block mt-1">
                            <strong>Publicado:</strong> acepta inscripciones de equipos<br>
                            <strong>Activo:</strong> evento en curso, no acepta más inscripciones
                        </small>
                    </div>

                    {{-- Categoría --}}
                    <div class="mb-3">
                        <label class="form-label" for="category">Categoría</label>
                        <select id="category" name="category" class="form-select rounded-pill">
                            <option value="">-- Selecciona una categoría --</option>
                            <option value="Desarrollo Web" {{ old('category', $event->category) == 'Desarrollo Web' ? 'selected' : '' }}>Desarrollo Web</option>
                            <option value="Desarrollo Móvil" {{ old('category', $event->category) == 'Desarrollo Móvil' ? 'selected' : '' }}>Desarrollo Móvil</option>
                            <option value="Aplicaciones de Escritorio" {{ old('category', $event->category) == 'Aplicaciones de Escritorio' ? 'selected' : '' }}>Aplicaciones de Escritorio</option>
                            <option value="Ciencia de Datos & IA" {{ old('category', $event->category) == 'Ciencia de Datos & IA' ? 'selected' : '' }}>Ciencia de Datos & IA</option>
                            <option value="Desarrollo de Juegos" {{ old('category', $event->category) == 'Desarrollo de Juegos' ? 'selected' : '' }}>Desarrollo de Juegos</option>
                            <option value="DevOps & Infraestructura" {{ old('category', $event->category) == 'DevOps & Infraestructura' ? 'selected' : '' }}>DevOps & Infraestructura</option>
                            <option value="IoT & Hardware" {{ old('category', $event->category) == 'IoT & Hardware' ? 'selected' : '' }}>IoT & Hardware</option>
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

                {{-- ASIGNACIÓN DE JURADOS --}}
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label">Asignación de Jurados</label>
                        <div class="card p-3" style="border-radius: 18px;">
                            @if(isset($judges) && $judges->count() > 0)
                                <div class="row">
                                    @foreach($judges as $judge)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="judge_ids[]" 
                                                       value="{{ $judge->id }}" 
                                                       id="judge_{{ $judge->id }}"
                                                       {{ in_array($judge->id, old('judge_ids', $event->judge_ids ?? [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="judge_{{ $judge->id }}">
                                                    {{ $judge->name }}
                                                    <small class="text-muted d-block">{{ $judge->email }}</small>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0">
                                    <i class="bi bi-info-circle"></i> No hay jueces registrados. 
                                    <a href="{{ route('admin.users.index') }}" class="text-decoration-none">Crear jueces</a>
                                </p>
                            @endif
                        </div>
                        <small class="text-muted">Selecciona los jueces que evaluarán los proyectos de este evento</small>
                    </div>
                </div>

                {{-- ASIGNACIÓN DE RÚBRICAS --}}
                <div class="col-12">
                    <div class="mb-3">
                        <label class="form-label">Rúbricas de Evaluación</label>
                        <div class="card p-3" style="border-radius: 18px;">
                            @if(isset($rubrics) && $rubrics->count() > 0)
                                <div class="row">
                                    @foreach($rubrics as $rubric)
                                        <div class="col-md-4 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="rubric_ids[]" 
                                                       value="{{ $rubric->id }}" 
                                                       id="rubric_{{ $rubric->id }}"
                                                       {{ in_array($rubric->id, old('rubric_ids', $event->rubric_ids ?? [])) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="rubric_{{ $rubric->id }}">
                                                    {{ $rubric->name }}
                                                    <small class="text-muted d-block">
                                                        @if($rubric->event_id)
                                                            Asociada a: {{ $rubric->event->title ?? 'Evento' }}
                                                        @else
                                                            Rúbrica general
                                                        @endif
                                                    </small>
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-muted mb-0">
                                    <i class="bi bi-info-circle"></i> No hay rúbricas activas. 
                                    <a href="{{ route('admin.rubrics.create') }}" class="text-decoration-none">Crear rúbrica</a>
                                </p>
                            @endif
                        </div>
                        <small class="text-muted">Selecciona las rúbricas que se usarán para evaluar los proyectos</small>
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
