@extends('layouts.admin-panel')

@section('title', 'Equipos')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Equipos</h1>
        <a href="{{ route('admin.teams.create') }}" class="admin-btn-primary text-decoration-none">
            <i class="bi bi-plus-circle me-1"></i> Crear equipo
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-title">Lista de equipos</div>

        @if($teams->isEmpty())
            <p class="mb-0">No hay equipos registrados.</p>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Integrantes</th>
                        <th style="width: 180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($teams as $team)
                        <tr>
                            <td>{{ $team->name }}</td>

                            {{-- 🔹 Mostrar integrantes reales --}}
                            <td>
                                @php
                                    $members = $team->members ?? [];

                                    $printMembers = [];

                                    if (is_array($members)) {
                                        foreach ($members as $m) {
                                            if (!empty($m['name'])) {
                                                $label = $m['name'];

                                                // si tiene rol y no es "Sin asignar"
                                                if (!empty($m['role']) && $m['role'] !== 'Sin asignar') {
                                                    $label .= " ({$m['role']})";
                                                }

                                                $printMembers[] = $label;
                                            }
                                        }
                                    }
                                @endphp

                                {{ count($printMembers) ? implode(', ', $printMembers) : '-' }}
                            </td>

                            <td>
                                <a href="{{ route('admin.teams.edit', $team) }}"
                                   class="btn btn-sm btn-light rounded-pill me-1">
                                    Editar
                                </a>

                                <form action="{{ route('admin.teams.destroy', $team) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este equipo?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="btn btn-sm btn-danger rounded-pill">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection



