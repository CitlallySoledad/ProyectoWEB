@extends('layouts.admin-panel')

@section('title', 'Eventos')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Eventos</h1>
        <a href="{{ route('admin.events.create') }}" class="admin-btn-primary text-decoration-none">
            <i class="bi bi-plus-circle me-1"></i> Crear evento
        </a>
    </div>

    {{-- MENSAJES DE ÉXITO --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 py-2 mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="admin-card">
        <div class="admin-card-title">Lista de eventos</div>

        @if($events->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-calendar-x" style="font-size: 3rem; color: #94a3b8;"></i>
                <p class="mt-3 mb-0 text-muted">No hay eventos registrados.</p>
                <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm mt-2 rounded-pill">
                    <i class="bi bi-plus-circle"></i> Crear primer evento
                </a>
            </div>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Categoría</th>
                        <th>Lugar</th>
                        <th>Estado</th>
                        <th>Jurados</th>
                        <th>Inscritos / Capacidad</th>
                        <th>Fecha inicio</th>
                        <th>Fecha fin</th>
                        <th style="width: 180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <tr>
                            <td>
                                <strong>{{ $event->title }}</strong>
                                @if($event->hasEnded())
                                    <small class="text-muted d-block">Finalizado</small>
                                @endif
                            </td>
                            <td>
                                @if($event->category)
                                    <span class="badge bg-primary">{{ $event->category }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $event->place ?? '—' }}</td>
                            <td>
                                @if($event->status === 'activo')
                                    <span class="badge bg-success">Activo</span>
                                @elseif($event->status === 'publicado')
                                    <span class="badge bg-info">Publicado</span>
                                @elseif($event->status === 'cerrado')
                                    <span class="badge bg-danger">Cerrado</span>
                                @else
                                    <span class="badge bg-secondary">Borrador</span>
                                @endif
                            </td>
                            <td>
                                @if($event->judge_ids && count($event->judge_ids) > 0)
                                    @php
                                        $judges = \App\Models\User::whereIn('id', $event->judge_ids)->get();
                                    @endphp
                                    <button type="button" 
                                            class="badge bg-info border-0" 
                                            data-bs-toggle="tooltip" 
                                            data-bs-placement="top" 
                                            data-bs-html="true"
                                            title="{{ $judges->pluck('name')->implode('<br>') }}">
                                        {{ $judges->count() }} jurado(s)
                                    </button>
                                @else
                                    <span class="text-muted">Sin asignar</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $inscritos = $event->teams()->count();
                                    $capacidad = $event->capacity ?? '∞';
                                    $porcentaje = is_numeric($capacidad) && $capacidad > 0 
                                        ? round(($inscritos / $capacidad) * 100) 
                                        : 0;
                                @endphp
                                <strong>{{ $inscritos }}</strong> / {{ $capacidad }}
                                @if($event->isFull())
                                    <span class="badge bg-warning text-dark ms-1">Lleno</span>
                                @elseif(is_numeric($capacidad) && $porcentaje >= 80)
                                    <span class="badge bg-info text-dark ms-1">{{ $porcentaje }}%</span>
                                @endif
                            </td>
                            <td>{{ $event->start_date ? $event->start_date->format('d/m/Y') : '—' }}</td>
                            <td>{{ $event->end_date ? $event->end_date->format('d/m/Y') : '—' }}</td>
                            <td>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="{{ route('admin.events.results', $event) }}"
                                       class="btn btn-sm btn-primary rounded-pill"
                                       title="Ver equipos y calificaciones">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    
                                    <a href="{{ route('admin.events.edit', $event) }}"
                                       class="btn btn-sm btn-warning rounded-pill"
                                       title="Editar evento">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>

                                    <form action="{{ route('admin.events.destroy', $event) }}"
                                          method="POST"
                                          class="d-inline m-0"
                                          onsubmit="return confirm('¿Eliminar este evento? Se perderán todas las inscripciones.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="btn btn-sm btn-danger rounded-pill"
                                                title="Eliminar evento">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- ESTADÍSTICAS RÁPIDAS --}}
            <div class="mt-3 p-3 bg-light rounded-3">
                <div class="row text-center">
                    <div class="col-md-2">
                        <h5 class="mb-0">{{ $events->count() }}</h5>
                        <small class="text-muted">Total</small>
                    </div>
                    <div class="col-md-2">
                        <h5 class="mb-0">{{ $events->where('status', 'activo')->count() }}</h5>
                        <small class="text-muted">Activos</small>
                    </div>
                    <div class="col-md-2">
                        <h5 class="mb-0">{{ $events->where('status', 'publicado')->count() }}</h5>
                        <small class="text-muted">Publicados</small>
                    </div>
                    <div class="col-md-2">
                        <h5 class="mb-0">{{ $events->where('status', 'borrador')->count() }}</h5>
                        <small class="text-muted">Borradores</small>
                    </div>
                    <div class="col-md-2">
                        <h5 class="mb-0">{{ $events->where('status', 'cerrado')->count() }}</h5>
                        <small class="text-muted">Cerrados</small>
                    </div>
                    <div class="col-md-2">
                        <h5 class="mb-0">{{ $events->sum(fn($e) => $e->teams()->count()) }}</h5>
                        <small class="text-muted">Inscritos</small>
                    </div>
                </div>
            </div>
        @endif
    </div>

@endsection

@push('scripts')
<script>
    // Inicializar tooltips de Bootstrap
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });
</script>
@endpush


