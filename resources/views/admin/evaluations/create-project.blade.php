@extends('layouts.admin')

@section('title', 'Crear proyecto')

@push('styles')
<style>
    .create-form-card {
        max-width: 600px;
        width: 100%;
        background: rgba(15, 23, 42, 0.95);
        border-radius: 22px;
        padding: 22px 24px;
        box-shadow: 0 18px 35px rgba(0,0,0,0.55);
    }

    .create-form-card label {
        color: #e5e7eb;
        font-size: 0.9rem;
        margin-bottom: 4px;
    }

    .create-form-card input,
    .create-form-card select {
        background: #020617;
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, 0.7);
        color: #e5e7eb;
        padding: 8px 14px;
        width: 100%;
        font-size: 0.9rem;
        margin-bottom: 12px;
    }

    .create-form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 10px;
    }

    .btn-main {
        border-radius: 999px;
        border: none;
        padding: 8px 18px;
        background: #2563eb;
        color: #f9fafb;
        cursor: pointer;
    }

    .btn-secondary {
        border-radius: 999px;
        border: 1px solid rgba(148, 163, 184, 0.7);
        padding: 8px 18px;
        background: transparent;
        color: #e5e7eb;
        cursor: pointer;
    }
</style>
@endpush

@section('content')
<div class="admin-page-wrapper">
    <div class="admin-page-card">

        {{-- SIDEBAR --}}
        <div class="admin-sidebar">
            <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-back">
                <i class="bi bi-chevron-left"></i>
            </a>

            <a href="{{ route('admin.events.index') }}" class="admin-sidebar-icon">
                <i class="bi bi-calendar-event"></i>
            </a>
            <a href="{{ route('admin.evaluations.index') }}" class="admin-sidebar-icon">
                <i class="bi bi-people-fill"></i>
            </a>
            <a href="{{ route('admin.evaluations.projects_list') }}" class="admin-sidebar-icon active">
                <i class="bi bi-grid-1x2"></i>
            </a>
            <a href="{{ route('admin.users.index') }}" class="admin-sidebar-icon">
                <i class="bi bi-person-badge"></i>
            </a>
        </div>

        <div class="admin-page-main">
            <div class="admin-page-header">
                <h1 class="projects-page-title">Crear nuevo proyecto</h1>

                <div class="admin-page-user">
                    <i class="bi bi-person-circle"></i>
                    <span>Admin</span>
                </div>
            </div>

            <div class="create-form-card">
                <form action="{{ route('admin.projects.store') }}" method="POST">
                    @csrf

                    <div class="mb-2">
                        <label for="name">Nombre del proyecto</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    </div>

                    <div class="mb-2">
                        <label for="team">Equipo</label>
                        <input type="text" id="team" name="team" value="{{ old('team') }}">
                    </div>

                    <div class="mb-2">
                        <label for="status">Estado</label>
                        <select id="status" name="status" required>
                            <option value="Pendiente" {{ old('status') === 'Pendiente' ? 'selected' : '' }}>Pendiente</option>
                            <option value="Evaluado" {{ old('status') === 'Evaluado' ? 'selected' : '' }}>Evaluado</option>
                        </select>
                    </div>

                    <div class="create-form-actions">
                        <a href="{{ route('admin.evaluations.projects_list') }}" class="btn-secondary">Cancelar</a>
                        <button type="submit" class="btn-main">Guardar</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection
