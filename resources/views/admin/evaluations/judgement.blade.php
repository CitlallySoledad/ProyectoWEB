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

            <form action="{{ route('admin.evaluations.judgement.store', $evaluation->id) }}" method="POST">
                @csrf

                <div class="admin-form-grid">
                    <div class="admin-form-col">

                        {{-- Juez --}}
                        <div class="admin-form-row">
                            <label class="admin-form-label" for="judge">Juez</label>
                            <input
                                type="text"
                                id="judge"
                                name="judge"
                                class="admin-form-input"
                                value="{{ old('judge', $judge) }}"
                            >
                        </div>

                        {{-- Fecha --}}
                        <div class="admin-form-row">
                            <label class="admin-form-label" for="evaluated_at">Fecha</label>
                            <input
                                type="date"
                                id="evaluated_at"
                                name="evaluated_at"
                                class="admin-form-input"
                                value="{{ old('evaluated_at', $date->format('Y-m-d')) }}"
                            >
                        </div>

                        {{-- Equipo --}}
                        <div class="admin-form-row">
                            <label class="admin-form-label" for="team">Equipo</label>
                            <input
                                type="text"
                                id="team"
                                name="team"
                                class="admin-form-input"
                                value="{{ old('team', $team) }}"
                            >
                        </div>

                        {{-- Total calculado --}}
                        <div class="admin-form-row">
                            <label class="admin-form-label" for="total_score">Total calculado</label>
                            <input
                                type="number"
                                id="total_score"
                                name="total_score"
                                class="admin-form-input"
                                value="{{ old('total_score', $total) }}"
                                min="0"
                            >
                        </div>
                    </div>

                    <div class="admin-form-col">
                        {{-- Comentarios --}}
                        <div class="admin-form-row" style="margin-top: 24px;">
                            <label class="admin-form-label" for="comments">Comentarios</label>
                            <textarea
                                id="comments"
                                name="comments"
                                class="admin-form-textarea"
                                placeholder="Comentarios"
                            >{{ old('comments', $evaluation->comments) }}</textarea>
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

