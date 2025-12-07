@extends('layouts.admin-panel')

@section('title', 'Panel de evaluaciones')

@section('content')

    <h1 class="h4 mb-3">Panel de evaluaciones</h1>

    <div class="admin-card">
        <div class="admin-card-title">Proyectos y puntuaciones</div>

        @if (empty($projects) || count($projects) === 0)
            <p class="mb-0">No hay proyectos para mostrar.</p>
        @else
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Eventos Tec</th>
                            <th style="width: 200px;">Creatividad</th>
                            <th>Funcionalidad</th>
                            <th>Innovación</th>
                            <th style="width: 150px;">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($projects as $project)
                            @php
                                $c = $project['creativity'];
                                $f = $project['functionality'];
                                $i = $project['innovation'];
                            @endphp
                            <tr>
                                {{-- Nombre proyecto: abre el formulario de evaluación --}}
                                <td>
                                    <button
                                        type="button"
                                        class="btn btn-link p-0 text-white text-decoration-none"
                                        onclick="window.location='{{ route('admin.evaluations.show', urlencode($project['name'])) }}'">
                                        {{ $project['name'] }}
                                    </button>
                                </td>

                                {{-- Creatividad (barra de progreso) --}}
                                <td>
                                    <div class="progress" style="height: 8px; border-radius: 999px; background-color: rgba(15,23,42,0.5);">
                                        <div class="progress-bar"
                                             role="progressbar"
                                             style="width: {{ $c * 10 }}%;"
                                             aria-valuenow="{{ $c * 10 }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100">
                                        </div>
                                    </div>
                                </td>

                                {{-- Funcionalidad --}}
                                <td class="text-center">
                                    {{ $f }}
                                </td>

                                {{-- Innovación --}}
                                <td class="text-center">
                                    {{ $i }}
                                </td>

                                {{-- Botón Juzgar (si tiene id) --}}
                                <td class="text-center">
                                    @if (isset($project['id']))
                                        <button type="button"
                                                class="btn btn-sm btn-light rounded-pill"
                                                onclick="window.location='{{ route('admin.evaluations.judgement', ['evaluation' => $project['id']]) }}'">
                                            Juzgar
                                        </button>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

@endsection

