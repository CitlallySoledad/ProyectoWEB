@extends('layouts.admin')

@section('title', 'Crear usuario')

@section('content')
<div class="admin-page-wrapper">
    <div class="admin-page-card">

        {{-- SIDEBAR IZQUIERDA --}}
        <div class="admin-sidebar">
            <a href="{{ route('admin.users.index') }}" class="admin-sidebar-back">
                <i class="bi bi-chevron-left"></i>
            </a>

            <div class="admin-sidebar-icon">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="admin-sidebar-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="admin-sidebar-icon">
                <i class="bi bi-grid-1x2"></i>
            </div>
            <div class="admin-sidebar-icon active">
                <i class="bi bi-person-badge"></i>
            </div>
        </div>

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="admin-page-main">
            <div class="admin-page-inner">

                <h1 class="admin-form-title">Crear usuario administrador</h1>

                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf

                    <div class="admin-form-row">
                        <label class="admin-form-label" for="name">Nombre</label>
                        <input type="text"
                               id="name"
                               name="name"
                               class="admin-form-input"
                               value="{{ old('name') }}"
                               required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="admin-form-row">
                        <label class="admin-form-label" for="email">Correo</label>
                        <input type="email"
                               id="email"
                               name="email"
                               class="admin-form-input"
                               value="{{ old('email') }}"
                               required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="admin-form-row">
                        <label class="admin-form-label" for="password">Contraseña</label>
                        <input type="password"
                               id="password"
                               name="password"
                               class="admin-form-input"
                               required>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="admin-form-row">
                        <label class="admin-form-label" for="password_confirmation">Confirmar contraseña</label>
                        <input type="password"
                               id="password_confirmation"
                               name="password_confirmation"
                               class="admin-form-input"
                               required>
                    </div>

                    <div class="admin-form-footer">
                        <a href="{{ route('admin.users.index') }}"
                           class="btn admin-btn-pill admin-btn-secondary">
                            Cancelar
                        </a>

                        <button type="submit" class="admin-btn-pill admin-btn-primary">
                            Guardar usuario
                        </button>
                    </div>
                </form>

            </div>
        </div>

    </div>
</div>
@endsection
