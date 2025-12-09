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
                    <label class="form-label">Categoría</label>
                    <select name="category" class="form-select rounded-pill">
                        <option value="">-- Selecciona una categoría --</option>
                        <option value="Desarrollo Web" {{ old('category') == 'Desarrollo Web' ? 'selected' : '' }}>Desarrollo Web</option>
                        <option value="Desarrollo Móvil" {{ old('category') == 'Desarrollo Móvil' ? 'selected' : '' }}>Desarrollo Móvil</option>
                        <option value="Aplicaciones de Escritorio" {{ old('category') == 'Aplicaciones de Escritorio' ? 'selected' : '' }}>Aplicaciones de Escritorio</option>
                        <option value="Ciencia de Datos & IA" {{ old('category') == 'Ciencia de Datos & IA' ? 'selected' : '' }}>Ciencia de Datos & IA</option>
                        <option value="Desarrollo de Juegos" {{ old('category') == 'Desarrollo de Juegos' ? 'selected' : '' }}>Desarrollo de Juegos</option>
                        <option value="DevOps & Infraestructura" {{ old('category') == 'DevOps & Infraestructura' ? 'selected' : '' }}>DevOps & Infraestructura</option>
                        <option value="IoT & Hardware" {{ old('category') == 'IoT & Hardware' ? 'selected' : '' }}>IoT & Hardware</option>
                    </select>
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

                <div class="col-12">
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
                                                   {{ in_array($judge->id, old('judge_ids', [])) ? 'checked' : '' }}>
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

                <div class="col-12">
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
                                                   {{ in_array($rubric->id, old('rubric_ids', [])) ? 'checked' : '' }}>
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

