@extends('layouts.judge-panel')

@section('title', 'Detalle de evaluación')

@push('styles')
<style>
    .judge-criteria-table th,
    .judge-criteria-table td {
        border-bottom: 1px solid rgba(15,23,42,0.7);
    }
    .judge-final-bar {
        border-radius: 999px;
        background: #2563eb;
        color: #fff;
        text-align: center;
        padding: 8px 16px;
        font-weight: 600;
        margin-top: 10px;
    }
</style>
@endpush

@section('content')
    <h1 class="h3 mb-3">Detalle de evaluación</h1>

    {{-- Tarjeta con info general del proyecto/evaluación --}}
    <div class="admin-card mb-3">
        <table class="admin-table mb-0">
            <thead>
            <tr>
                <th>Proyecto</th>
                <th>Rúbrica</th>
                <th>Estado</th>
                <th style="width: 200px;">Calificación final</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>{{ $evaluation->project_name }}</td>
                <td>{{ $evaluation->rubric ?? '—' }}</td>
                <td class="text-capitalize">{{ $evaluation->status }}</td>
                <td>
                    {{ $evaluation->final_score ?? '—' }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    {{-- FORMULARIO DE PUNTAJES POR CRITERIO --}}
    <form action="{{ route('judge.evaluation.store', $evaluation) }}" method="POST">
        @csrf

        {{-- Tabla de criterios --}}
        <div class="admin-card mb-3">
            <table class="admin-table judge-criteria-table">
                <thead>
                <tr>
                    <th>Criterio</th>
                    <th>Descripción</th>
                    <th style="width: 90px;">Puntaje</th>
                    <th>Comentario</th>
                </tr>
                </thead>
                <tbody>
                @foreach($criteria as $criterion)
                    @php
                        // Si ya existen scores, puedes cargarlos así si el controlador los pasa
                        $currentScore = $evaluation->scores
                            ->firstWhere('rubric_criterion_id', $criterion->id) ?? null;
                    @endphp
                    <tr>
                        <td>{{ $criterion->name }}</td>
                        <td>{{ $criterion->description }}</td>
                        <td>
                            <input type="hidden"
                                   name="scores[{{ $loop->index }}][criterion_id]"
                                   value="{{ $criterion->id }}">

                            <input type="number"
                                   name="scores[{{ $loop->index }}][score]"
                                   class="form-control form-control-sm rounded-pill"
                                   min="{{ $criterion->min_score }}"
                                   max="{{ $criterion->max_score }}"
                                   value="{{ old("scores.$loop->index.score", $currentScore->score ?? '') }}"
                                   required>
                        </td>
                        <td>
                            <input type="text"
                                   name="scores[{{ $loop->index }}][comment]"
                                   class="form-control form-control-sm rounded-pill"
                                   value="{{ old("scores.$loop->index.comment", $currentScore->comment ?? '') }}">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{-- Comentarios generales --}}
        <div class="admin-card mb-3">
            <label class="form-label">Comentarios generales</label>
            <textarea name="general_comments"
                      rows="3"
                      class="form-control"
                      placeholder="Comentarios generales sobre el proyecto...">{{ old('general_comments', $evaluation->general_comments ?? '') }}</textarea>

            <div class="judge-final-bar mt-3">
                Calificación final: {{ $evaluation->final_score ?? '—' }}
            </div>
        </div>

        {{-- Botones inferiores --}}
        <div class="d-flex justify-content-between">
            <a href="{{ route('judge.projects.index') }}"
               class="admin-btn-secondary text-decoration-none">
                Volver a la lista
            </a>

            <button type="submit" class="admin-btn-primary">
                Guardar evaluación
            </button>
        </div>
    </form>
@endsection
