@extends('layouts.admin')

@section('title', 'Panel de evaluaciones')

@section('content')
<div class="admin-page-wrapper">
    <div class="admin-page-card">

        {{-- SIDEBAR IZQUIERDA --}}
        <div class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-back">
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
            <div class="admin-page-header">
                <h1 class="admin-page-title">Panel de evaluaciones</h1>

                <div class="admin-page-user">
                    <i class="bi bi-person-circle"></i>
                    <span>Admin</span>
                </div>
            </div>

            {{-- ENCABEZADO DE COLUMNAS --}}
            <div class="eval-header-row">
                <div class="eval-header-col" style="text-align:left;">Eventos Tec</div>
                <div class="eval-header-col">Creatividad</div>
                <div class="eval-header-col">Funcionalidad</div>
                <div class="eval-header-col">Innovación</div>
            </div>

            {{-- LISTA DE PROYECTOS --}}
            @foreach($projects as $project)
                @php
                    $c = $project['creativity'];
                    $f = $project['functionality'];
                    $i = $project['innovation'];
                @endphp

                <div class="eval-row">
                    {{-- Nombre proyecto --}}
                    <div class="eval-row-name">
                        <button class="btn btn-link p-0 text-white text-decoration-none"
                                onclick="window.location='{{ route('admin.evaluations.show', urlencode($project['name'])) }}'">
                            {{ $project['name'] }}
                        </button>
                    </div>

                    {{-- Creatividad (barra) --}}
                    <div class="eval-row-metric">
                        <div class="eval-progress-track">
                            <div class="eval-progress-fill" style="width: {{ $c * 10 }}%;"></div>
                        </div>
                    </div>

                    {{-- Funcionalidad (número) --}}
                    <div class="eval-row-metric">
                        <span class="eval-score-number">{{ $f }}</span>
                    </div>

                    {{-- Innovación (número) --}}
                    <div class="eval-row-metric">
                        <span class="eval-score-number">{{ $i }}</span>
                    </div>
                </div>
            @endforeach

        </div>

    </div>
</div>
@endsection
