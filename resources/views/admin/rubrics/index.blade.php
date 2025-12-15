@extends('layouts.admin-panel')

@section('title', 'Gestión de rúbricas')

@push('styles')
<style>
    /* ===== COLORES BLANCOS PARA TEXTO ===== */
    .h3, .mb-0, .admin-card-title, .admin-table th, .admin-table td, .form-label {
        color: #fff !important;
    }
    
    .text-muted {
        color: #94a3b8 !important;
    }
    
    .judge-search-rubric {
        border-radius: 999px;
        border: none;
        padding: 8px 16px;
        font-size: 0.95rem;
        width: 100%;
        max-width: 260px;
    }
    .judge-new-rubric-btn {
        border-radius: 18px;
        background: #065f46;
        color: #e5e7eb;
        padding: 8px 20px;
        border: none;
        font-weight: 600;
        box-shadow: 0 12px 30px rgba(0,0,0,0.4);
        text-decoration: none;
        display: inline-block;
    }
    .judge-new-rubric-btn:hover {
        background: #047857;
        color: #ffffff;
    }
    
    /* ===== PAGINACIÓN COMPACTA RÚBRICAS ===== */
    .rubrics-pagination {
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

    .rubrics-pagination-info {
        white-space: nowrap;
        opacity: 0.85;
        font-weight: 500;
    }

    .rubrics-pagination-pages {
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
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Gestión de rúbricas</h1>
        <a href="{{ route('admin.rubrics.create') }}" target="_blank" class="judge-new-rubric-btn">+ Nueva Rúbrica</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 py-2 mb-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show rounded-3 py-2 mb-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="admin-card mb-3">
        <table class="admin-table">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Evento Asignado</th>
                <th>Estado</th>
                <th style="width: 220px;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($rubrics as $rubricItem)
                <tr @if($rubric && $rubric->id === $rubricItem->id) style="box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.35);" @endif>
                    <td><strong>{{ $rubricItem->name }}</strong></td>
                    <td>
                        @php
                            $assignedEvents = $rubricItem->events();
                        @endphp
                        @if($assignedEvents->isNotEmpty())
                            @foreach($assignedEvents as $assignedEvent)
                                <span class="badge bg-info me-1 mb-1">{{ $assignedEvent->title }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">Sin asignar</span>
                        @endif
                    </td>
                    <td>
                        @if($rubricItem->status === 'activa')
                            <span class="badge bg-success">Activa</span>
                        @else
                            <span class="badge bg-secondary">Inactiva</span>
                        @endif
                    </td>
                    <td>
                        <div style="display: flex; gap: 6px; flex-wrap: wrap; align-items: center;">
                            <a href="{{ route('admin.rubrics.edit', $rubricItem) }}" class="btn btn-sm btn-primary rounded-pill">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <button form="delete-rubric-{{ $rubricItem->id }}" type="submit" class="btn btn-sm btn-danger rounded-pill" onclick="return confirm('¿Eliminar rúbrica? Esta acción no se puede deshacer.')">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 2rem; color: #94a3b8;"></i>
                        <p class="text-muted mt-2 mb-0">No hay rúbricas registradas.</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
        
        {{-- PAGINACIÓN COMPACTA --}}
        @if ($rubrics->hasPages())
            <div class="rubrics-pagination">
                <span class="rubrics-pagination-info">
                    Mostrando {{ $rubrics->firstItem() }} - {{ $rubrics->lastItem() }} de {{ $rubrics->total() }} rúbricas
                </span>

                <div class="rubrics-pagination-pages">
                    {{-- Flecha Anterior --}}
                    @if ($rubrics->onFirstPage())
                        <span class="page-arrow disabled">&laquo;</span>
                    @else
                        <a href="{{ $rubrics->previousPageUrl() }}" class="page-arrow">&laquo;</a>
                    @endif

                    {{-- Números de página --}}
                    @foreach ($rubrics->getUrlRange(1, $rubrics->lastPage()) as $page => $url)
                        @if ($page == $rubrics->currentPage())
                            <span class="page-number active">{{ $page }}</span>
                        @else
                            <a href="{{ $url }}" class="page-number">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Flecha Siguiente --}}
                    @if ($rubrics->hasMorePages())
                        <a href="{{ $rubrics->nextPageUrl() }}" class="page-arrow">&raquo;</a>
                    @else
                        <span class="page-arrow disabled">&raquo;</span>
                    @endif
                </div>
            </div>
        @endif
    </div>

    {{-- Formularios ocultos para eliminar rúbricas --}}
    @foreach($rubrics as $rubricItem)
        <form id="delete-rubric-{{ $rubricItem->id }}" action="{{ route('admin.rubrics.destroy', $rubricItem) }}" method="POST" style="display:none;">
            @csrf
            @method('DELETE')
        </form>
    @endforeach
@endsection
