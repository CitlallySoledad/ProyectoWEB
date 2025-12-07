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
        <form action="{{ route('admin.evaluations.store', urlencode($projectName)) }}" method="POST">
            @csrf

            <div class="row g-3">
                {{-- COLUMNA IZQUIERDA --}}
                <div class="col-md-6">

                    {{-- Evento --}}
                    <div class="mb-3">
                        <label class="form-label">Eventos Tec</label>
                        <input
                            type="text"
                            class="form-control rounded-pill"
                            value="{{ $projectName }}"
                            disabled
                        >
                        <input type="hidden" name="project_name" value="{{ $projectName }}">
                    </div>

                    {{-- Creatividad --}}
                    <div class="mb-3">
                        <label class="form-label" for="creativity">Creatividad</label>
                        <input
                            type="number"
                            id="creativity"
                            name="creativity"
                            class="form-control rounded-pill"
                            value="{{ old('creativity', 10) }}"
                            min="0"
                            max="10"
                        >
                    </div>

                    {{-- Funcionalidad --}}
                    <div class="mb-3">
                        <label class="form-label" for="functionality">Funcionalidad</label>
                        <input
                            type="number"
                            id="functionality"
                            name="functionality"
                            class="form-control rounded-pill"
                            value="{{ old('functionality', 10) }}"
                            min="0"
                            max="10"
                        >
                    </div>

                    {{-- Innovación --}}
                    <div class="mb-3">
                        <label class="form-label" for="innovation">Innovación</label>
                        <input
                            type="number"
                            id="innovation"
                            name="innovation"
                            class="form-control rounded-pill"
                            value="{{ old('innovation', 10) }}"
                            min="0"
                            max="10"
                        >
                    </div>
                </div>

                {{-- COLUMNA DERECHA --}}
                <div class="col-md-6">

                    {{-- Rúbrica --}}
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <label class="form-label mb-0">Rúbrica</label>
                        <select name="rubric" class="form-select rounded-pill" style="max-width: 220px;">
                            @foreach($rubrics as $rubric)
                                <option value="{{ $rubric }}" {{ old('rubric') == $rubric ? 'selected' : '' }}>
                                    {{ $rubric }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Comentarios --}}
                    <div class="mb-3">
                        <label class="form-label" for="comments">Comentarios</label>
                        <textarea
                            id="comments"
                            name="comments"
                            class="form-control"
                            rows="6"
                            style="border-radius: 18px;"
                            placeholder="Comentarios"
                        >{{ old('comments') }}</textarea>
                    </div>
                </div>
            </div>

            {{-- BOTONES --}}
            <div class="mt-3 d-flex justify-content-between">
                <a href="{{ route('admin.evaluations.projects_list') }}"
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
