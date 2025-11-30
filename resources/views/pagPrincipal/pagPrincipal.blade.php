@extends('layouts.admin')

@section('title', 'Inicio')

@push('styles')
<style>
    .landing-wrapper {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at top, #123c6b, #020617);
        padding: 20px;
    }

    .landing-card {
        width: 100%;
        max-width: 960px;
        background: #0f172a;
        border-radius: 10px;
        box-shadow: 0 20px 45px rgba(0,0,0,0.6);
        overflow: hidden;
        color: #fff;
        font-size: 0.92rem;
    }

    .landing-header {
        display: flex;
        align-items: center;
        padding: 10px 20px;
        background: #14557b;
    }

    .landing-header-left,
    .landing-header-right {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .landing-header-left img,
    .landing-header-right img {
        height: 52px;
        width: auto;
        object-fit: contain;
    }

    .landing-header-center {
        flex: 1;
        text-align: center;
    }

    .landing-header-center h1 {
        font-size: 2rem;
        margin: 0;
    }

    .landing-header-center span {
        font-size: 0.85rem;
        display: block;
        margin-top: -4px;
    }

    .landing-nav {
        display: flex;
        background: #166534;
        padding: 6px 8px;
        gap: 4px;
    }

    .landing-nav button {
        border: none;
        padding: 6px 16px;
        border-radius: 4px;
        font-size: 0.85rem;
        cursor: pointer;
        background: #22c55e;
        color: #022c22;
    }

    .landing-nav button.secondary {
        background: #15803d;
        color: #e5e7eb;
    }

    .landing-body {
        padding: 18px 24px 28px;
    }

    .landing-title {
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .landing-columns {
        display: grid;
        grid-template-columns: 220px 1fr;
        gap: 16px;
        margin-bottom: 18px;
    }

    .landing-social {
        display: flex;
        flex-direction: column;
        gap: 6px;
        font-size: 0.85rem;
    }

    .landing-social-item {
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .landing-social-item i {
        font-size: 1.1rem;
    }

    .landing-gallery {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 8px;
        margin-top: 8px;
    }

    .landing-gallery div {
        height: 120px;
        border-radius: 4px;
        background-size: cover;
        background-position: center;
        background-color: #111827;
    }

    .landing-buttons {
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        gap: 8px;
        align-items: center;
    }

    .landing-btn {
        width: 50%;
        max-width: 380px;
        border-radius: 999px;
        padding: 10px 20px;
        border: none;
        cursor: pointer;
        font-size: 0.95rem;
        font-weight: 600;
    }

    .landing-btn-primary {
        background: #2563eb;
        color: #fff;
    }

    .landing-btn-secondary {
        background: #1d4ed8;
        color: #e5e7eb;
    }

    @media (max-width: 768px) {
        .landing-card {
            max-width: 100%;
        }
        .landing-columns {
            grid-template-columns: 1fr;
        }
        .landing-header {
            flex-direction: column;
            gap: 6px;
        }
    }
</style>
@endpush

@section('content')
<div class="landing-wrapper">
    <div class="landing-card">

        {{-- ENCABEZADO --}}
        <div class="landing-header">
            <div class="landing-header-left">
                {{-- logotipo izquierda (reemplaza src por tus imágenes reales) --}}
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

        {{-- CUERPO --}}
        <div class="landing-body">
            <div class="landing-title">
                Únete a la comunidad que transforma ideas en algoritmos
            </div>

            <div class="landing-columns">
                {{-- Columna izquierda: redes / contacto --}}
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

                {{-- Columna derecha: texto + galería --}}
                <div>
                    <p>
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

