@extends('layouts.judge-panel')

@section('title', 'Ver Evaluación')

@push('styles')
<style>
    .info-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .info-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .info-row:last-child {
        border-bottom: none;
    }
    .info-label {
        font-weight: 600;
        color: #64748b;
        width: 180px;
        flex-shrink: 0;
    }
    .info-value {
        color: #1e293b;
    }
    .score-card {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        margin-bottom: 24px;
    }
    .score-value {
        font-size: 48px;
        font-weight: bold;
        margin: 12px 0;
    }
    .criteria-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 16px;
    }
    .criteria-table th {
        background: #f8fafc;
        padding: 12px;
        text-align: left;
        font-weight: 600;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
    }
    .criteria-table td {
        padding: 12px;
        border-bottom: 1px solid #f1f5f9;
    }
    .criteria-table tr:last-child td {
        border-bottom: none;
    }
    .score-badge {
        display: inline-block;
        background: #2563eb;
        color: white;
        padding: 4px 12px;
        border-radius: 999px;
        font-weight: 600;
        font-size: 14px;
    }
    .evidence-item {
        display: inline-block;
        background: #f1f5f9;
        padding: 8px 16px;
        border-radius: 8px;
        margin: 4px;
        text-decoration: none;
        color: #475569;
        transition: all 0.2s;
    }
    .evidence-item:hover {
        background: #e2e8f0;
        color: #1e293b;
    }
    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin-bottom: 16px;
        padding-bottom: 8px;
        border-bottom: 2px solid #e2e8f0;
    }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">📋 Evaluación Completada</h1>
    <div>
        <a href="{{ route('judge.evaluations.show', $evaluation->project_id) }}" 
           class="btn btn-warning">
            <i class="bi bi-pencil me-2"></i>Editar
        </a>
        <a href="{{ route('judge.evaluations.export-pdf', $evaluation) }}" 
           class="btn btn-danger"
           target="_blank">
            <i class="bi bi-file-pdf me-2"></i>Descargar PDF
        </a>
        <a href="{{ route('judge.evaluations.index') }}" 
           class="btn btn-secondary">
            <i class="bi bi-arrow-left me-2"></i>Volver
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Información del Proyecto -->
        <div class="info-card">
            <div class="section-title">Información del Proyecto</div>
            
            <div class="info-row">
                <div class="info-label">Proyecto:</div>
                <div class="info-value"><strong>{{ $evaluation->project->name }}</strong></div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Equipo:</div>
                <div class="info-value">{{ $evaluation->project->team?->name ?? '—' }}</div>
            </div>
            
            @if($evaluation->project->team && $evaluation->project->team->members->isNotEmpty())
            <div class="info-row">
                <div class="info-label">Miembros:</div>
                <div class="info-value">
                    {{ $evaluation->project->team->members->pluck('name')->join(', ') }}
                </div>
            </div>
            @endif
            
            <div class="info-row">
                <div class="info-label">Rúbrica:</div>
                <div class="info-value">{{ $evaluation->rubric?->name ?? '—' }}</div>
            </div>
            
            <div class="info-row">
                <div class="info-label">Fecha de Evaluación:</div>
                <div class="info-value">{{ $evaluation->evaluated_at?->format('d/m/Y H:i') ?? '—' }}</div>
            </div>
        </div>

        <!-- Criterios Evaluados -->
        <div class="info-card">
            <div class="section-title">Criterios Evaluados</div>
            
            <table class="criteria-table">
                <thead>
                    <tr>
                        <th style="width: 40%;">Criterio</th>
                        <th style="width: 15%; text-align: center;">Puntaje</th>
                        <th style="width: 45%;">Comentario</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($evaluation->rubric->criteria ?? [] as $criterion)
                        @php
                            $score = $evaluation->scores->firstWhere('rubric_criterion_id', $criterion->id);
                        @endphp
                        <tr>
                            <td>
                                <strong>{{ $criterion->name }}</strong>
                                @if($criterion->description)
                                    <br><small class="text-muted">{{ $criterion->description }}</small>
                                @endif
                            </td>
                            <td style="text-align: center;">
                                @if($score)
                                    <span class="score-badge">{{ $score->score }}/{{ $criterion->max_score }}</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                <small>{{ $score->comment ?? '—' }}</small>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Comentarios Generales -->
        @if($evaluation->general_comments)
        <div class="info-card">
            <div class="section-title">Comentarios Generales</div>
            <p style="color: #475569; line-height: 1.6; margin: 0;">
                {{ $evaluation->general_comments }}
            </p>
        </div>
        @endif

        <!-- Evidencias -->
        @if($evaluation->evidences->isNotEmpty())
        <div class="info-card">
            <div class="section-title">Evidencias Adjuntas</div>
            <div>
                @foreach($evaluation->evidences as $evidence)
                    <a href="{{ asset('storage/' . $evidence->file_path) }}" 
                       target="_blank" 
                       class="evidence-item">
                        <i class="bi bi-file-earmark me-2"></i>{{ $evidence->original_name }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="col-md-4">
        <!-- Puntaje Final -->
        <div class="score-card">
            <div style="font-size: 16px; opacity: 0.9;">Puntaje Final</div>
            <div class="score-value">{{ number_format($evaluation->final_score ?? 0, 2) }}</div>
            <div style="font-size: 14px; opacity: 0.8;">de 10 puntos</div>
        </div>

        <!-- Estado -->
        <div class="info-card">
            <div class="section-title">Estado</div>
            <div style="text-align: center; padding: 20px;">
                <div style="font-size: 48px; margin-bottom: 8px;">✅</div>
                <div style="font-size: 18px; font-weight: 600; color: #059669;">
                    Evaluación Completada
                </div>
            </div>
        </div>

        <!-- Documentos del Proyecto -->
        @if($evaluation->project->documents && $evaluation->project->documents->isNotEmpty())
        <div class="info-card">
            <div class="section-title">Documentos del Proyecto</div>
            <div>
                @foreach($evaluation->project->documents as $doc)
                    <a href="{{ asset('storage/' . $doc->file_path) }}" 
                       target="_blank" 
                       class="evidence-item d-block mb-2">
                        <i class="bi bi-file-pdf me-2"></i>{{ $doc->original_name }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
