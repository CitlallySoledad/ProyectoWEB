@extends('layouts.admin-panel')

@section('title', 'Crear evento')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">✨ Crear Nuevo Evento</h1>
            <p class="text-dark mb-0">Completa todos los campos para crear un evento exitoso</p>
        </div>
        <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary rounded-pill">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </a>
    </div>

    {{-- ERRORES DE VALIDACIÓN --}}
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" style="border-radius: 15px; border-left: 4px solid #dc3545;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Error en el formulario:</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-card" style="box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
        <form action="{{ route('admin.events.store') }}" method="POST">
            @csrf

            {{-- INFORMACIÓN BÁSICA --}}
            <div class="mb-4">
                <h5 class="fw-bold mb-3" style="color: #2563eb;">
                    <i class="bi bi-info-circle-fill me-2"></i>Información Básica
                </h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">
                            <i class="bi bi-trophy text-warning me-1"></i> Título del evento *
                        </label>
                        <input type="text" name="title" value="{{ old('title') }}"
                               class="form-control rounded-pill" placeholder="Ej: Hackathon 2025" required
                               style="padding: 12px 20px; border: 2px solid #e5e7eb; transition: all 0.3s;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">
                            <i class="bi bi-geo-alt-fill text-danger me-1"></i> Lugar / Campus *
                        </label>
                        <input type="text" name="place" value="{{ old('place') }}"
                               class="form-control rounded-pill" placeholder="Ej: Campus Central, Auditorio A" required
                               style="padding: 12px 20px; border: 2px solid #e5e7eb; transition: all 0.3s;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">
                            <i class="bi bi-people-fill text-primary me-1"></i> Capacidad (equipos) *
                        </label>
                        <input type="number" name="capacity" value="{{ old('capacity', 10) }}"
                               class="form-control rounded-pill" placeholder="10" min="1" required
                               style="padding: 12px 20px; border: 2px solid #e5e7eb; transition: all 0.3s;">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">
                            <i class="bi bi-tag-fill text-success me-1"></i> Categoría *
                        </label>
                        <select name="category" class="form-select rounded-pill" required
                                style="padding: 12px 20px; border: 2px solid #e5e7eb; transition: all 0.3s;">
                            <option value="">-- Selecciona una categoría --</option>
                            <option value="Desarrollo Web" {{ old('category') == 'Desarrollo Web' ? 'selected' : '' }}>🌐 Desarrollo Web</option>
                            <option value="Desarrollo Móvil" {{ old('category') == 'Desarrollo Móvil' ? 'selected' : '' }}>📱 Desarrollo Móvil</option>
                            <option value="Aplicaciones de Escritorio" {{ old('category') == 'Aplicaciones de Escritorio' ? 'selected' : '' }}>💻 Aplicaciones de Escritorio</option>
                            <option value="Ciencia de Datos & IA" {{ old('category') == 'Ciencia de Datos & IA' ? 'selected' : '' }}>🤖 Ciencia de Datos & IA</option>
                            <option value="Desarrollo de Juegos" {{ old('category') == 'Desarrollo de Juegos' ? 'selected' : '' }}>🎮 Desarrollo de Juegos</option>
                            <option value="DevOps & Infraestructura" {{ old('category') == 'DevOps & Infraestructura' ? 'selected' : '' }}>⚙️ DevOps & Infraestructura</option>
                            <option value="IoT & Hardware" {{ old('category') == 'IoT & Hardware' ? 'selected' : '' }}>🔌 IoT & Hardware</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold text-dark">
                            <i class="bi bi-file-text-fill text-info me-1"></i> Descripción *
                        </label>
                        <textarea name="description" rows="5"
                                  class="form-control"
                                  style="border-radius: 18px; padding: 15px 20px; border: 2px solid #e5e7eb; transition: all 0.3s;"
                                  placeholder="Describe el evento, objetivos, premios, reglas, etc." required>{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            {{-- CONFIGURACIÓN DEL EVENTO --}}
            <div class="mb-4">
                <h5 class="fw-bold mb-3" style="color: #2563eb;">
                    <i class="bi bi-gear-fill me-2"></i>Configuración del Evento
                </h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="bi bi-calendar-check text-success me-1"></i> Fecha inicio *
                        </label>
                        <input type="date" name="start_date" value="{{ old('start_date') }}"
                               class="form-control rounded-pill" required
                               style="padding: 12px 20px; border: 2px solid #e5e7eb; transition: all 0.3s;">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="bi bi-calendar-x text-danger me-1"></i> Fecha fin *
                        </label>
                        <input type="date" name="end_date" value="{{ old('end_date') }}"
                               class="form-control rounded-pill" required
                               style="padding: 12px 20px; border: 2px solid #e5e7eb; transition: all 0.3s;">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label fw-semibold text-dark">
                            <i class="bi bi-circle-fill text-primary me-1"></i> Estado *
                        </label>
                        <select name="status" class="form-select rounded-pill" required
                                style="padding: 12px 20px; border: 2px solid #e5e7eb; transition: all 0.3s;">
                            <option value="borrador" {{ old('status', 'borrador') == 'borrador' ? 'selected' : '' }}>📝 Borrador</option>
                            <option value="publicado" {{ old('status') == 'publicado' ? 'selected' : '' }}>📢 Publicado</option>
                            <option value="activo" {{ old('status') == 'activo' ? 'selected' : '' }}>⚡ Activo</option>
                            <option value="cerrado" {{ old('status') == 'cerrado' ? 'selected' : '' }}>🔒 Cerrado</option>
                        </select>
                        <small class="text-dark d-block mt-1">
                            <strong>Publicado:</strong> acepta inscripciones | <strong>Activo:</strong> evento en curso
                        </small>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            {{-- ASIGNACIÓN DE JURADOS --}}
            <div class="mb-4">
                <h5 class="fw-bold mb-3" style="color: #2563eb;">
                    <i class="bi bi-person-badge-fill me-2"></i>Asignación de Jurados
                </h5>
                <div class="card border-0" style="border-radius: 18px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 20px;">
                    @if(isset($judges) && $judges->count() > 0)
                        <div class="row g-3">
                            @foreach($judges as $judge)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s;">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="judge_ids[]" 
                                                       value="{{ $judge->id }}" 
                                                       id="judge_{{ $judge->id }}"
                                                       style="width: 20px; height: 20px; cursor: pointer;"
                                                       {{ in_array($judge->id, old('judge_ids', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label ms-2 cursor-pointer" for="judge_{{ $judge->id }}" style="cursor: pointer;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-2">
                                                            <i class="bi bi-person-fill text-primary"></i>
                                                        </div>
                                                        <div>
                                                            <strong class="d-block">{{ $judge->name }}</strong>
                                                            <small class="text-dark">{{ $judge->email }}</small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-info-circle text-secondary" style="font-size: 3rem;"></i>
                            <p class="text-dark mb-2 mt-3">No hay jueces registrados en el sistema</p>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-primary rounded-pill">
                                <i class="bi bi-plus-circle me-1"></i> Crear jueces
                            </a>
                        </div>
                    @endif
                </div>
                <small class="text-dark d-block mt-2">
                    <i class="bi bi-lightbulb-fill text-warning me-1"></i>
                    Selecciona los jueces que evaluarán los proyectos de este evento
                </small>
            </div>

            <hr class="my-4">

            {{-- RÚBRICAS DE EVALUACIÓN --}}
            <div class="mb-4">
                <h5 class="fw-bold mb-3" style="color: #2563eb;">
                    <i class="bi bi-clipboard-check-fill me-2"></i>Rúbricas de Evaluación
                </h5>
                <div class="card border-0" style="border-radius: 18px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); padding: 20px;">
                    @if(isset($rubrics) && $rubrics->count() > 0)
                        <div class="row g-3">
                            @foreach($rubrics as $rubric)
                                <div class="col-md-6 col-lg-4">
                                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 12px; transition: transform 0.2s, box-shadow 0.2s;">
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" 
                                                       name="rubric_ids[]" 
                                                       value="{{ $rubric->id }}" 
                                                       id="rubric_{{ $rubric->id }}"
                                                       style="width: 20px; height: 20px; cursor: pointer;"
                                                       {{ in_array($rubric->id, old('rubric_ids', [])) ? 'checked' : '' }}>
                                                <label class="form-check-label ms-2 cursor-pointer" for="rubric_{{ $rubric->id }}" style="cursor: pointer;">
                                                    <div class="d-flex align-items-center">
                                                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-2">
                                                            <i class="bi bi-list-check text-success"></i>
                                                        </div>
                                                        <div>
                                                            <strong class="d-block">{{ $rubric->name }}</strong>
                                                            <small class="text-dark">
                                                                @if($rubric->event_id)
                                                                    <i class="bi bi-link-45deg"></i> {{ $rubric->event->title ?? 'Evento' }}
                                                                @else
                                                                    <i class="bi bi-check-circle"></i> Rúbrica general
                                                                @endif
                                                            </small>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-info-circle text-secondary" style="font-size: 3rem;"></i>
                            <p class="text-dark mb-2 mt-3">No hay rúbricas activas en el sistema</p>
                            <a href="{{ route('admin.rubrics.create') }}" class="btn btn-success rounded-pill">
                                <i class="bi bi-plus-circle me-1"></i> Crear rúbrica
                            </a>
                        </div>
                    @endif
                </div>
                <small class="text-dark d-block mt-2">
                    <i class="bi bi-lightbulb-fill text-warning me-1"></i>
                    Selecciona las rúbricas que se usarán para evaluar los proyectos
                </small>
            </div>

            <hr class="my-4">

            {{-- BOTONES DE ACCIÓN --}}
            <div class="d-flex justify-content-between align-items-center gap-3">
                <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary rounded-pill px-4 py-2">
                    <i class="bi bi-x-circle me-1"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-primary rounded-pill px-5 py-2 shadow-sm" 
                        style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); border: none;">
                    <i class="bi bi-check-circle-fill me-2"></i> Crear Evento
                </button>
            </div>
        </form>
    </div>

    <style>
        .form-control:focus, .form-select:focus {
            border-color: #2563eb !important;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15) !important;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 16px rgba(0,0,0,0.1) !important;
        }

        .form-check-input:checked {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        .admin-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4) !important;
        }

        .btn-outline-secondary:hover {
            transform: translateY(-2px);
        }
    </style>
@endsection

