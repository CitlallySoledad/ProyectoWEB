@extends('layouts.admin')

@section('title', 'Inicio de sesión - Admin')

@section('content')
<div class="login-wrapper">
    <div class="card login-card-custom">
        <div class="row g-0">
            {{-- LADO IZQUIERDO: ILUSTRACIÓN --}}
            <div class="col-md-6 login-left">
                {{-- Pon una imagen tuya en public/images/login-illustration.png --}}
                <img src="{{ asset('imagenes/img1.jpg') }}" alt="Ilustración">
            </div>

            {{-- LADO DERECHO: FORMULARIO --}}
            <div class="col-md-6 login-right">
                <div class="text-center mb-4">
                    <i class="bi bi-person-circle" style="font-size: 3rem; color: #0d6efd;"></i>
                </div>

                <h1 class="login-title text-center">Iniciar sesión</h1>
                <p class="login-subtitle text-center">
                    Inicio sesión de usuario administrador
                </p>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.post') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Correo electrónico</label>
                        <input
                            type="email"
                            class="form-control login-input @error('email') is-invalid @enderror"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autofocus
                            placeholder="admin@admin.com">
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input
                            type="password"
                            class="form-control login-input @error('password') is-invalid @enderror"
                            id="password"
                            name="password"
                            required
                            placeholder="••••••••">
                    </div>

                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Recordarme</label>
                    </div>

                    <button type="submit" class="btn btn-primary login-btn">
                        Iniciar sesión
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
