@extends('layouts.admin')

@section('title', 'Roles Participantes')

@section('content')
    <div class="admin-page-wrapper">
        <div class="admin-page-card">

            {{-- SIDEBAR IZQUIERDA --}}
            <div class="admin-sidebar">
                <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-back">
                    <i class="bi bi-chevron-left"></i>
                </a>
                <div class="admin-sidebar-icon">
                    <a href="{{ route('panel.participante') }}">
                        <i class="bi bi-house-door"></i>
                    </a>
                </div>
                <div class="admin-sidebar-icon">
                    <a href="{{ route('panel.eventos') }}">
                        <i class="bi bi-search"></i>
                    </a>
                </div>
                <div class="admin-sidebar-icon">
                    <a href="{{ route('panel.perfil') }}">
                        <i class="bi bi-people"></i>
                    </a>
                </div>
                <div class="admin-sidebar-icon">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="bi bi-box-arrow-left"></i>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </div>

            {{-- CONTENIDO PRINCIPAL --}}
            <div class="admin-page-main">
                <div class="admin-page-header">
                    <h1 class="admin-page-title">Roles</h1>
                    <div class="admin-page-user">
                        <i class="bi bi-person-circle"></i>
                        <span>Usuario Líder</span>
                    </div>
                </div>

                {{-- BUSCAR EQUIPO --}}
                <div class="search-container">
                    <input type="text" placeholder="Buscar Equipo" class="search-input">
                </div>

                {{-- ROLES Y PARTICIPANTES EN DOS COLUMNAS --}}
                <div class="roles-participants-wrapper">
                    {{-- ROLES EN GRID 2x2 --}}
                    <div class="roles-grid">
                        @foreach (['BACK', 'FRONT', 'DISEÑADOR', 'LÍDER'] as $rol)
                            <div class="role-card">
                                <div class="role-title">{{ $rol }}</div>
                                <img src="{{ asset('imagenes/FLOR.webp') }}" alt="Flor decorativa" class="role-image">
                                <div class="role-label">Flor decorativa</div>
                                <button class="role-btn">
                                    <i class="bi bi-people-fill"></i>
                                    <i class="bi bi-arrow-right"></i>
                                    ROL
                                </button>
                            </div>
                        @endforeach
                    </div>

                    {{-- PARTICIPANTES AL LADO --}}
                    <div class="participants-panel">
                        <h3>Participantes</h3>
                        <div class="participants-list">
                            <div class="participant-item">
                                <span class="participant-name">Alfredo</span>
                                <span class="participant-role">Back</span>
                            </div>
                            <div class="participant-item">
                                <span class="participant-name">Liz</span>
                                <span class="participant-role">Front</span>
                            </div>
                            <div class="participant-item">
                                <span class="participant-name">Citlaly</span>
                                <span class="participant-role">Diseñador</span>
                            </div>
                            <div class="participant-item">
                                <span class="participant-name">Usuario Líder</span>
                                <span class="participant-role">Líder</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .admin-page-wrapper {
            display: flex;
            height: 100vh;
            background: #8a1e5f !important;
        }

        .admin-page-card {
            display: flex;
            width: 100%;
        }

        .admin-sidebar {
            width: 100px;
            background: #6A1B9A;
            color: white;
            padding: 40px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
        }

        .admin-sidebar-icon {
            margin: 25px 0;
            font-size: 2.0rem;
        }

        .admin-page-main {
            flex-grow: 1;
            padding: 30px;
            color: white !important;
        }

        .admin-page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .admin-page-title {
            font-size: 2rem;
            font-weight: bold;
        }

        .admin-page-user {
            background: #497eba;
            padding: 8px 12px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: 600;
        }

        .search-container {
            margin-bottom: 20px;
        }

        .search-input {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: none;
            font-size: 1rem;
        }

        .roles-participants-wrapper {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            margin-top: 20px;
        }

        .roles-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }

        .role-card {
            background-color: #1d4166;
            padding: 12px;
            border-radius: 10px;
            text-align: center;
            color: white;
            font-size: 0.9rem;
        }

        .role-title {
            font-size: 1rem;
            font-weight: bold;
            margin-bottom: 6px;
        }

        .role-image {
            width: 100%;
            height: 80px;
            object-fit: cover;
            border-radius: 6px;
            margin-bottom: 6px;
        }

        .role-label {
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .role-btn {
            background-color: #93c5fd;
            border: none;
            padding: 6px 12px;
            border-radius: 8px;
            font-weight: bold;
            color: #1e3a8a;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            cursor: pointer;
            font-size: 0.85rem;
        }

        .role-btn:hover {
            background-color: #60a5fa;
        }

        .participants-panel {
            background: #f3f4f6;
            padding: 20px;
            border-radius: 12px;
            color: black;
            height: fit-content;
        }

        .participants-panel h3 {
            font-size: 1.3rem;
            margin-bottom: 16px;
        }

        .participants-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .participant-item {
            background-color: #232323;
            color: white;
            padding: 10px 14px;
            border-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 0.9rem;
        }

        .participant-role {
            color: #3B82F6;
            font-weight: bold;
        }
    </style>
@endpush