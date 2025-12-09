@extends('layouts.admin')

@section('title', 'Equipos Inscritos - ' . $event->title)

@push('styles')
<style>
.event-teams-container {
    width: 100%;
    min-height: 100vh;
    background: radial-gradient(circle at top left, #1d4ed8, #0f172a);
    padding: 40px 20px;
}

.event-teams-card {
    max-width: 1200px;
    margin: 0 auto;
    background: rgba(15, 23, 42, 0.85);
    border-radius: 24px;
    padding: 32px;
    box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
}

.event-teams-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 32px;
    padding-bottom: 20px;
    border-bottom: 2px solid rgba(148, 163, 184, 0.2);
}

.event-teams-title {
    font-size: 2rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
}

.event-teams-subtitle {
    font-size: 1rem;
    color: #94a3b8;
    margin-top: 8px;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 24px;
    background: rgba(148, 163, 184, 0.2);
    color: #e5e7eb;
    text-decoration: none;
    border-radius: 999px;
    font-size: 0.9rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-back:hover {
    background: rgba(148, 163, 184, 0.3);
    color: #ffffff;
    transform: translateX(-4px);
}

.teams-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
    gap: 24px;
    margin-top: 24px;
}

.team-card {
    background: linear-gradient(135deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.9));
    border: 1px solid rgba(148, 163, 184, 0.2);
    border-radius: 18px;
    padding: 24px;
    transition: all 0.3s ease;
}

.team-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(59, 130, 246, 0.3);
    border-color: rgba(59, 130, 246, 0.5);
}

.team-card-header {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

.team-icon {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #3b82f6, #2563eb);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #ffffff;
}

.team-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
}

.team-leader-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 12px;
    background: rgba(234, 179, 8, 0.2);
    color: #fbbf24;
    border-radius: 999px;
    font-size: 0.75rem;
    font-weight: 600;
    margin-top: 4px;
}

.team-members-section {
    margin-top: 16px;
}

.team-members-title {
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #94a3b8;
    margin-bottom: 12px;
}

.team-member {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px;
    background: rgba(15, 23, 42, 0.5);
    border-radius: 12px;
    margin-bottom: 8px;
}

.member-avatar {
    width: 36px;
    height: 36px;
    background: linear-gradient(135deg, #8b5cf6, #6366f1);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-weight: 700;
    font-size: 0.9rem;
}

.member-info {
    flex: 1;
}

.member-name {
    font-size: 0.95rem;
    font-weight: 600;
    color: #e5e7eb;
    margin: 0;
}

.member-role {
    font-size: 0.8rem;
    color: #94a3b8;
    margin: 0;
}

.no-teams-message {
    text-align: center;
    padding: 60px 20px;
    color: #94a3b8;
}

.no-teams-icon {
    font-size: 4rem;
    color: #475569;
    margin-bottom: 16px;
}

.event-info-box {
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.3);
    border-radius: 16px;
    padding: 20px;
    margin-bottom: 32px;
}

.event-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 20px;
}

.event-info-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.event-info-label {
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: #94a3b8;
}

.event-info-value {
    font-size: 1.1rem;
    font-weight: 600;
    color: #ffffff;
}
</style>
@endpush

@section('content')
<div class="event-teams-container">
    <div class="event-teams-card">
        <div class="event-teams-header">
            <div>
                <h1 class="event-teams-title">{{ $event->title }}</h1>
                <p class="event-teams-subtitle">Equipos Inscritos en el Evento</p>
            </div>
            <a href="{{ route('panel.lista-eventos') }}" class="btn-back">
                <i class="bi bi-arrow-left"></i>
                Volver a eventos
            </a>
        </div>

        {{-- Información del evento --}}
        <div class="event-info-box">
            <div class="event-info-grid">
                <div class="event-info-item">
                    <span class="event-info-label">Fecha Inicio</span>
                    <span class="event-info-value">{{ $event->start_date ? $event->start_date->format('d/m/Y') : '—' }}</span>
                </div>
                <div class="event-info-item">
                    <span class="event-info-label">Fecha Fin</span>
                    <span class="event-info-value">{{ $event->end_date ? $event->end_date->format('d/m/Y') : '—' }}</span>
                </div>
                <div class="event-info-item">
                    <span class="event-info-label">Lugar</span>
                    <span class="event-info-value">{{ $event->place ?? 'No especificado' }}</span>
                </div>
                <div class="event-info-item">
                    <span class="event-info-label">Equipos Inscritos</span>
                    <span class="event-info-value">{{ $teams->count() }} / {{ $event->capacity ?? '∞' }}</span>
                </div>
            </div>
        </div>

        {{-- Lista de equipos --}}
        @if($teams->count() > 0)
            <div class="teams-grid">
                @foreach($teams as $team)
                    <div class="team-card">
                        <div class="team-card-header">
                            <div class="team-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <div style="flex: 1;">
                                <h3 class="team-name">{{ $team->name }}</h3>
                                @if($team->leader)
                                    <div class="team-leader-badge">
                                        <i class="bi bi-star-fill"></i>
                                        Líder: {{ $team->leader->name }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Miembros del equipo --}}
                        @if($team->members && $team->members->count() > 0)
                            <div class="team-members-section">
                                <div class="team-members-title">Miembros ({{ $team->members->count() }})</div>
                                @foreach($team->members as $member)
                                    <div class="team-member">
                                        <div class="member-avatar">
                                            {{ strtoupper(substr($member->name, 0, 1)) }}
                                        </div>
                                        <div class="member-info">
                                            <p class="member-name">{{ $member->name }}</p>
                                            @if($member->pivot && $member->pivot->role)
                                                <p class="member-role">{{ $member->pivot->role }}</p>
                                            @else
                                                <p class="member-role">Miembro</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-teams-message">
                <div class="no-teams-icon">
                    <i class="bi bi-inbox"></i>
                </div>
                <h3>No hay equipos inscritos aún</h3>
                <p>Aún no se han inscrito equipos en este evento.</p>
            </div>
        @endif
    </div>
</div>
@endsection
