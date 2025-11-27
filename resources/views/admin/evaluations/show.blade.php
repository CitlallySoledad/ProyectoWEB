@extends('layouts.admin')

@section('title', 'Evaluación de proyecto')

@section('content')
<div class="admin-page-wrapper">
    <div class="admin-page-card">

        {{-- SIDEBAR IZQUIERDA --}}
        <div class="admin-sidebar">
            <a href="{{ route('admin.evaluations.index') }}" class="admin-sidebar-back">
                <i class="bi bi-chevron-left"></i>
            </a>

            <div class="admin-sidebar-icon">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="admin-sidebar-icon active">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="admin-sidebar-icon">
                <i class="bi bi-grid-1x2"></i>
            </div>
            <div class="admin-sidebar-icon">
                <i class="bi bi-person-badge"></i>
            </div>
        </div>

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="admin-page-main">
            <div class="d-flex justify-content-between mb-2">
                <h1 class="admin-page-title">
                    Evaluación de {{ $projectName }}
                </h1>

                <div class="admin-page-user">
                    <i class="bi bi-person-circle"></i>
                    <span>Admin</span>
                </div>
            </div>

            {{-- FORMULARIO DE EVALUACIÓN (demo) --}}
            <form action="{{ route('admin.evaluations.store', urlencode($projectName)) }}" method="POST">
                @csrf

                <div class="admin-form-grid">
                    <div class="admin-form-col">
                        {{-- Evento --}}
                        <div class="admin-form-row">
                            <label class="admin-form-label">Eventos Tec</label>
                            <input type="text" class="admin-form-input" value="{{ $projectName }}" disabled>
                            <input type="hidden" name="project_name" value="{{ $projectName }}">

                        </div>

                        {{-- Creatividad --}}
                        <div class="admin-form-row">
                            <label class="admin-form-label" for="creativity">Creatividad</label>
                            <input type="number" id="creativity" name="creativity"
                                   class="admin-form-input" value="10" min="0" max="10">
                        </div>

                        {{-- Funcionalidad --}}
                        <div class="admin-form-row">
                            <label class="admin-form-label" for="functionality">Funcionalidad</label>
                            <input type="number" id="functionality" name="functionality"
                                   class="admin-form-input" value="10" min="0" max="10">
                        </div>

                        {{-- Innovación --}}
                        <div class="admin-form-row">
                            <label class="admin-form-label" for="innovation">Innovación</label>
                            <input type="number" id="innovation" name="innovation"
                                   class="admin-form-input" value="10" min="0" max="10">
                        </div>
                    </div>

                    <div class="admin-form-col">
                        {{-- Rúbrica --}}
                        <div class="admin-form-row d-flex justify-content-between align-items-center">
                            <label class="admin-form-label">Rúbrica</label>
                            <select name="rubric" class="admin-form-select" style="max-width: 180px;">
                                @foreach($rubrics as $rubric)
                                    <option value="{{ $rubric }}">{{ $rubric }}</option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Comentarios --}}
                        <div class="admin-form-row" style="margin-top: 24px;">
                            <label class="admin-form-label" for="comments">Comentarios</label>
                            <textarea id="comments" name="comments"
                                      class="admin-form-textarea"
                                      placeholder="Comentarios"></textarea>
                        </div>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="admin-form-footer">
                    <a href="{{ route('admin.evaluations.index') }}" class="btn admin-btn-pill admin-btn-secondary">
                        Cancelar
                    </a>

                    <button type="submit" class="admin-btn-pill admin-btn-primary">
                        Guardar
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
