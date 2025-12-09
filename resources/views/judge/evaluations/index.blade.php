@extends('layouts.judge-panel')

@section('title', 'Mis evaluaciones')

@push('styles')
<style>
    .eval-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s ease;
        border-left: 4px solid #2563eb;
    }
    .eval-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
        transform: translateY(-2px);
    }
    .eval-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 12px;
    }
    .eval-title {
        font-size: 18px;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 4px 0;
    }
    .eval-team {
        color: #64748b;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .eval-status {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 13px;
        font-weight: 600;
    }
    .eval-status.completada {
        background: #dcfce7;
        color: #166534;
    }
    .eval-status.pendiente {
        background: #fef3c7;
        color: #92400e;
    }
    .eval-body {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
    }
    .eval-score {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .score-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 18px;
    }
    .score-details {
        display: flex;
        flex-direction: column;
    }
    .score-label {
        font-size: 12px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .score-value {
        font-size: 24px;
        font-weight: 700;
        color: #2563eb;
    }
    .eval-actions {
        display: flex;
        gap: 8px;
    }
    .eval-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        border: 2px solid transparent;
    }
    .eval-btn-primary {
        background: #2563eb;
        color: white;
    }
    .eval-btn-primary:hover {
        background: #1d4ed8;
        color: white;
    }
    .eval-btn-danger {
        background: #dc2626;
        color: white;
    }
    .eval-btn-danger:hover {
        background: #b91c1c;
        color: white;
    }
    .eval-comments {
        margin-top: 12px;
        padding-top: 12px;
        border-top: 1px solid #f1f5f9;
        color: #64748b;
        font-size: 14px;
        line-height: 1.5;
    }
    .event-section {
        margin-bottom: 40px;
    }
    .event-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px 24px;
        border-radius: 12px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .event-title {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .event-badge {
        background: rgba(255,255,255,0.2);
        padding: 6px 16px;
        border-radius: 999px;
        font-size: 14px;
        font-weight: 600;
    }
    .empty-state {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }
    .empty-state-icon {
        font-size: 64px;
        margin-bottom: 16px;
        opacity: 0.5;
    }
    .empty-state-text {
        font-size: 18px;
        margin-bottom: 8px;
        color: #64748b;
    }
</style>
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">📋 Mis Evaluaciones</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-radius: 12px; border-left: 4px solid #22c55e;">
            <strong>✅ Éxito:</strong> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('info'))
        <div class="alert alert-info alert-dismissible fade show" role="alert" style="border-radius: 12px; border-left: 4px solid #3b82f6;">
            <strong>ℹ️ Info:</strong> {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @forelse($evaluationsByEvent as $eventId => $evaluations)
        @php
            $event = $events->get($eventId);
        @endphp
        
        <div class="event-section">
            <div class="event-header">
                <h2 class="event-title">
                    <i class="bi bi-calendar-event"></i>
                    <span>{{ $event ? $event->title : 'Sin evento asignado' }}</span>
                </h2>
                <span class="event-badge">
                    {{ $evaluations->count() }} {{ $evaluations->count() == 1 ? 'evaluación' : 'evaluaciones' }}
                </span>
            </div>

            @foreach($evaluations as $evaluation)
                <div class="eval-card">
                    <div class="eval-header">
                        <div>
                            <h3 class="eval-title">{{ $evaluation->project->name }}</h3>
                            <div class="eval-team">
                                <i class="bi bi-people"></i>
                                <span>{{ $evaluation->project->team?->name ?? 'Sin equipo' }}</span>
                            </div>
                        </div>
                        <span class="eval-status {{ $evaluation->status }}">
                            @if($evaluation->status === 'completada')
                                <i class="bi bi-check-circle-fill"></i>
                            @else
                                <i class="bi bi-clock"></i>
                            @endif
                            {{ ucfirst($evaluation->status) }}
                        </span>
                    </div>

                    <div class="eval-body">
                        <div class="eval-score">
                            <div class="score-circle">
                                ⭐
                            </div>
                            <div class="score-details">
                                <span class="score-label">Puntaje Final</span>
                                <span class="score-value">{{ number_format($evaluation->final_score ?? 0, 1) }}<span style="font-size: 16px; color: #94a3b8;">/10</span></span>
                            </div>
                        </div>

                        <div class="eval-actions">
                            <a href="{{ route('judge.evaluations.view', $evaluation) }}" 
                               class="eval-btn eval-btn-primary">
                                <i class="bi bi-eye"></i> Ver Detalles
                            </a>
                            <a href="{{ route('judge.evaluations.export-pdf', $evaluation) }}" 
                               class="eval-btn eval-btn-danger"
                               target="_blank">
                                <i class="bi bi-file-pdf"></i> Descargar PDF
                            </a>
                        </div>
                    </div>

                    @if($evaluation->general_comments)
                        <div class="eval-comments">
                            <strong style="color: #475569;">💬 Comentarios:</strong> 
                            {{ Str::limit($evaluation->general_comments, 150) }}
                        </div>
                    @endif

                    @if($evaluation->evaluated_at)
                        <div style="margin-top: 12px; font-size: 12px; color: #94a3b8;">
                            <i class="bi bi-clock-history"></i> 
                            Evaluado el {{ $evaluation->evaluated_at->format('d/m/Y H:i') }}
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @empty
        <div class="admin-card">
            <div class="empty-state">
                <div class="empty-state-icon">📭</div>
                <p class="empty-state-text">Aún no tienes evaluaciones completadas</p>
                <small style="color: #94a3b8;">Una vez completes la evaluación de un proyecto, aparecerá aquí.</small>
            </div>
        </div>
    @endforelse
@endsection
