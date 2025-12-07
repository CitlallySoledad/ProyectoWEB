@extends('layouts.judge-panel')

@section('title', 'Editar rúbrica')

@section('content')
    <h1 class="h3 mb-3">Editar rúbrica</h1>

    <form action="{{ route('judge.rubrics.update', $rubric) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $rubric->name) }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Evento (opcional)</label>
            <select name="event_id" class="form-control">
                <option value="">-- Ninguno --</option>
                @foreach($events as $ev)
                    <option value="{{ $ev->id }}" @if(old('event_id', $rubric->event_id) == $ev->id) selected @endif>{{ $ev->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Estado</label>
            <select name="status" class="form-control" required>
                <option value="activa" @if($rubric->status === 'activa') selected @endif>Activa</option>
                <option value="inactiva" @if($rubric->status === 'inactiva') selected @endif>Inactiva</option>
            </select>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('judge.rubrics.index', ['rubric' => $rubric->id]) }}" class="admin-btn-secondary">Cancelar</a>
            <button class="admin-btn-primary" type="submit" name="action" value="apply" @if($rubric->status === 'inactiva') disabled title="No se puede aplicar una rúbrica inactiva" @endif>Guardar y aplicar</button>
        </div>
    </form>
@endsection
