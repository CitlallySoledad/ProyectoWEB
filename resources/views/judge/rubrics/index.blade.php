@extends('layouts.judge-panel')

@section('title', 'Gestión de rúbricas')

@push('styles')
<style>
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
    }
    
    /* Paginación estilo compacto */
    .rubrics-pagination {
        margin: 24px auto 8px;
        padding: 8px 16px;
        border-radius: 999px;
        background: rgba(15, 23, 42, 0.95);
        display: flex;
        align-items: center;
        gap: 18px;
        width: auto;
        max-width: 100%;
        font-size: 0.85rem;
        color: #e5e7eb;
        justify-content: center;
    }
    
    .rubrics-pagination-info {
        white-space: nowrap;
        opacity: 0.85;
    }
    
    .rubrics-pagination-pages {
        display: flex;
        align-items: center;
        gap: 6px;
    }
    
    .page-number,
    .page-arrow {
        min-width: 32px;
        height: 32px;
        padding: 0 10px;
        border-radius: 999px;
        border: 1px solid #1d4ed8;
        background: rgba(30, 58, 138, 0.2);
        color: #93c5fd;
        display: flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        cursor: pointer;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .page-number:hover,
    .page-arrow:hover {
        background: #1d4ed8;
        color: #fff;
        transform: translateY(-1px);
    }
    
    .page-number.active {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: #fff;
        font-weight: 600;
        border-color: transparent;
    }
    
    .page-arrow.disabled {
        opacity: 0.4;
        cursor: not-allowed;
        pointer-events: none;
    }
</style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Rúbricas disponibles</h1>
        <span class="badge bg-info">Solo lectura</span>
    </div>

    <div class="alert alert-info rounded-3 py-2 mb-3">
        <i class="bi bi-info-circle me-2"></i>
        Puedes consultar las rúbricas disponibles. Para modificarlas, contacta al administrador.
    </div>

    <div class="admin-card mb-3">
        <table class="admin-table">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Evento</th>
                <th>Estado</th>
                <th style="width: 100px;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($rubrics as $rubricItem)
                <tr @if($rubric && $rubric->id === $rubricItem->id) style="box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.35);" @endif>
                    <td><strong>{{ $rubricItem->name }}</strong></td>
                    <td>{{ $rubricItem->event?->title ?? '-' }}</td>
                    <td>
                        @if($rubricItem->status === 'activa')
                            <span class="badge bg-success">Activa</span>
                        @else
                            <span class="badge bg-secondary">Inactiva</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('judge.rubrics.show', $rubricItem) }}"
                            class="btn btn-sm btn-light rounded-pill">
                            <i class="bi bi-eye"></i> Ver
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-4">
                        <i class="bi bi-inbox" style="font-size: 2rem; color: #94a3b8;"></i>
                        <p class="text-muted mt-2 mb-0">No hay rúbricas disponibles.</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Paginación estilo compacto --}}
    @if($rubrics->hasPages())
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

    {{-- Si hay rúbrica seleccionada, mostramos criterios debajo (solo lectura) --}}
    @if($rubric)
        <div class="admin-card">
            <div class="admin-card-title">Criterios de: {{ $rubric->name }}</div>
            <div class="alert alert-warning rounded-3 py-2 mb-3">
                <i class="bi bi-lock me-2"></i>
                Los criterios son de solo lectura. Solo el administrador puede modificarlos.
            </div>
            
            <table class="admin-table">
                <thead>
                <tr>
                    <th>Criterio</th>
                    <th>Descripción</th>
                    <th>Peso</th>
                    <th>Puntuación Mínima</th>
                    <th>Puntuación Máxima</th>
                </tr>
                </thead>
                <tbody>
                @forelse($rubric->criteria as $criterion)
                    <tr>
                        <td><strong>{{ $criterion->name }}</strong></td>
                        <td>{{ $criterion->description ?? '-' }}</td>
                        <td>{{ $criterion->weight }}</td>
                        <td>{{ $criterion->min_score }}</td>
                        <td>{{ $criterion->max_score }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-3">
                            <small class="text-muted">Esta rúbrica no tiene criterios definidos.</small>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    @endif

@endsection
