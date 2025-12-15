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
                <td>{{ $evaluation->project_name ?? $project->name }}</td>
                <td>{{ $evaluation?->rubric?->name ?? $project->rubric?->name ?? '—' }}</td>
                <td class="text-capitalize">{{ $evaluation->status ?? 'pendiente' }}</td>
                <td>
                    {{ $evaluation->final_score ?? '—' }}
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    {{-- Documentos del proyecto --}}
    @if($project->documents->isNotEmpty())
        <div class="admin-card mb-3">
            <div class="admin-card-title mb-3">
                <i class="bi bi-file-pdf"></i> Documentos del Proyecto
            </div>
            <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                @foreach($project->documents as $doc)
                    <a href="{{ asset('storage/' . $doc->file_path) }}" 
                       target="_blank" 
                       class="btn btn-sm"
                       style="background: rgba(139,92,246,0.2); border: 1px solid #8b5cf6; color: #e5e7eb; padding: 8px 16px; border-radius: 999px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px;">
                        <i class="bi bi-file-pdf-fill" style="font-size: 1.2rem; color: #c084fc;"></i>
                        <div style="text-align: left;">
                            <div style="font-weight: 600; font-size: 0.9rem;">{{ $doc->original_name }}</div>
                            @if($doc->description)
                                <div style="font-size: 0.75rem; color: #cbd5e1; margin-top: 2px;">{{ Str::limit($doc->description, 50) }}</div>
                            @endif
                            <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 2px;">{{ number_format($doc->file_size / 1024, 2) }} KB</div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    {{-- FORMULARIO DE PUNTAJES POR CRITERIO --}}
    <form action="{{ route('judge.evaluations.store', $project) }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Tabla de criterios --}}
        <div class="admin-card mb-3">
            <table class="admin-table judge-criteria-table">
                <thead>
                <tr>
                    <th>Criterio</th>
                    <th>Descripción</th>
                    <th style="width: 80px;">Peso</th>
                    <th style="width: 80px;">Min</th>
                    <th style="width: 80px;">Max</th>
                    <th style="width: 90px;">Puntaje</th>
                    <th>Comentario</th>
                </tr>
                </thead>
                <tbody>
                @foreach($criteria as $criterion)
                    @php
                        $existing = $existingScores->get($criterion->id);
                        $prefillScore = old("scores.$loop->index.score", optional($existing)->score ?? '');
                        $prefillComment = old("scores.$loop->index.comment", optional($existing)->comment ?? '');
                    @endphp
                    <tr>
                        <td>{{ $criterion->name }}</td>
                        <td>{{ $criterion->description }}</td>
                        <td class="text-center">{{ $criterion->weight ?? $criterion->peso ?? '—' }}</td>
                        <td class="text-center">{{ $criterion->min ?? 0 }}</td>
                        <td class="text-center">{{ $criterion->max ?? 10 }}</td>
                        <td>
                            <input type="hidden"
                                   name="scores[{{ $loop->index }}][criterion_id]"
                                   value="{{ $criterion->id }}">

                            <input type="number"
                                   name="scores[{{ $loop->index }}][score]"
                                   class="form-control form-control-sm rounded-pill"
                                   min="{{ $criterion->min ?? 0 }}"
                                   max="{{ $criterion->max ?? 10 }}"
                                   value="{{ $prefillScore }}"
                                   required>
                        </td>
                        <td>
                            <input type="text"
                                   name="scores[{{ $loop->index }}][comment]"
                                   class="form-control form-control-sm rounded-pill"
                                   value="{{ $prefillComment }}">
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

        {{-- SECCIÓN DE EVIDENCIAS --}}
        <div class="admin-card mb-3">
            <div class="admin-card-title mb-3">Evidencias de Evaluación</div>
            
            <div id="evidence-container">
                <div class="evidence-input-group mb-3">
                    <div class="row g-2">
                        <div class="col-md-6">
                            <label class="form-label">Archivo (PDF, Imagen, Documento)</label>
                            <input type="file"
                                   name="evidence_files[]"
                                   class="form-control"
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                                   placeholder="Selecciona un archivo">
                            <small class="text-muted">Máx. 10 MB. Formatos: PDF, JPG, PNG, DOC, DOCX</small>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Descripción (opcional)</label>
                            <input type="text"
                                   name="evidence_descriptions[]"
                                   class="form-control"
                                   placeholder="Ej: Captura de pantalla del proyecto...">
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botón para agregar más evidencias --}}
            <button type="button" class="btn btn-sm btn-outline-secondary" id="add-evidence-btn">
                <i class="bi bi-plus me-1"></i> Añadir otra evidencia
            </button>
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

@push('scripts')
<script>
    document.getElementById('add-evidence-btn').addEventListener('click', function(e) {
        e.preventDefault();
        const container = document.getElementById('evidence-container');
        const newGroup = `
            <div class="evidence-input-group mb-3">
                <div class="row g-2">
                    <div class="col-md-6">
                        <input type="file"
                               name="evidence_files[]"
                               class="form-control"
                               accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <small class="text-muted">Máx. 10 MB</small>
                    </div>
                    <div class="col-md-6 d-flex gap-2 align-items-end">
                        <input type="text"
                               name="evidence_descriptions[]"
                               class="form-control"
                               placeholder="Descripción...">
                        <button type="button" class="btn btn-sm btn-danger remove-evidence-btn">
                            Quitar
                        </button>
                    </div>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', newGroup);
    });

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-evidence-btn')) {
            e.preventDefault();
            e.target.closest('.evidence-input-group').remove();
        }
    });
</script>
@endpush
