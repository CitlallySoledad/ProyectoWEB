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

    @php
        // Normalizamos el array de miembros para tener siempre 4 posiciones
        $members = $team->members ?? [];
        for ($i = 0; $i < 4; $i++) {
            if (!isset($members[$i])) {
                $members[$i] = ['name' => '', 'role' => ''];
            }
        }
    @endphp

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

            {{-- Encabezado Integrantes / Rol --}}
            <div class="team-edit-members-header">
                <span>Integrantes</span>
                <span>ROL</span>
            </div>

            {{-- Integrante 1 (Líder) --}}
            <div class="team-edit-member-row">
                <div>
                    <input
                        type="text"
                        name="members[0][name]"
                        class="team-edit-input"
                        placeholder="Nombre del líder"
                        value="{{ old('members.0.name', $members[0]['name'] ?? '') }}"
                    >
                </div>
                <div>
                    <div class="team-edit-role-leader">Líder</div>
                    {{-- rol fijo para el líder --}}
                    <input type="hidden" name="members[0][role]" value="lider">
                </div>
            </div>

            {{-- Integrante 2 --}}
            <div class="team-edit-member-row">
                <div>
                    <input
                        type="text"
                        name="members[1][name]"
                        class="team-edit-input"
                        placeholder="Integrante 2 (opcional)"
                        value="{{ old('members.1.name', $members[1]['name'] ?? '') }}"
                    >
                </div>
                <div>
                    @php $role1 = old('members.1.role', $members[1]['role'] ?? ''); @endphp
                    <select name="members[1][role]" class="team-edit-select">
                        <option value="" {{ $role1=='' ? 'selected' : '' }}>Sin asignar</option>
                        <option value="participante" {{ $role1=='participante' ? 'selected' : '' }}>Participante</option>
                        <option value="mentor"       {{ $role1=='mentor' ? 'selected' : '' }}>Mentor</option>
                    </select>
                </div>
            </div>

            {{-- Integrante 3 --}}
            <div class="team-edit-member-row">
                <div>
                    <input
                        type="text"
                        name="members[2][name]"
                        class="team-edit-input"
                        placeholder="Integrante 3 (opcional)"
                        value="{{ old('members.2.name', $members[2]['name'] ?? '') }}"
                    >
                </div>
                <div>
                    @php $role2 = old('members.2.role', $members[2]['role'] ?? ''); @endphp
                    <select name="members[2][role]" class="team-edit-select">
                        <option value="" {{ $role2=='' ? 'selected' : '' }}>Sin asignar</option>
                        <option value="participante" {{ $role2=='participante' ? 'selected' : '' }}>Participante</option>
                        <option value="mentor"       {{ $role2=='mentor' ? 'selected' : '' }}>Mentor</option>
                    </select>
                </div>
            </div>

            {{-- Integrante 4 --}}
            <div class="team-edit-member-row">
                <div>
                    <input
                        type="text"
                        name="members[3][name]"
                        class="team-edit-input"
                        placeholder="Integrante 4 (opcional)"
                        value="{{ old('members.3.name', $members[3]['name'] ?? '') }}"
                    >
                </div>
                <div>
                    @php $role3 = old('members.3.role', $members[3]['role'] ?? ''); @endphp
                    <select name="members[3][role]" class="team-edit-select">
                        <option value="" {{ $role3=='' ? 'selected' : '' }}>Sin asignar</option>
                        <option value="participante" {{ $role3=='participante' ? 'selected' : '' }}>Participante</option>
                        <option value="mentor"       {{ $role3=='mentor' ? 'selected' : '' }}>Mentor</option>
                    </select>
                </div>
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

