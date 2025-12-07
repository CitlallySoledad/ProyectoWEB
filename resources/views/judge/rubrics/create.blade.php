@extends('layouts.judge-panel')

@section('title', 'Crear rúbrica')

@section('content')
    <h1 class="h3 mb-3">Crear nueva rúbrica</h1>

    <form action="{{ route('judge.rubrics.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Evento (opcional)</label>
            <select name="event_id" class="form-control">
                <option value="">-- Ninguno --</option>
                @foreach($events as $ev)
                    <option value="{{ $ev->id }}">{{ $ev->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="status" class="form-control" required>
                <option value="activa">Activa</option>
                <option value="inactiva">Inactiva</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('judge.rubrics.index') }}" class="admin-btn-secondary">Cancelar</a>
            <div>
                <button class="btn btn-outline-secondary me-2" type="submit" name="action" value="create">Crear</button>
                <button class="admin-btn-primary" type="submit" name="action" value="apply">Crear y aplicar</button>
            </div>
        </div>
    </form>
@endsection
