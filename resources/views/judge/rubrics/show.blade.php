@extends('layouts.judge-panel')

@section('title', 'Detalle de rúbrica')

@push('styles')
<style>
    .judge-rubric-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .judge-rubric-badge {
        border-radius: 999px;
        padding: 6px 14px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .judge-rubric-badge-activa {
        background: #22c55e;
        color: #052e16;
    }

    .judge-rubric-badge-inactiva {
        background: #f97373;
        color: #450a0a;
    }
</style>
@endpush

@section('content')
    <div class="judge-rubric-header">
        <div>
            <h1 class="h3 mb-1">{{ $rubric->name }}</h1>
            <div class="small text-light">
                Evento:
                <strong>{{ $rubric->event->name ?? 'Sin evento asignado' }}</strong>
            </div>
        </div>

        <div>
            <span class="judge-rubric-badge
                {{ $rubric->status === 'activa' ? 'judge-rubric-badge-activa' : 'judge-rubric-badge-inactiva' }}">
                {{ ucfirst($rubric->status) }}
            </span>
        </div>
    </div>

    {{-- TARJETA CON LISTA DE CRITERIOS --}}
    <div class="admin-card mb-3">
        <div class="admin-card-title">Criterios de la rúbrica</div>

        @if($rubric->criteria->isEmpty())
            <p class="mb-0">Esta rúbrica aún no tiene criterios configurados.</p>
        @else
            <table class="admin-table">
                <thead>
                <tr>
                    <th>Criterio</th>
                    <th>Descripción</th>
                    <th>Peso</th>
                    <th>Mín.</th>
                    <th>Máx.</th>
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
        @endif

        <button type="button" class="btn btn-sm btn-light rounded-pill mt-2">
            + Agregar criterio
        </button>
    </div>

    {{-- BOTÓN VOLVER --}}
    <div class="d-flex justify-content-between">
        <a href="{{ route('judge.rubrics.index') }}"
           class="admin-btn-secondary text-decoration-none">
            Volver a la lista de rúbricas
        </a>

        {{-- Aquí más adelante podrías poner un botón para editar la rúbrica --}}
        <button type="button" class="admin-btn-primary">
            Editar rúbrica
        </button>
    </div>
@endsection
