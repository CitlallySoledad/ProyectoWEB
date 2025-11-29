@extends('layouts.admin')

@section('title', 'Inicio de sesión')

@push('styles')
<style>
    .user-login-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at top left, #4c1d95, #020617);
        padding: 20px;
    }

    .user-login-card {
        width: 100%;
        max-width: 960px;
        background: linear-gradient(90deg, #4c1d95 0%, #4c1d95 45%, #0f172a 45%, #0f172a 100%);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.7);
        display: grid;
        grid-template-columns: 1.1fr 1.2fr;
    }

    .user-login-left {
        padding: 24px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        color: #e5e7eb;
    }

    .user-login-illustration {
        flex: 1;
        border-radius: 16px;
        background-size: cover;
        background-position: center;
        background-color: #312e81;
        margin-bottom: 16px;
    }

    .user-login-illustration.bottom {
        margin-bottom: 0;
    }

    .user-login-right {
        padding: 32px 40px;
        color: #e5e7eb;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .user-login-title {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 8px;
        text-align: center;
    }

    .user-login-subtitle {
        font-size: 0.9rem;
        text-align: center;
        margin-bottom: 24px;
    }

    .user-login-label {
        font-weight: 600;
        margin-bottom: 4px;
        font-size: 0.88rem;
    }

    .user-login-input {
        border-radius: 999px;
        border: none;
        outline: none;
        width: 100%;
        padding: 10px 16px;
        margin-bottom: 12px;
        background: #e5edff;
        color: #111827;
    }

    .user-login-input::placeholder {
        color: #6b7280;
    }

    .user-login-btn {
        width: 100%;
        border-radius: 999px;
        border: none;
        padding: 10px 20px;
        background: #2563eb;
        color: #fff;
        font-weight: 600;
        margin-top: 10px;
    }

    .user-login-link {
        margin-top: 10px;
        font-size: 0.85rem;
        text-align: center;
        color: #93c5fd;
        cursor: pointer;
    }

    .user-login-link a {
        color: #93c5fd;
        text-decoration: none;
    }

    .user-login-link a:hover {
        text-decoration: underline;
    }

    .user-login-admin-link {
        margin-top: 12px;
        font-size: 0.86rem;
        text-align: center;
    }

    .user-login-admin-link button {
        border: none;
        background: transparent;
        color: #60a5fa;
        text-decoration: underline;
        cursor: pointer;
    }

    @media (max-width: 900px) {
        .user-login-card {
            grid-template-columns: 1fr;
            background: #0f172a;
        }

        .user-login-left {
            display: none;
        }
    }
</style>
@endpush

@section('content')
<div class="user-login-wrapper">
    <div class="user-login-card">

        {{-- LADO IZQUIERDO: ILUSTRACIONES --}}
        <div class="user-login-left">
            <div class="user-login-illustration"
                 style="background-image:url('{{ asset('imagenes/user-login-top.png') }}')"></div>
            <div class="user-login-illustration bottom"
                 style="background-image:url('{{ asset('imagenes/user-login-bottom.png') }}')"></div>
        </div>

        {{-- LADO DERECHO: FORMULARIO --}}
        <div class="user-login-right">
            <div class="user-login-title">Iniciar sesión</div>
            <div class="user-login-subtitle">
                Inicio de sesión para participantes / usuarios
            </div>

            {{-- SOLO DISEÑO: esta acción la puedes cambiar a la ruta real cuando tengas el login de usuarios --}}
            <form method="POST" action="#">
                @csrf

                <div class="mb-2">
                    <label class="user-login-label" for="control">Número de control</label>
                    <input type="text" id="control" name="control"
                           class="user-login-input" placeholder="Número de control">
                </div>

                <div class="mb-2">
                    <label class="user-login-label" for="password">Contraseña</label>
                    <input type="password" id="password" name="password"
                           class="user-login-input" placeholder="Contraseña">
                </div>

                <button type="submit" class="user-login-btn">
                    Iniciar sesión
                </button>
            </form>

            <div class="user-login-link">
                ¿Olvidaste tu contraseña?
            </div>

            <div class="user-login-link">
                ¿Aún no tienes cuenta?
                <a href="{{ route('public.register') }}">Regístrate</a>
            </div>

            {{-- 🔹 BOTÓN PARA IR AL LOGIN DE ADMINISTRADOR --}}
            <div class="user-login-admin-link">
                ¿Eres administrador?
                <button type="button"
                        onclick="window.location='{{ route('admin.login') }}'">
                    Iniciar sesión como administrador
                </button>
            </div>
        </div>

    </div>
</div>
@endsection

