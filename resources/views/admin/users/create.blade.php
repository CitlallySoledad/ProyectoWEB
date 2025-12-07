@extends('layouts.admin-panel')

@section('title', 'Crear usuario administrador')

@section('content')

    <h1 class="h4 mb-3">Crear usuario administrador</h1>

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
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            {{-- Forzar que este usuario sea administrador --}}
            <input type="hidden" name="is_admin" value="1">

            {{-- NOMBRE --}}
            <div class="mb-3">
                <label class="form-label" for="name">Nombre</label>
                <input
                    type="text"
                    id="name"
                    name="name"
                    class="form-control rounded-pill"
                    value="{{ old('name') }}"
                    placeholder="Nombre completo"
                    required
                >
                @error('name')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            {{-- CORREO --}}
            <div class="mb-3">
                <label class="form-label" for="email">Correo electrónico</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-control rounded-pill"
                    value="{{ old('email') }}"
                    placeholder="admin@ejemplo.com"
                    required
                >
                @error('email')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            {{-- CONTRASEÑA --}}
            <div class="mb-3">
                <label class="form-label" for="password">Contraseña</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control rounded-pill"
                    placeholder="••••••••"
                    required
                >
                @error('password')
                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                @enderror
            </div>

            {{-- CONFIRMAR CONTRASEÑA --}}
            <div class="mb-4">
                <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                <input
                    type="password"
                    id="password_confirmation"
                    name="password_confirmation"
                    class="form-control rounded-pill"
                    placeholder="Repite la contraseña"
                    required
                >
            </div>

            {{-- BOTONES --}}
            <div class="d-flex justify-content-between">
                <a href="{{ route('admin.users.index') }}"
                   class="admin-btn-secondary text-decoration-none">
                    Cancelar
                </a>

                <button type="submit" class="admin-btn-primary">
                    Guardar usuario
                </button>
            </div>
        </form>
    </div>

@endsection

