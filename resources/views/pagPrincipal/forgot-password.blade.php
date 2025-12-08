@extends('layouts.admin')

@section('title', 'Recuperar Contraseña')

@push('styles')
<style>
    .forgot-password-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at top left, #4c1d95, #020617);
        padding: 20px;
    }

    .forgot-password-card {
        width: 100%;
        max-width: 500px;
        background: #0f172a;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.7);
        padding: 40px;
    }

    .forgot-password-icon {
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

    .forgot-password-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 12px;
        text-align: center;
        color: #e5e7eb;
    }

    .forgot-password-subtitle {
        font-size: 0.95rem;
        text-align: center;
        margin-bottom: 32px;
        color: #9ca3af;
        line-height: 1.5;
    }

    .forgot-password-label {
        font-weight: 600;
        margin-bottom: 8px;
        font-size: 0.9rem;
        color: #e5e7eb;
        display: block;
    }

    .forgot-password-input {
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

    .forgot-password-input::placeholder {
        color: #6b7280;
    }

    .forgot-password-input:focus {
        border-color: #667eea;
        background: rgba(15, 23, 42, 0.7);
        box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .forgot-password-btn {
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

    .forgot-password-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(102, 126, 234, 0.4);
    }

    .forgot-password-link {
        margin-top: 24px;
        font-size: 0.9rem;
        text-align: center;
        color: #93c5fd;
    }

    .forgot-password-link a {
        color: #93c5fd;
        text-decoration: none;
        font-weight: 600;
    }

    .forgot-password-link a:hover {
        text-decoration: underline;
    }

    .alert-success {
        background: rgba(16, 185, 129, 0.1);
        border: 1px solid #10b981;
        color: #10b981;
        padding: 14px 18px;
        border-radius: 8px;
        margin-bottom: 20px;
        font-size: 0.9rem;
        text-align: center;
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
</style>
@endpush

@section('content')
<div class="forgot-password-wrapper">
    <div class="forgot-password-card">
        
        <div class="forgot-password-icon">
            🔐
        </div>

        <h1 class="forgot-password-title">¿Olvidaste tu contraseña?</h1>
        <p class="forgot-password-subtitle">
            No te preocupes. Ingresa tu correo electrónico y te enviaremos un enlace para recuperar tu contraseña.
        </p>

        {{-- Mensaje de éxito --}}
        @if (session('status'))
            <div class="alert-success">
                {{ session('status') }}
            </div>
        @endif

        {{-- Errores --}}
        @if ($errors->any())
            <div class="alert-danger">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <label class="forgot-password-label" for="email">
                Correo electrónico
            </label>
            <input
                type="email"
                id="email"
                name="email"
                class="forgot-password-input"
                placeholder="tu@correo.com"
                value="{{ old('email') }}"
                required
                autofocus
            >

            <button type="submit" class="forgot-password-btn">
                Enviar enlace de recuperación
            </button>
        </form>

        <div class="forgot-password-link">
            <a href="{{ route('login') }}">← Volver al inicio de sesión</a>
        </div>

    </div>
</div>
@endsection
