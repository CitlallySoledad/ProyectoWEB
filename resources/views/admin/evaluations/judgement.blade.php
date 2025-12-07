@extends('layouts.admin-panel')

@section('title', 'Evaluación de proyecto')

@section('content')

    <h1 class="h4 mb-3">Evaluación de {{ $projectName }}</h1>

    {{-- ERRORES DE VALIDACIÓN --}}
    @if ($errors->any())
        <div class="alert alert-danger rounded-3 py-2">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-card">
        <form action="{{ route('admin.evaluations.judgement.store', $evaluation->id) }}" method="POST">
            @csrf

            <div class="row g-3">
                {{-- COLUMNA IZQUIERDA --}}
                <div class="col-md-6">
                    {{-- Juez --}}
                    <div class="mb-3">
                        <label class="form-label" for="judge">Juez</label>
                        <input
                            type="text"
                            id="judge"
                            name="judge"
                            class="form-control rounded-pill"
                            value="{{ old('judge', $judge) }}"
                            placeholder="Nombre del juez"
                        >
                    </div>

                    {{-- Fecha --}}
                    <div class="mb-3">
                        <label class="form-label" for="evaluated_at">Fecha</label>
                        <input
                            type="date"
                            id="evaluated_at"
                            name="evaluated_at"
                            class="form-control rounded-pill"
                            value="{{ old('evaluated_at', $date->format('Y-m-d')) }}"
                        >
                    </div>

                    {{-- Equipo --}}
                    <div class="mb-3">
                        <label class="form-label" for="team">Equipo</label>
                        <input
                            type="text"
                            id="team"
                            name="team"
                            class="form-control rounded-pill"
                            value="{{ old('team', $team) }}"
                            placeholder="Nombre del equipo"
                        >
                    </div>

                    {{-- Total calculado --}}
                    <div class="mb-3">
                        <label class="form-label" for="total_score">Total calculado</label>
                        <input
                            type="number"
                            id="total_score"
                            name="total_score"
                            class="form-control rounded-pill"
                            value="{{ old('total_score', $total) }}"
                            min="0"
                        >
                    </div>
                </div>

                {{-- COLUMNA DERECHA --}}
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="form-label" for="comments">Comentarios</label>
                        <textarea
                            id="comments"
                            name="comments"
                            class="form-control"
                            rows="6"
                            style="border-radius: 18px;"
                            placeholder="Comentarios del juez"
                        >{{ old('comments', $evaluation->comments) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- BOTONES --}}
            <div class="mt-3 d-flex justify-content-between">
                <a href="{{ route('admin.evaluations.index') }}"
                   class="admin-btn-secondary text-decoration-none">
                    Cancelar
                </a>

                <button type="submit" class="admin-btn-primary">
                    Guardar
                </button>
            </div>
        </form>
    </div>

@endsection
