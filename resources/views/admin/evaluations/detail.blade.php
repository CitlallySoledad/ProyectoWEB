@extends('layouts.admin')

@section('title', 'Detalle de evaluación')

@push('styles')
<style>
    .detail-title {
        font-size: 2rem;
        font-weight: 600;
        color: #dbeafe;
        margin-bottom: 16px;
    }
    .detail-card {
        background: rgba(15, 23, 42, 0.95);
        border-radius: 22px;
        padding: 18px 22px;
        box-shadow: 0 18px 35px rgba(0,0,0,0.55);
        margin-bottom: 20px;
    }
    .detail-header-grid {
        display: grid;
        grid-template-columns: 2fr 2fr 1fr 1.5fr;
        column-gap: 12px;
        align-items: center;
        font-size: 0.9rem;
    }
    .detail-header-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #9ca3af;
        margin-bottom: 2px;
    }
    .detail-chip {
        display: inline-flex;
        align-items: center;
        padding: 4px 14px;
        border-radius: 999px;
        font-size: 0.8rem;
        background: rgba(37, 99, 235, 0.15);
        color: #bfdbfe;
    }

    .detail-section-title {
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 8px;
        color: #e5e7eb;
    }

    .detail-criteria-table {
        width: 100%;
        border-spacing: 0 8px;
    }
    .detail-criteria-table th {
        text-align: left;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: #9ca3af;
        padding-bottom: 4px;
    }
    .detail-criteria-row {
        background: #1d3557;
        border-radius: 16px;
    }
    .detail-criteria-row td {
        padding: 8px 12px;
        font-size: 0.9rem;
    }

    .detail-input,
    .detail-textarea,
    .detail-select {
        width: 100%;
        background: #020617;
        border-radius: 999px;
        border: 1px solid rgba(148,163,184,0.7);
        color: #e5e7eb;
        padding: 8px 14px;
        font-size: 0.9rem;
    }
    .detail-textarea {
        border-radius: 18px;
        min-height: 100px;
        resize: vertical;
    }
    .detail-input[disabled],
    .detail-textarea[disabled],
    .detail-select[disabled] {
        opacity: 0.6;
        cursor: default;
    }

    .detail-actions {
        display: flex;
        justify-content: space-between;
        margin-top: 18px;
    }
    .detail-btn {
        border-radius: 999px;
        border: none;
        padding: 8px 18px;
        font-size: 0.9rem;
        cursor: pointer;
    }
    .detail-btn-secondary {
        background: transparent;
        border: 1px solid rgba(148,163,184,0.8);
        color: #e5e7eb;
    }
    .detail-btn-primary {
        background: #2563eb;
        color: #f9fafb;
    }
</style>
@endpush

@section('content')
@php
    $readOnly = ($mode === 'view');
@endphp

<div class="admin-page-wrapper">
    <div class="admin-page-card">

        {{-- SIDEBAR --}}
        <div class="admin-sidebar">
            <a href="{{ route('admin.evaluations.projects_list') }}" class="admin-sidebar-back">
                <i class="bi bi-chevron-left"></i>
            </a>

            <a href="{{ route('admin.events.index') }}" class="admin-sidebar-icon">
                <i class="bi bi-calendar-event"></i>
            </a>
            <a href="{{ route('admin.evaluations.index') }}" class="admin-sidebar-icon">
                <i class="bi bi-people-fill"></i>
            </a>
            <a href="{{ route('admin.evaluations.projects_list') }}" class="admin-sidebar-icon active">
                <i class="bi bi-grid-1x2"></i>
            </a>
            <a href="{{ route('admin.users.index') }}" class="admin-sidebar-icon">
                <i class="bi bi-person-badge"></i>
            </a>
        </div>

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="admin-page-main">
            <div class="admin-page-header">
                <h1 class="detail-title">
                    Detalle de evaluación
                </h1>

                <div class="admin-page-user">
                    <i class="bi bi-person-circle"></i>
                    <span>Admin</span>
                </div>
            </div>

            {{-- ENCABEZADO DATOS GENERALES --}}
            <div class="detail-card">
                <div class="detail-header-grid">
                    <div>
                        <div class="detail-header-label">Nombre del equipo</div>
                        <div class="detail-chip">
                            {{ $evaluation->team ?? 'Equipo no definido' }}
                        </div>
                    </div>
                    <div>
                        <div class="detail-header-label">Proyecto</div>
                        <div class="detail-chip">
                            {{ $evaluation->project_name }}
                        </div>
                    </div>
                    <div>
                        <div class="detail-header-label">Estado</div>
                        <div class="detail-chip">
                            Evaluado
                        </div>
                    </div>
                    <div style="text-align:right;">
                        <div class="detail-header-label">Rúbrica</div>
                        <select name="rubric" form="detailForm" class="detail-select" {{ $readOnly ? 'disabled' : '' }}>
                            <option value="Rúbrica 1" {{ $evaluation->rubric === 'Rúbrica 1' ? 'selected' : '' }}>Rúbrica 1</option>
                            <option value="Rúbrica 2" {{ $evaluation->rubric === 'Rúbrica 2' ? 'selected' : '' }}>Rúbrica 2</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- FORMULARIO (se usa para VER o EDITAR) --}}
            <form id="detailForm"
                  action="{{ route('admin.evaluations.update', $evaluation) }}"
                  method="POST">
                @csrf
                @method('PUT')

                <div class="detail-card">
                    <div class="detail-section-title">Criterios de evaluación</div>

                    <table class="detail-criteria-table">
                        <thead>
                        <tr>
                            <th>Criterio</th>
                            <th>Descripción</th>
                            <th style="width:120px;">Puntaje</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr class="detail-criteria-row">
                            <td>Creatividad</td>
                            <td>Originalidad de la propuesta</td>
                            <td>
                                <input type="number" name="creativity"
                                       class="detail-input"
                                       value="{{ old('creativity', $evaluation->creativity) }}"
                                       min="0" max="10"
                                       {{ $readOnly ? 'disabled' : '' }}>
                            </td>
                        </tr>
                        <tr class="detail-criteria-row">
                            <td>Funcionalidad</td>
                            <td>Cumplimiento técnico del prototipo</td>
                            <td>
                                <input type="number" name="functionality"
                                       class="detail-input"
                                       value="{{ old('functionality', $evaluation->functionality) }}"
                                       min="0" max="10"
                                       {{ $readOnly ? 'disabled' : '' }}>
                            </td>
                        </tr>
                        <tr class="detail-criteria-row">
                            <td>Innovación</td>
                            <td>Impacto e innovación de la solución</td>
                            <td>
                                <input type="number" name="innovation"
                                       class="detail-input"
                                       value="{{ old('innovation', $evaluation->innovation) }}"
                                       min="0" max="10"
                                       {{ $readOnly ? 'disabled' : '' }}>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <div class="detail-card">
                    <div class="detail-section-title">Comentarios generales</div>
                    <textarea name="comments"
                              class="detail-textarea"
                              {{ $readOnly ? 'disabled' : '' }}
                              placeholder="Comentarios generales sobre el proyecto...">{{ old('comments', $evaluation->comments) }}</textarea>
                </div>

                <div class="detail-actions">
                    <a href="{{ route('admin.evaluations.projects_list') }}" class="detail-btn detail-btn-secondary">
                        Volver a la lista
                    </a>

                    @if (!$readOnly)
                        <button type="submit" class="detail-btn detail-btn-primary">
                            Guardar cambios
                        </button>
                    @endif
                </div>
            </form>

        </div>
    </div>
</div>
@endsection
