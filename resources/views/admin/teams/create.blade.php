@extends('layouts.admin-panel')

@section('title', 'Crear equipo')

@section('content')

    <h1 class="h4 mb-3">Crear equipo</h1>

    {{-- ERRORES DE VALIDACIÓN --}}
    @if ($errors->any())
        <div class="alert alert-danger rounded-3 py-2">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="admin-card">
        <form action="{{ route('admin.teams.store') }}" method="POST">
            @csrf

            {{-- NOMBRE DEL EQUIPO --}}
            <div class="mb-3">
                <label class="form-label" for="name">Nombre del equipo</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control rounded-pill"
                    placeholder="Nombre del equipo"
                    value="{{ old('name') }}"
                    required
                >
            </div>

            {{-- INTEGRANTES / ROLES (usuarios existentes) --}}
            <div class="mb-3">
                <label class="form-label fw-semibold text-uppercase text-muted">Líder</label>
                <select name="leader_id" class="form-select rounded-pill" required>
                    <option value="">Selecciona líder</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('leader_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-uppercase text-muted">Backend</label>
                <select name="backend_id" class="form-select rounded-pill">
                    <option value="">Selecciona integrante backend (opcional)</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('backend_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-uppercase text-muted">Front-end</label>
                <select name="frontend_id" class="form-select rounded-pill">
                    <option value="">Selecciona integrante front-end (opcional)</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('frontend_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold text-uppercase text-muted">Diseñador</label>
                <select name="designer_id" class="form-select rounded-pill">
                    <option value="">Selecciona diseñador (opcional)</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('designer_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- BOTONES --}}
            <div class="mt-3 d-flex justify-content-between">
                <a href="{{ route('admin.teams.index') }}"
                   class="admin-btn-secondary text-decoration-none">
                    Cancelar
                </a>

                <button type="submit" class="admin-btn-primary">
                    Crear equipo
                </button>
            </div>
        </form>
    </div>

@endsection
