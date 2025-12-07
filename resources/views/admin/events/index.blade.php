@extends('layouts.admin-panel')

@section('title', 'Eventos')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Eventos</h1>
        <a href="{{ route('admin.events.create') }}" class="admin-btn-primary text-decoration-none">
            <i class="bi bi-plus-circle me-1"></i> Crear evento
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-title">Lista de eventos</div>

        @if($events->isEmpty())
            <p class="mb-0">No hay eventos registrados.</p>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Capacidad</th>
                        <th>Fecha inicio</th>
                        <th>Fecha fin</th>
                        <th style="width: 180px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $event)
                        <tr>
                            <td>{{ $event->title }}</td>
                            <td>{{ $event->capacity }}</td>
                            <td>{{ $event->start_date }}</td>
                            <td>{{ $event->end_date }}</td>
                            <td>
                                <a href="{{ route('admin.events.edit', $event) }}"
                                   class="btn btn-sm btn-light rounded-pill me-1">
                                    Editar
                                </a>

                                <form action="{{ route('admin.events.destroy', $event) }}"
                                      method="POST"
                                      class="d-inline"
                                      onsubmit="return confirm('¿Eliminar este evento?')">
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


