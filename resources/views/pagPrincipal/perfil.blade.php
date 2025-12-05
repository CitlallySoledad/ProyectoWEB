@extends('layouts.admin')

@section('title', 'Mi perfil')

@push('styles')
<style>
    .panel-wrapper {
        min-height: 100vh;
        display: flex;
        margin: 0;
        padding: 0;
        background: #020617;
    }

    .panel-card {
        width: 100%;
        min-height: 100vh;
        display: flex;
        background: radial-gradient(circle at top, #1e3a8a, #020617);
        color: #e5e7eb;
    }

    /* SIDEBAR */
    .panel-sidebar {
        width: 250px;
        background: linear-gradient(180deg, #4c1d95, #7c3aed);
        padding: 18px 14px;
        display: flex;
        flex-direction: column;
        box-shadow: 8px 0 20px rgba(0,0,0,0.35);
        z-index: 2;
    }

    .sidebar-top {
        display: flex;
        align-items: center;
        margin-bottom: 24px;
    }

    .sidebar-back {
        width: 32px;
        height: 32px;
        border-radius: 999px;
        border: 1px solid rgba(255,255,255,0.35);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background: rgba(15,23,42,0.25);
    }

    .sidebar-back i {
        color: #fff;
        font-size: 1rem;
    }

    .sidebar-logo {
        margin-left: auto;
    }

    .sidebar-logo img {
        height: 40px;
    }

    .sidebar-menu {
        list-style: none;
        padding: 0;
        margin-top: 20px;
    }

    .sidebar-item {
        margin-bottom: 14px;
    }

    .sidebar-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 12px;
        color: #fff !important;
        text-decoration: none;
        cursor: pointer;
        transition: background-color 0.3s ease, transform 0.2s ease;
    }

    .sidebar-link:hover {
        background: rgba(255,255,255,0.15);
        transform: translateX(2px);
    }

    .sidebar-link.active {
        background: rgba(15,23,42,0.95);
        box-shadow: 0 0 0 1px rgba(15,23,42,0.6);
    }

    .logout-btn {
        width: 100%;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        border-radius: 12px;
        background: transparent;
        border: none;
        color: #fff;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: auto;
    }

    .logout-btn:hover {
        background: rgba(255,255,255,0.15);
    }

    /* MAIN PANEL */
    .panel-main {
        flex: 1;
        padding: 20px 26px 26px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        position: relative;
        z-index: 1;
    }

    .panel-header {
        display: flex;
        justify-content: flex-end;
    }

    .user-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 6px 12px;
        border-radius: 999px;
        background: rgba(15,23,42,0.85);
        border: 1px solid rgba(148,163,184,0.85);
        font-size: 0.85rem;
    }

    .user-badge i {
        font-size: 1rem;
    }

    h1 {
        margin-top: 10px;
        margin-bottom: 6px;
    }

    .alert-success {
        background: #15803d;
        color: #fff;
        padding: 8px 14px;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 0.85rem;
    }

    /* PROFILE LAYOUT */
    .profile-wrapper {
        display: flex;
        gap: 32px;
        margin-top: 10px;
        align-items: flex-start;
    }

    .profile-image {
        width: 220px;
        text-align: center;
    }

    .profile-image img {
        width: 180px;
        height: 180px;
        border-radius: 18px;
        object-fit: cover;
        background: #111827;
        box-shadow: 0 12px 30px rgba(0,0,0,0.55);
        animation: card-pop 0.5s ease-out;
    }

    .profile-profession {
        margin-top: 10px;
        font-size: 0.9rem;
        opacity: 0.9;
    }

    .profile-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    /* TARJETAS ANIMADAS */
    .profile-info {
        background: rgba(2,6,23,0.95);
        padding: 24px;
        border-radius: 18px;
        box-shadow: 0 18px 40px rgba(15,23,42,0.75);
        border: 1px solid rgba(148,163,184,0.2);
        transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
        animation: card-fade 0.5s ease-out;
    }

    .profile-info:hover {
        transform: translateY(-4px);
        box-shadow: 0 22px 55px rgba(15,23,42,0.9);
        border-color: rgba(56,189,248,0.8);
    }

    .profile-info h3 {
        margin-top: 0;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .profile-info h3::before {
        content: '';
        width: 6px;
        height: 20px;
        border-radius: 999px;
        background: linear-gradient(180deg,#38bdf8,#a855f7);
    }

    /* FORM FIELDS */
    .profile-info label {
        display: block;
        margin-top: 8px;
        margin-bottom: 3px;
        font-size: 0.9rem;
        font-weight: 600;
    }

    .profile-info input,
    .profile-info select {
        width: 100%;
        border-radius: 10px;
        border: none;
        padding: 8px 12px;
        font-size: 0.9rem;
        margin-bottom: 6px;
        color: #111827;
    }

    .profile-info input:focus,
    .profile-info select:focus {
        outline: 2px solid #38bdf8;
        box-shadow: 0 0 0 2px rgba(56,189,248,0.4);
    }

    /* BOTONES */
    .btn-edit-datos {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 16px;
        border-radius: 999px;
        border: 1px solid #38bdf8;
        color: #e0f2fe;
        text-decoration: none;
        margin-top: 10px;
        font-size: 0.85rem;
    }

    .btn-edit-datos:hover {
        background: rgba(56,189,248,0.2);
    }

    .btn-guardar {
        margin-top: 12px;
        padding: 8px 20px;
        border-radius: 10px;
        border: none;
        background: #0f172a;
        color: #e5e7eb;
        cursor: pointer;
        font-size: 0.9rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-guardar:hover {
        background: #020617;
    }

    /* ANIMACIONES */
    @keyframes card-fade {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes card-pop {
        from {
            opacity: 0;
            transform: scale(0.96);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @media (max-width: 960px) {
        .panel-card {
            flex-direction: column;
        }
        .profile-wrapper {
            flex-direction: column;
            align-items: stretch;
        }
    }
</style>
@endpush

@section('content')
<div class="panel-wrapper">
    <div class="panel-card">

        {{-- SIDEBAR --}}
        <aside class="panel-sidebar">
            <div class="sidebar-top">
                <button class="sidebar-back" type="button" onclick="window.history.back();">
                    <i class="bi bi-arrow-left"></i>
                </button>
                <div class="sidebar-logo">
                    <img src="{{ asset('imagenes/logo-ito.png') }}" alt="Logo">
                </div>
            </div>

            <ul class="sidebar-menu">
                <li class="sidebar-item">
                    {{-- Activo cuando NO estamos en datos ni equipo --}}
                    <a class="sidebar-link {{ empty($editDatos) && empty($editEquipo) ? 'active' : '' }}"
                       href="{{ route('panel.perfil') }}">
                        <i class="bi bi-person-fill"></i>
                        <span>Mi perfil</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    {{-- Activo cuando ?edit=datos --}}
                    <a class="sidebar-link {{ !empty($editDatos) ? 'active' : '' }}"
                       href="{{ route('panel.perfil', ['edit' => 'datos']) }}#datos-personales">
                        <i class="bi bi-file-earmark-person-fill"></i>
                        <span>Datos personales</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    {{-- Activo cuando ?edit=equipo --}}
                    <a class="sidebar-link {{ !empty($editEquipo) ? 'active' : '' }}"
                       href="{{ route('panel.perfil', ['edit' => 'equipo']) }}#mi-equipo">
                        <i class="bi bi-people-fill"></i>
                        <span>Mi equipo</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <a class="sidebar-link" href="{{ route('panel.cambiarContrasena') }}">
                        <i class="bi bi-lock-fill"></i>
                        <span>Cambiar contraseña</span>
                    </a>
                </li>
            </ul>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="logout-btn" type="submit">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Cerrar sesión</span>
                </button>
            </form>
        </aside>

        {{-- CONTENIDO PRINCIPAL --}}
        <main class="panel-main">
            <div class="panel-header">
                <div class="user-badge">
                    <i class="bi bi-person-fill"></i>
                    <span>{{ $user->name }}</span>
                </div>
            </div>

            <h1>Detalles de Mi Perfil</h1>

            @if(session('success'))
                <div class="alert-success">
                    <i class="bi bi-check-circle-fill"></i>
                    {{ session('success') }}
                </div>
            @endif

            <div class="profile-wrapper">
                {{-- FOTO --}}
                <div class="profile-image">
                    <img src="{{ asset('imagenes/foto-perfil.jpg') }}" alt="Foto de perfil">
                    <p class="profile-profession">
                        {{ $user->profesion ?? 'Ingeniería en Sistemas Computacionales' }}
                    </p>
                </div>

                {{-- TARJETAS --}}
                <div class="profile-details">
                    {{-- DATOS PERSONALES --}}
                    <div class="profile-info" id="datos-personales">
                        <h3>Datos personales</h3>

                        @if(!empty($editDatos))
                            {{-- MODO EDICIÓN --}}
                            <form action="{{ route('panel.perfil.updateDatos') }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <label for="name">Nombre completo</label>
                                <input type="text" id="name" name="name"
                                       value="{{ old('name', $user->name) }}">

                                <label for="email">Correo electrónico</label>
                                <input type="email" id="email" name="email"
                                       value="{{ old('email', $user->email) }}">

                                <label for="curp">CURP</label>
                                <input type="text" id="curp" name="curp"
                                       value="{{ old('curp', $user->curp) }}">

                                <label for="fecha_nacimiento">Fecha de nacimiento</label>
                                <input type="date" id="fecha_nacimiento" name="fecha_nacimiento"
                                       value="{{ old('fecha_nacimiento', optional($user->fecha_nacimiento)->format('Y-m-d')) }}">

                                <label for="genero">Género</label>
                                <select id="genero" name="genero">
                                    @php
                                        $generoValue = old('genero', $user->genero);
                                    @endphp
                                    <option value="" {{ $generoValue === null || $generoValue === '' ? 'selected' : '' }}>Selecciona una opción</option>
                                    <option value="Masculino" {{ $generoValue === 'Masculino' ? 'selected' : '' }}>Masculino</option>
                                    <option value="Femenino" {{ $generoValue === 'Femenino' ? 'selected' : '' }}>Femenino</option>
                                    <option value="Otro" {{ $generoValue === 'Otro' ? 'selected' : '' }}>Otro</option>
                                    <option value="Prefiero no decirlo" {{ $generoValue === 'Prefiero no decirlo' ? 'selected' : '' }}>Prefiero no decirlo</option>
                                </select>

                                <label for="estado_civil">Estado civil</label>
                                <select id="estado_civil" name="estado_civil">
                                    @php
                                        $estadoValue = old('estado_civil', $user->estado_civil);
                                    @endphp
                                    <option value="" {{ $estadoValue === null || $estadoValue === '' ? 'selected' : '' }}>Selecciona una opción</option>
                                    <option value="Soltero(a)" {{ $estadoValue === 'Soltero(a)' ? 'selected' : '' }}>Soltero(a)</option>
                                    <option value="Casado(a)" {{ $estadoValue === 'Casado(a)' ? 'selected' : '' }}>Casado(a)</option>
                                    <option value="Unión libre" {{ $estadoValue === 'Unión libre' ? 'selected' : '' }}>Unión libre</option>
                                    <option value="Divorciado(a)" {{ $estadoValue === 'Divorciado(a)' ? 'selected' : '' }}>Divorciado(a)</option>
                                    <option value="Viudo(a)" {{ $estadoValue === 'Viudo(a)' ? 'selected' : '' }}>Viudo(a)</option>
                                    <option value="Otro" {{ $estadoValue === 'Otro' ? 'selected' : '' }}>Otro</option>
                                </select>

                                <label for="telefono">Teléfono</label>
                                <input type="text" id="telefono" name="telefono"
                                       value="{{ old('telefono', $user->telefono) }}">

                                <label for="profesion">Profesión</label>
                                <input type="text" id="profesion" name="profesion"
                                       value="{{ old('profesion', $user->profesion) }}">

                                <button type="submit" class="btn-guardar">
                                    <i class="bi bi-save"></i>
                                    Guardar cambios
                                </button>
                            </form>
                        @else
                            {{-- SOLO LECTURA --}}
                            <p><strong>Nombre:</strong> {{ $user->name }}</p>
                            <p><strong>Correo electrónico:</strong> {{ $user->email }}</p>
                            <p><strong>CURP:</strong> {{ $user->curp ?? '—' }}</p>

                            <p><strong>Fecha de nacimiento:</strong>
                                @if($user->fecha_nacimiento)
                                    {{ $user->fecha_nacimiento->format('d/m/Y') }}
                                @else
                                    —
                                @endif
                            </p>

                            <p><strong>Edad:</strong>
                                @if($user->fecha_nacimiento)
                                    {{ $user->fecha_nacimiento->age }} años
                                @else
                                    —
                                @endif
                            </p>

                            <p><strong>Género:</strong> {{ $user->genero ?? '—' }}</p>
                            <p><strong>Estado civil:</strong> {{ $user->estado_civil ?? '—' }}</p>
                            <p><strong>Teléfono:</strong> {{ $user->telefono ?? '—' }}</p>
                            <p><strong>Profesión:</strong> {{ $user->profesion ?? '—' }}</p>

                            <a href="{{ route('panel.perfil', ['edit' => 'datos']) }}#datos-personales"
                               class="btn-edit-datos">
                                <i class="bi bi-pencil-square"></i>
                                Editar datos personales
                            </a>
                        @endif
                    </div>

                    {{-- MI EQUIPO --}}
                    <div class="profile-info" id="mi-equipo">
                        <h3>Mi equipo</h3>

                        @if($teams->isEmpty())
                            <p>No perteneces a ningún equipo.</p>
                        @else
                            @foreach($teams as $team)
                                <h4 style="margin-top: 10px;">Equipo: {{ $team->name }}</h4>

                                <ul style="margin-left: 16px;">
                                    @foreach($team->members as $member)
                                        <li>
                                            <strong>{{ $member->name }}</strong>
                                            @if($member->id === optional($team->leader)->id)
                                                <span style="color:#38bdf8;">(Líder)</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endforeach
                        @endif

                        {{-- En un futuro aquí podrías poner opciones para modificar tu equipo --}}
                    </div>
                </div>
            </div>
        </main>

    </div>
</div>
@endsection

