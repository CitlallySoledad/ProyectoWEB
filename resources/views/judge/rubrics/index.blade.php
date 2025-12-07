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
</style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Gestión de rúbricas</h1>
        <button class="judge-new-rubric-btn" type="button">
            + Nueva Rúbrica
        </button>
    </div>

    <div class="mb-3">
        <input type="text"
               class="judge-search-rubric"
               placeholder="Buscar rúbrica"
               value="{{ request('q') }}">
    </div>

    <div class="admin-card mb-3">
        <table class="admin-table">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Evento</th>
                <th>Estado</th>
                <th style="width: 220px;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @foreach($rubrics as $rubricItem)
                <tr @if($rubric && $rubric->id === $rubricItem->id) style="box-shadow: 0 0 0 2px rgba(248,250,252,0.35);" @endif>
                    <td>{{ $rubricItem->name }}</td>
                    <td>{{ $rubricItem->event?->name ?? '-' }}</td>
                    <td class="text-capitalize">{{ $rubricItem->status }}</td>
                    <td>
                        <a href="{{ route('judge.rubrics.index', ['rubric' => $rubricItem->id]) }}"
                           class="btn btn-sm btn-light rounded-pill me-1">Ver</a>
                        <button class="btn btn-sm btn-primary rounded-pill me-1" type="button">Editar</button>
                        <button class="btn btn-sm btn-outline-light rounded-pill" type="button">Eliminar</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    {{-- Si hay rúbrica seleccionada, mostramos criterios debajo --}}
    @if($rubric)
        <div class="admin-card">
            <table class="admin-table">
                <thead>
                <tr>
                    <th>Criterio</th>
                    <th>Descripción</th>
                    <th>Peso</th>
                    <th>Min</th>
                    <th>Max</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rubric->criteria as $criterion)
                    <tr>
                        <td>{{ $criterion->name }}</td>
                        <td>{{ $criterion->description }}</td>
                        <td>{{ $criterion->weight }}</td>
                        <td>{{ $criterion->min_score }}</td>
                        <td>{{ $criterion->max_score }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <button class="btn btn-sm btn-light rounded-pill mt-2" type="button">
                + Agregar criterio
            </button>
        </div>
    @endif
@endsection
