@extends('layouts.admin-panel')

@section('title', 'Dashboard admin')

@push('styles')
<style>
    .activity-list {
        border: 1px solid rgba(255,255,255,0.08);
        border-radius: 14px;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(37,99,235,0.08), rgba(124,58,237,0.06));
    }
    .activity-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        border-bottom: 1px solid rgba(255,255,255,0.04);
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        display: grid;
        place-items: center;
        background: linear-gradient(135deg, #2563eb, #7c3aed);
        color: #fff;
        font-size: 18px;
    }
    .activity-body {
        flex: 1;
    }
    .activity-title {
        margin: 0;
        font-weight: 600;
        color: #e5e7eb;
    }
    .activity-sub {
        margin: 2px 0 0;
        font-size: 13px;
        color: #cbd5e1;
    }
    .activity-date {
        font-size: 12px;
        color: #cbd5e1;
        background: rgba(255,255,255,0.06);
        padding: 6px 10px;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.08);
    }
</style>
@endpush

@section('content')

    <div class="admin-card mb-3">
        <div class="admin-card-title">Resumen general</div>
        <div class="row g-3">
            <div class="col-md-4">
                <div class="admin-card">
                    <div class="small text-uppercase text-muted mb-1">Eventos activos</div>
                    <div class="fs-4 fw-semibold">{{ $eventsCount ?? 0 }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="admin-card">
                    <div class="small text-uppercase text-muted mb-1">Equipos registrados</div>
                    <div class="fs-4 fw-semibold">{{ $teamsCount ?? 0 }}</div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="admin-card">
                    <div class="small text-uppercase text-muted mb-1">Usuarios</div>
                    <div class="fs-4 fw-semibold">{{ $usersCount ?? 0 }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="admin-card">
        <div class="admin-card-title">Actividad reciente</div>
        @if(($recentTeams ?? collect())->isEmpty())
            <p class="mb-0 text-sm">Aún no hay equipos creados.</p>
        @else
            <div class="activity-list">
                @foreach($recentTeams as $team)
                    <div class="activity-item">
                        <div class="activity-icon">
                            <i class="bi bi-people-fill"></i>
                        </div>
                        <div class="activity-body">
                            <p class="activity-title">{{ $team->name }}</p>
                            <p class="activity-sub">Líder: {{ $team->leader->name ?? 'Sin líder' }}</p>
                        </div>
                        <span class="activity-date">
                            {{ optional($team->created_at)->format('d/m/Y H:i') }}
                        </span>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

@endsection

