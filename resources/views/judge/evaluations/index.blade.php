@extends('layouts.judge-panel')

@section('title', 'Mis evaluaciones')

@section('content')
    <h1 class="h3 mb-3">Evaluación del proyecto</h1>

    <div class="admin-card">
        <table class="admin-table">
            <thead>
            <tr>
                <th>Nombre</th>
                <th>Equipo</th>
                <th>Estado</th>
                <th style="width: 220px;">Acciones</th>
            </tr>
            </thead>
            <tbody>
            @forelse($evaluations as $evaluation)
                <tr>
                    <td>{{ $evaluation->project->name }}</td>
                    <td>{{ $evaluation->project->team?->name ?? '-' }}</td>
                    <td class="text-capitalize">{{ $evaluation->status }}</td>
                    <td>
                        <a href="{{ route('judge.evaluations.show', $evaluation->project) }}"
                           class="btn btn-sm btn-light rounded-pill me-1">Ver</a>
                        <a href="{{ route('judge.evaluations.show', $evaluation->project) }}"
                           class="btn btn-sm btn-primary rounded-pill me-1">Editar</a>
                        {{-- si quieres permitir eliminar --}}
                        {{-- <form ...> --}}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">Aún no has registrado evaluaciones.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
