@extends('layouts.admin-panel')

@section('title', 'Eventos')

@push('styles')
<style>
/* ===== COLORES BLANCOS PARA TEXTO ===== */
.h3, .fw-bold, .text-muted, .admin-card-title, .admin-table th, .admin-table td, .admin-table td strong {
    color: #fff !important;
}

.text-muted {
    color: #94a3b8 !important;
}

/* ===== PAGINACIÓN COMPACTA EVENTOS ===== */
.events-pagination {
    margin: 24px auto 8px;
    padding: 8px 16px;
    border-radius: 999px;
    background: rgba(37, 99, 235, 0.1);
    display: flex;
    align-items: center;
    gap: 18px;
    width: auto;
    max-width: 100%;
    font-size: 0.85rem;
    color: #e5e7eb;
}

.events-pagination-info {
    white-space: nowrap;
    opacity: 0.85;
    font-weight: 500;
}

.events-pagination-pages {
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Botones de página */
.page-number,
.page-arrow {
    min-width: 32px;
    height: 32px;
    padding: 0 10px;
    border-radius: 999px;
    border: 1px solid #2563eb;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    text-decoration: none;
    color: #e5e7eb;
    background: transparent;
    transition: background 0.18s ease, color 0.18s ease, transform 0.12s ease;
    cursor: pointer;
}

.page-number:hover,
.page-arrow:hover {
    background: #2563eb;
    color: #ffffff;
    transform: translateY(-1px);
}

/* Página actual */
.page-number.active {
    background: #2563eb;
    color: #ffffff;
    border-color: #1d4ed8;
    font-weight: 600;
}

/* Deshabilitados */
.page-arrow.disabled {
    opacity: 0.35;
    border-color: #94a3b8;
    cursor: default;
    pointer-events: none;
}
</style>
@endpush

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">🎯 Gestión de Eventos</h1>
            <p class="text-muted mb-0">Administra todos los eventos del sistema</p>
        </div>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary rounded-pill px-4 py-2 shadow-sm" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%); border: none;">
            <i class="bi bi-plus-circle me-2"></i> Crear Evento
        </a>
    </div>

    {{-- BARRA DE ESTADÍSTICAS --}}
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="card-body text-center text-white py-4">
                    <div class="display-4 fw-bold mb-2">{{ $stats['total'] }}</div>
                    <div class="text-white-50 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Total</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);">
                <div class="card-body text-center text-white py-4">
                    <div class="display-4 fw-bold mb-2">{{ $stats['activos'] }}</div>
                    <div class="text-white-50 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Activos</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
                <div class="card-body text-center text-white py-4">
                    <div class="display-4 fw-bold mb-2">{{ $stats['publicados'] }}</div>
                    <div class="text-white-50 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Publicados</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #94a3b8 0%, #64748b 100%);">
                <div class="card-body text-center text-white py-4">
                    <div class="display-4 fw-bold mb-2">{{ $stats['borradores'] }}</div>
                    <div class="text-white-50 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Borradores</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
                <div class="card-body text-center text-white py-4">
                    <div class="display-4 fw-bold mb-2">{{ $stats['cerrados'] }}</div>
                    <div class="text-white-50 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Cerrados</div>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-0 shadow-sm h-100" style="border-radius: 15px; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                <div class="card-body text-center text-white py-4">
                    <div class="display-4 fw-bold mb-2">{{ $stats['inscritos'] }}</div>
                    <div class="text-white-50 fw-semibold text-uppercase" style="font-size: 0.75rem; letter-spacing: 1px;">Inscritos</div>
                </div>
            </div>
        </div>
    </div>

    {{-- MENSAJES DE ÉXITO --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" style="border-radius: 15px; border-left: 4px solid #22c55e;" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i><strong>¡Éxito!</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="admin-card" style="border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.08);">
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

            {{-- PAGINACIÓN COMPACTA --}}
            @if ($events->hasPages())
                <div class="events-pagination">
                    <span class="events-pagination-info">
                        Mostrando {{ $events->firstItem() }} - {{ $events->lastItem() }} de {{ $events->total() }} eventos
                    </span>

                    <div class="events-pagination-pages">
                        {{-- Flecha Anterior --}}
                        @if ($events->onFirstPage())
                            <span class="page-arrow disabled">&laquo;</span>
                        @else
                            <a href="{{ $events->previousPageUrl() }}" class="page-arrow">&laquo;</a>
                        @endif

                        {{-- Números de página --}}
                        @foreach ($events->getUrlRange(1, $events->lastPage()) as $page => $url)
                            @if ($page == $events->currentPage())
                                <span class="page-number active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-number">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Flecha Siguiente --}}
                        @if ($events->hasMorePages())
                            <a href="{{ $events->nextPageUrl() }}" class="page-arrow">&raquo;</a>
                        @else
                            <span class="page-arrow disabled">&raquo;</span>
                        @endif
                    </div>
                </div>
            @endif
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


