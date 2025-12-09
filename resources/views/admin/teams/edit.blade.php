@extends('layouts.admin-panel')

@section('title', 'Editar equipo')

@push('styles')
<style>
    /* Contenedor general en la parte derecha del panel */
    .team-edit-wrapper {
        padding: 40px 60px;
        color: #ffffff;
    }

    .team-edit-title {
        font-size: 2.4rem;
        font-weight: 600;
        margin-bottom: 24px;
    }

    /* Tarjeta azul oscuro del formulario (igual que crear equipo) */
    .team-edit-card {
        background: #053555;
        border-radius: 26px;
        padding: 26px 32px 24px;
        box-shadow: 0 18px 40px rgba(0,0,0,0.45);
        max-width: 1200px;
        margin: 0 auto;
    }

    .team-edit-label {
        font-size: 0.95rem;
        font-weight: 600;
        color: #e5e7eb;
        margin-bottom: 8px;
    }

    .team-edit-input {
        width: 100%;
        border-radius: 999px;
        border: none;
        outline: none;
        padding: 10px 18px;
        font-size: 0.95rem;
    }

    .team-edit-select {
        width: 100%;
        border-radius: 999px;
        border: none;
        outline: none;
        padding: 10px 18px;
        font-size: 0.9rem;
    }

    /* Encabezado “Integrantes / ROL” */
    .team-edit-members-header {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 160px;
        align-items: center;
        margin: 22px 0 8px;
        font-size: 0.9rem;
        font-weight: 600;
        color: #e5e7eb;
    }

    .team-edit-members-header span:last-child {
        text-align: right;
    }

    /* Fila integrante + rol */
    .team-edit-member-row {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 160px;
        column-gap: 14px;
        align-items: center;
        margin-bottom: 10px;
    }

    /* Pastilla roja de “Líder” */
    .team-edit-role-leader {
        border-radius: 999px;
        background: #dc2626;
        color: #fff;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 8px 0;
        text-align: center;
    }

    /* Pie de formulario */
    .team-edit-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 22px;
    }

    .team-edit-footer-left,
    .team-edit-footer-right {
        display: flex;
        gap: 10px;
    }

    .btn-team-pill {
        border-radius: 999px;
        padding: 8px 24px;
        border: none;
        font-size: 0.9rem;
        cursor: pointer;
    }

    .btn-team-secondary {
        background: rgba(148,163,184,0.4);
        color: #e5e7eb;
    }

    .btn-team-primary {
        background: #2563eb;
        color: #fff;
        font-weight: 600;
    }

    .btn-team-secondary:hover {
        background: rgba(148,163,184,0.6);
    }

    .btn-team-primary:hover {
        background: #1d4ed8;
    }

    @media (max-width: 960px) {
        .team-edit-wrapper {
            padding: 24px 18px;
        }
        .team-edit-card {
            padding: 20px 18px;
        }
        .team-edit-member-row,
        .team-edit-members-header {
            grid-template-columns: 1fr;
        }
        .team-edit-members-header span:last-child {
            text-align: left;
            margin-top: 4px;
        }
    }
</style>
@endpush

@section('content')
<div class="team-edit-wrapper">

    <h1 class="team-edit-title">Editar equipo</h1>

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

    <div class="team-edit-card">
        <form action="{{ route('admin.teams.update', $team->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Nombre del equipo --}}
            <div class="mb-3">
                <label class="team-edit-label" for="name">Nombre del equipo</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="team-edit-input"
                    placeholder="Nombre del equipo"
                    value="{{ old('name', $team->name) }}"
                    required
                >
            </div>

            {{-- Roles con usuarios existentes --}}
            <div class="mb-3">
                <label class="team-edit-label">Líder</label>
                <select name="leader_id" class="team-edit-select" required>
                    <option value="">Selecciona líder</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('leader_id', $team->leader_id) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="team-edit-label">Backend</label>
                <select name="backend_id" class="team-edit-select">
                    <option value="">Selecciona backend (opcional)</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('backend_id', $backendId ?? null) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="team-edit-label">Front-end</label>
                <select name="frontend_id" class="team-edit-select">
                    <option value="">Selecciona front-end (opcional)</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('frontend_id', $frontendId ?? null) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="team-edit-label">Diseñador</label>
                <select name="designer_id" class="team-edit-select">
                    <option value="">Selecciona diseñador (opcional)</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ old('designer_id', $designerId ?? null) == $user->id ? 'selected' : '' }}>
                            {{ $user->name }} ({{ $user->email }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Botones inferiores --}}
            <div class="team-edit-footer">
                <div class="team-edit-footer-left">
                    <a href="{{ route('admin.teams.index') }}" class="btn-team-pill btn-team-secondary">
                        Cancelar
                    </a>
                </div>

                <div class="team-edit-footer-right">
                    <button type="submit" class="btn-team-pill btn-team-primary">
                        Guardar cambios
                    </button>
                </div>
            </div>

        </form>
    </div>
</div>
@endsection

