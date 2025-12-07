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

            {{-- INTEGRANTES / ROLES --}}
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="fw-semibold small text-uppercase text-muted">Integrantes</span>
                <span class="fw-semibold small text-uppercase text-muted">Rol</span>
            </div>

            {{-- LÍDER --}}
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="flex-grow-1">
                    <input
                        type="text"
                        name="members[0][name]"
                        class="form-control rounded-pill"
                        placeholder="Nombre del líder"
                        value="{{ old('members.0.name') }}"
                    >
                </div>
                <div style="width: 150px;">
                    <span class="badge bg-danger w-100 py-2">Líder</span>
                    <input type="hidden" name="members[0][role]" value="lider">
                </div>
            </div>

            {{-- PENDIENTE 1 --}}
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="flex-grow-1">
                    <input
                        type="text"
                        name="members[1][name]"
                        class="form-control rounded-pill"
                        placeholder="Integrante 2 (opcional)"
                        value="{{ old('members.1.name') }}"
                    >
                </div>
                <div style="width: 150px;">
                    <select name="members[1][role]" class="form-select form-select-sm rounded-pill">
                        <option value="" {{ old('members.1.role')=='' ? 'selected' : '' }}>Sin asignar</option>
                        <option value="participante" {{ old('members.1.role')=='participante' ? 'selected' : '' }}>Participante</option>
                        <option value="mentor" {{ old('members.1.role')=='mentor' ? 'selected' : '' }}>Mentor</option>
                    </select>
                </div>
            </div>

            {{-- PENDIENTE 2 --}}
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="flex-grow-1">
                    <input
                        type="text"
                        name="members[2][name]"
                        class="form-control rounded-pill"
                        placeholder="Integrante 3 (opcional)"
                        value="{{ old('members.2.name') }}"
                    >
                </div>
                <div style="width: 150px;">
                    <select name="members[2][role]" class="form-select form-select-sm rounded-pill">
                        <option value="" {{ old('members.2.role')=='' ? 'selected' : '' }}>Sin asignar</option>
                        <option value="participante" {{ old('members.2.role')=='participante' ? 'selected' : '' }}>Participante</option>
                        <option value="mentor" {{ old('members.2.role')=='mentor' ? 'selected' : '' }}>Mentor</option>
                    </select>
                </div>
            </div>

            {{-- PENDIENTE 3 --}}
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="flex-grow-1">
                    <input
                        type="text"
                        name="members[3][name]"
                        class="form-control rounded-pill"
                        placeholder="Integrante 4 (opcional)"
                        value="{{ old('members.3.name') }}"
                    >
                </div>
                <div style="width: 150px;">
                    <select name="members[3][role]" class="form-select form-select-sm rounded-pill">
                        <option value="" {{ old('members.3.role')=='' ? 'selected' : '' }}>Sin asignar</option>
                        <option value="participante" {{ old('members.3.role')=='participante' ? 'selected' : '' }}>Participante</option>
                        <option value="mentor" {{ old('members.3.role')=='mentor' ? 'selected' : '' }}>Mentor</option>
                    </select>
                </div>
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

