@extends('layouts.admin-panel')

@section('title', 'Resultados del Evento')

@push('styles')
<style>
    .results-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 32px;
        border-radius: 12px;
        margin-bottom: 32px;
    }
    .results-title {
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 8px 0;
    }
    .results-subtitle {
        font-size: 16px;
        opacity: 0.9;
        margin: 0;
    }
    .stats-card {
        background: white;
        border-radius: 12px;
        padding: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        text-align: center;
    }
    .stats-value {
        font-size: 36px;
        font-weight: 700;
        color: #2563eb;
        margin: 0;
    }
    .stats-label {
        font-size: 14px;
        color: #64748b;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-top: 8px;
    }
    .team-card {
        background: white;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border-left: 4px solid #2563eb;
    }
    .team-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 16px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f1f5f9;
    }
    .team-name {
        font-size: 22px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 8px 0;
    }
    .team-members {
        color: #64748b;
        font-size: 14px;
    }
    .project-section {
        background: #f8fafc;
        border-radius: 8px;
        padding: 16px;
        margin-top: 16px;
    }
    .project-title {
        font-size: 16px;
        font-weight: 600;
        color: #475569;
        margin: 0 0 12px 0;
    }
    .evaluation-item {
        background: white;
        border-radius: 8px;
        padding: 12px 16px;
        margin-bottom: 8px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    .judge-info {
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .judge-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 16px;
    }
    .judge-details {
        display: flex;
        flex-direction: column;
    }
    .judge-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 14px;
    }
    .rubric-name {
        font-size: 12px;
        color: #64748b;
    }
    .score-badge {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 16px;
        border-radius: 999px;
        font-weight: 700;
        font-size: 18px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    .no-evaluations {
        text-align: center;
        padding: 32px;
        color: #94a3b8;
        font-style: italic;
    }
    .no-project {
        text-align: center;
        padding: 24px;
        background: #fef3c7;
        border-radius: 8px;
        color: #92400e;
        margin-top: 16px;
    }
    .avg-score {
        background: #dcfce7;
        color: #166534;
        padding: 6px 12px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 16px;
    }
    .status-badge {
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }
    .status-completada {
        background: #dcfce7;
        color: #166534;
    }
    .status-pendiente {
        background: #fef3c7;
        color: #92400e;
    }
    .podium-card {
        background: white;
        border-radius: 16px;
        padding: 32px 24px;
        text-align: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        transition: all 0.3s ease;
        border: 3px solid transparent;
    }
    .podium-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }
    .podium-1 {
        border-color: #ffd700;
        background: linear-gradient(135deg, #fff9e6 0%, #ffffff 100%);
    }
    .podium-2 {
        border-color: #c0c0c0;
        background: linear-gradient(135deg, #f5f5f5 0%, #ffffff 100%);
    }
    .podium-3 {
        border-color: #cd7f32;
        background: linear-gradient(135deg, #fff4e6 0%, #ffffff 100%);
    }
    .podium-place {
        margin-bottom: 16px;
    }
    .place-icon {
        font-size: 64px;
        margin-bottom: 8px;
        line-height: 1;
    }
    .place-text {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .podium-team-name {
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
        margin: 16px 0;
        min-height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .podium-score {
        font-size: 28px;
        font-weight: 700;
        color: #2563eb;
        margin: 16px 0;
        padding: 12px;
        background: #eff6ff;
        border-radius: 12px;
    }
    .podium-members {
        font-size: 14px;
        color: #64748b;
        margin-top: 16px;
        padding-top: 16px;
        border-top: 2px solid #f1f5f9;
        line-height: 1.6;
    }
</style>
@endpush

@section('content')
<div class="results-header">
    <div class="d-flex justify-content-between align-items-start">
        <div>
            <h1 class="results-title">📊 {{ $event->title }}</h1>
            <p class="results-subtitle">Equipos Inscritos y Calificaciones</p>
        </div>
        <a href="{{ route('admin.events.index') }}" class="btn btn-light">
            <i class="bi bi-arrow-left me-2"></i>Volver a Eventos
        </a>
    </div>
</div>

<!-- Estadísticas -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="stats-card">
            <h2 class="stats-value">{{ $totalTeams }}</h2>
            <p class="stats-label">Equipos Inscritos</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <h2 class="stats-value">{{ $teamsWithProjects }}</h2>
            <p class="stats-label">Con Proyectos</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="stats-card">
            <h2 class="stats-value">{{ $totalEvaluations }}</h2>
            <p class="stats-label">Evaluaciones Totales</p>
        </div>
    </div>
</div>

<!-- Podio - Top 3 -->
@if($rankings->isNotEmpty())
<div class="mb-5">
    <h2 style="font-size: 24px; font-weight: 700; color: #1e293b; margin-bottom: 24px; text-align: center;">
        🏆 Podio de Ganadores
    </h2>
    <div class="row justify-content-center">
        @foreach($rankings as $ranking)
            <div class="col-md-4 mb-3">
                <div class="podium-card podium-{{ $ranking['place'] }}">
                    <div class="podium-place">
                        @if($ranking['place'] == 1)
                            <div class="place-icon">🥇</div>
                            <div class="place-text">1er Lugar</div>
                        @elseif($ranking['place'] == 2)
                            <div class="place-icon">🥈</div>
                            <div class="place-text">2do Lugar</div>
                        @else
                            <div class="place-icon">🥉</div>
                            <div class="place-text">3er Lugar</div>
                        @endif
                    </div>
                    <div class="podium-team-name">{{ $ranking['team']->name }}</div>
                    <div class="podium-score">
                        ⭐ {{ number_format($ranking['score'], 2) }}/10
                    </div>
                    @if($ranking['team']->members->isNotEmpty())
                        <div class="podium-members">
                            <i class="bi bi-people me-1"></i>
                            {{ $ranking['team']->members->pluck('name')->join(', ') }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
@endif

<!-- Equipos -->
@forelse($teams as $team)
    <div class="team-card">
        <div class="team-header">
            <div>
                <h3 class="team-name">
                    <i class="bi bi-people-fill me-2"></i>{{ $team->name }}
                </h3>
                @if($team->members->isNotEmpty())
                    <p class="team-members">
                        <i class="bi bi-person me-1"></i>
                        {{ $team->members->pluck('name')->join(', ') }}
                    </p>
                @endif
            </div>
            <div>
                @if($team->projects->isNotEmpty())
                    @php
                        $project = $team->projects->first();
                        $avgScore = $project->evaluations->isNotEmpty() 
                            ? $project->evaluations->avg('final_score') 
                            : null;
                    @endphp
                    @if($avgScore)
                        <div class="avg-score">
                            ⭐ Promedio: {{ number_format($avgScore, 2) }}/10
                        </div>
                    @endif
                @endif
            </div>
        </div>

        @if($team->projects->isNotEmpty())
            @foreach($team->projects as $project)
                <div class="project-section">
                    <h4 class="project-title">
                        <i class="bi bi-folder me-2"></i>Proyecto: {{ $project->name }}
                        @if($project->status)
                            <span class="badge bg-info ms-2">{{ ucfirst($project->status) }}</span>
                        @endif
                    </h4>

                    @if($project->evaluations->isNotEmpty())
                        @foreach($project->evaluations as $evaluation)
                            <div class="evaluation-item">
                                <div class="judge-info">
                                    <div class="judge-avatar">
                                        {{ strtoupper(substr($evaluation->judge?->name ?? 'J', 0, 1)) }}
                                    </div>
                                    <div class="judge-details">
                                        <span class="judge-name">{{ $evaluation->judge?->name ?? 'Juez no asignado' }}</span>
                                        <span class="rubric-name">
                                            <i class="bi bi-clipboard-check me-1"></i>
                                            {{ $evaluation->rubric?->name ?? 'Sin rúbrica' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="status-badge status-{{ $evaluation->status }}">
                                        {{ ucfirst($evaluation->status) }}
                                    </span>
                                    <div class="score-badge">
                                        ⭐ {{ number_format($evaluation->final_score ?? 0, 2) }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="no-evaluations">
                            <i class="bi bi-clock-history" style="font-size: 32px; display: block; margin-bottom: 8px;"></i>
                            Sin evaluaciones aún
                        </div>
                    @endif
                </div>
            @endforeach
        @else
            <div class="no-project">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Este equipo aún no ha enviado ningún proyecto
            </div>
        @endif
    </div>
@empty
    <div class="team-card">
        <div class="no-evaluations">
            <i class="bi bi-inbox" style="font-size: 48px; display: block; margin-bottom: 16px; opacity: 0.3;"></i>
            <p style="font-size: 18px; margin: 0;">No hay equipos inscritos en este evento</p>
        </div>
    </div>
@endforelse

@endsection
