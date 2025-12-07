@extends('layouts.admin-panel')

@section('title', 'Dashboard admin')

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
        <p class="mb-0 text-sm">
            Aquí puedes mostrar últimos eventos creados, equipos nuevos, etc.
        </p>
    </div>

@endsection

