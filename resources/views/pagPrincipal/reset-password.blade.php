@extends('layouts.admin')

@section('title', 'Restablecer Contraseña')

@push('styles')
<style>
    .reset-password-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at top left, #4c1d95, #020617);
        padding: 20px;
    }

    .reset-password-card {
        width: 100%;
        max-width: 500px;
        background: #0f172a;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.7);
        padding: 40px;
    }

    .reset-password-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 24px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
    }

    .reset-password-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 12px;
        text-align: center;
        color: #e5e7eb;
    }

    .reset-password-subtitle {
        font-size: 0.95rem;
        text-align: center;
        margin-bottom: 32px;
        color: #9ca3af;
        line-height: 1.5;
    }

    .reset-password-label {
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 0.9rem;
        color: #e5e7eb;
        display: block;
    }

    .reset-password-input {
        border-radius: 8px;
        border: 1px solid #475569;
        outline: none;
        width: 100%;
        padding: 12px 16px;
        margin-bottom: 20px;
        background: rgba(15, 23, 42, 0.5);
        color: #e5e7eb;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }

    .reset-password-input::placeholder {
        color: #6b7280;
    }

    .reset-password-input:focus {
        border-color: #667eea;
        background: rgba(15, 23, 42, 0.7);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .reset-password-btn {
        width: 100%;
        border-radius: 8px;
        border: none;
        padding: 14px 20px;
        background: linear-gradient(135deg, #667eea, #764ba2);
        color: #fff;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
    }

    .reset-password-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
    }

    .alert-danger {
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid #ef4444;
        color: #fca5a5;
        padding: 14px 18px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 0.9rem;
    }

    .password-requirements {
        background: rgba(59, 130, 246, 0.1);
        border: 1px solid #3b82f6;
        border-radius: 8px;
        padding: 14px;
        margin-bottom: 20px;
    }

    .password-requirements p {
        margin: 0 0 8px 0;
        color: #93c5fd;
        font-size: 0.85rem;
        font-weight: 600;
    }

    .password-requirements ul {
        margin: 0;
        padding-left: 20px;
        color: #bfdbfe;
        font-size: 0.85rem;
    }

    .password-requirements li {
        margin-bottom: 4px;
    }
</style>
@endpush

@section('content')
<div class="reset-password-wrapper">
    <div class="reset-password-card">
        
        <div class="reset-password-icon">
            🔑
        </div>

        <h1 class="reset-password-title">Restablecer Contraseña</h1>
        <p class="reset-password-subtitle">
            Ingresa tu nueva contraseña. Asegúrate de que sea segura y fácil de recordar.
        </p>

        {{-- Errores --}}
        @if ($errors->any())
            <div class="alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="password-requirements">
            <p>📋 Requisitos de la contraseña:</p>
            <ul>
                <li>Mínimo 8 caracteres</li>
                <li>Las contraseñas deben coincidir</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ request('email') }}">

            <label class="reset-password-label" for="email">
                Correo electrónico
            </label>
            <input
                type="email"
                id="email"
                name="email"
                class="reset-password-input"
                value="{{ request('email') }}"
                readonly
                style="background: rgba(15, 23, 42, 0.3); cursor: not-allowed;"
            >

            <label class="reset-password-label" for="password">
                Nueva Contraseña
            </label>
            <input
                type="password"
                id="password"
                name="password"
                class="reset-password-input"
                placeholder="Ingresa tu nueva contraseña"
                required
                autofocus
            >

            <label class="reset-password-label" for="password_confirmation">
                Confirmar Contraseña
            </label>
            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                class="reset-password-input"
                placeholder="Confirma tu nueva contraseña"
                required
            >

            <button type="submit" class="reset-password-btn">
                Restablecer Contraseña
            </button>
        </form>

    </div>
</div>
@endsection
