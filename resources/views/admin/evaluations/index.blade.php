@extends('layouts.admin-panel')

@section('title', 'Resultados de evaluaciones')

@section('content')

<h1 class="h4 mb-3">Resultados de evaluaciones</h1>

<div class="admin-card">
    <div class="admin-card-title">Proyectos evaluados con rúbrica</div>

    @if ($evaluations->isEmpty())
        <p class="mb-0">Aún no hay evaluaciones completadas.</p>
    @else
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Proyecto</th>
                        <th>Equipo</th>
                        <th>Evento</th>
                        <th>Rúbrica</th>
                        <th>Juez</th>
                        <th class="text-center" style="width:120px;">Puntuación</th>
                        <th class="text-center" style="width:140px;">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($evaluations as $evaluation)
                        <tr>
                            <td>{{ $evaluation->project->name ?? $evaluation->project_name }}</td>
                            <td>{{ $evaluation->project->team->name ?? 'Sin equipo' }}</td>
                            <td>{{ $evaluation->project->event->title ?? 'Sin evento' }}</td>
                            <td>{{ $evaluation->rubric->name ?? 'Sin rúbrica' }}</td>
                            <td>{{ $evaluation->judge->name ?? 'Sin juez' }}</td>
                            <td class="text-center fw-bold">
                                {{ number_format($evaluation->final_score ?? 0, 2) }}/10
                            </td>
                            <td class="text-center">
                                {{ optional($evaluation->evaluated_at)->format('d/m/Y H:i') ?? 'N/D' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

@endsection

