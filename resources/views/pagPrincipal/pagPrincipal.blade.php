@extends('layouts.admin')

@section('title', 'Inicio')

@push('styles')
<style>
    .landing-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: flex-start;
        justify-content: center;
        background: radial-gradient(circle at top, #123c6b, #020617);
        padding: 40px 20px;
    }

    .landing-card {
        width: 100%;
        max-width: 1200px;
        min-height: 720px;
        background: #0f172a;
        border-radius: 14px;
        box-shadow: 0 25px 55px rgba(0,0,0,0.6);
        overflow: hidden;
        color: #fff;
        font-size: 0.95rem;
    }

    /* -------- HEADER -------- */
    .landing-header {
        display: flex;
        align-items: center;
        padding: 14px 26px;
        background: #14557b;
    }

    .landing-header-left img,
    .landing-header-right img {
        height: 60px;
        width: auto;
        object-fit: contain;
    }

    .landing-header-center {
        flex: 1;
        text-align: center;
    }

    .landing-header-center h1 {
        font-size: 2.2rem;
        margin: 0;
    }

    .landing-header-center span {
        font-size: 1rem;
        display: block;
        margin-top: -6px;
    }

    /* -------- NAV -------- */
    .landing-nav {
        display: flex;
        background: #166534;
        padding: 10px 12px;
        gap: 6px;
    }

    .landing-nav button {
        border: none;
        padding: 8px 20px;
        border-radius: 4px;
        font-size: 0.9rem;
        cursor: pointer;
        background: #22c55e;
        color: #022c22;
        transition: 0.2s ease;
    }

    .landing-nav button.secondary {
        background: #15803d;
        color: #e5e7eb;
    }

    .landing-nav button:hover {
        background: #4ade80;
    }

    /* -------- CUERPO PRINCIPAL -------- */
    .landing-body {
        padding: 32px 38px 40px;
        display: flex;
        flex-direction: column;
        gap: 32px;
        min-height: 520px;
    }

    .landing-title {
        font-size: 1.7rem;
        font-weight: 700;
        margin-bottom: 4px;
    }

    /* -------- COLUMNAS -------- */
    .landing-columns {
        display: grid;
        grid-template-columns: 300px 1fr;
        gap: 40px;
        align-items: flex-start;
    }

    .landing-social {
        display: flex;
        flex-direction: column;
        gap: 10px;
        font-size: 1rem;
    }

    .landing-social-item {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .landing-social-item i {
        font-size: 1.3rem;
    }

    /* -------- GALERÍA -------- */
    .landing-gallery {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
        margin-top: 14px;
    }

    .landing-gallery div {
        height: 190px;
        border-radius: 6px;
        background-size: cover;
        background-position: center;
        background-color: #111827;
    }

    /* -------- BOTONES -------- */
    .landing-buttons {
        margin-top: 16px;
        display: flex;
        flex-direction: column;
        gap: 14px;
        align-items: center;
    }

    .landing-btn {
        width: 60%;
        max-width: 420px;
        border-radius: 999px;
        padding: 14px 22px;
        border: none;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 600;
        transition: .2s ease;
    }

    .landing-btn-primary {
        background: #2563eb;
        color: #fff;
    }

    .landing-btn-primary:hover {
        background: #1e3a8a;
    }

    .landing-btn-secondary {
        background: #1d4ed8;
        color: #e5e7eb;
    }

    .landing-btn-secondary:hover {
        background: #1e40af;
    }

    /* -------- RESPONSIVE -------- */
    @media (max-width: 768px) {
        .landing-card {
            max-width: 100%;
        }

        .landing-columns {
            grid-template-columns: 1fr;
        }

        .landing-header {
            flex-direction: column;
            gap: 10px;
        }

        .landing-gallery div {
            height: 140px;
        }

        .landing-btn {
            width: 80%;
        }
    }
</style>
@endpush

@section('content')
<div class="landing-wrapper">
    <div class="landing-card">

        {{-- HEADER --}}
        <div class="landing-header">
            <div class="landing-header-left">
                <img src="{{ asset('imagenes/logo-tec.png') }}" alt="TecNM">
            </div>

            <div class="landing-header-center">
                <h1>Educación</h1>
                <span>Secretaría de Educación Pública</span>
            </div>

            <div class="landing-header-right">
                <img src="{{ asset('imagenes/logo-ito.png') }}" alt="ITO">
            </div>
        </div>

        {{-- NAV --}}
        <div class="landing-nav">
            <button>Bienvenido</button>
            <button class="secondary">Eventos</button>
            <button class="secondary">Reglamento</button>
            <button class="secondary">Noticias</button>
            <button class="secondary">Contáctanos</button>
        </div>

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="landing-body">
            <div class="landing-title">
                Únete a la comunidad que transforma ideas en algoritmos
            </div>

            <div class="landing-columns">

                {{-- IZQUIERDA: REDES --}}
                <div class="landing-social">
                    <div class="landing-social-item">
                        <i class="bi bi-facebook"></i> Big Programing
                    </div>
                    <div class="landing-social-item">
                        <i class="bi bi-instagram"></i> Big Programing
                    </div>
                    <div class="landing-social-item">
                        <i class="bi bi-whatsapp"></i> 9518596325
                    </div>
                </div>

                {{-- DERECHA: TEXTO + GALERÍA --}}
                <div>
                    <p style="font-size: 1.05rem;">
                        Registra tu equipo, organiza tu código y demuestra tu talento
                        en los eventos de programación.
                    </p>

                    <div class="landing-gallery">
                        <div style="background-image:url('{{ asset('imagenes/principal1.jpg') }}')"></div>
                        <div style="background-image:url('{{ asset('imagenes/principal2.jpg') }}')"></div>
                        <div style="background-image:url('{{ asset('imagenes/principal3.jpg') }}')"></div>
                    </div>
                </div>
            </div>

            {{-- BOTONES --}}
            <div class="landing-buttons">
                <button class="landing-btn landing-btn-primary"
                        onclick="window.location='{{ route('public.login') }}'">
                    Iniciar Sesión
                </button>

                <button class="landing-btn landing-btn-secondary"
                        onclick="window.location='{{ route('public.register') }}'">
                    Registrarse
                </button>
            </div>
        </div>

    </div>
</div>
@endsection
