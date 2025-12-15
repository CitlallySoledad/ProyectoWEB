@extends('layouts.admin-panel')

@section('title', 'Usuarios administradores')

@push('styles')
<style>
/* ===== COLORES BLANCOS PARA TEXTO ===== */
.h4, .mb-0, .admin-card-title, .admin-table th, .admin-table td {
    color: #fff !important;
}

/* ===== PAGINACIÓN COMPACTA USUARIOS ===== */
.users-pagination {
    margin: 24px auto 8px;
    padding: 8px 16px;
    border-radius: 999px;
    background: rgba(37, 99, 235, 0.1);
    display: flex;
    align-items: center;
    gap: 18px;
    width: auto;
    max-width: 100%;
    font-size: 0.85rem;
    color: #e5e7eb;
}

.users-pagination-info {
    white-space: nowrap;
    opacity: 0.85;
    font-weight: 500;
}

.users-pagination-pages {
    display: flex;
    align-items: center;
    gap: 6px;
}

/* Botones de página */
.page-number,
.page-arrow {
    min-width: 32px;
    height: 32px;
    padding: 0 10px;
    border-radius: 999px;
    border: 1px solid #2563eb;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.85rem;
    text-decoration: none;
    color: #e5e7eb;
    background: transparent;
    transition: background 0.18s ease, color 0.18s ease, transform 0.12s ease;
    cursor: pointer;
}

.page-number:hover,
.page-arrow:hover {
    background: #2563eb;
    color: #ffffff;
    transform: translateY(-1px);
}

/* Página actual */
.page-number.active {
    background: #2563eb;
    color: #ffffff;
    border-color: #1d4ed8;
    font-weight: 600;
}

/* Deshabilitados */
.page-arrow.disabled {
    opacity: 0.35;
    border-color: #94a3b8;
    cursor: default;
    pointer-events: none;
}
</style>
@endpush

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h4 mb-0">Usuarios (admins)</h1>
        <a href="{{ route('admin.users.create') }}" class="admin-btn-primary text-decoration-none">
            <i class="bi bi-plus-circle me-1"></i> Crear usuario admin
        </a>
    </div>

    <div class="admin-card">
        <div class="admin-card-title">Lista de usuarios administradores</div>

        @if($users->isEmpty())
            <p class="mb-0">No hay usuarios administradores registrados.</p>
        @else
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Correo</th>
                        <th style="width: 120px; text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td style="text-align: center;">
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display: inline;" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" style="padding: 4px 12px; font-size: 0.85rem;">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <span style="color: #94a3b8; font-size: 0.8rem;">—</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- PAGINACIÓN COMPACTA --}}
            @if ($users->hasPages())
                <div class="users-pagination">
                    <span class="users-pagination-info">
                        Mostrando {{ $users->firstItem() }} - {{ $users->lastItem() }} de {{ $users->total() }} usuarios
                    </span>

                    <div class="users-pagination-pages">
                        {{-- Flecha Anterior --}}
                        @if ($users->onFirstPage())
                            <span class="page-arrow disabled">&laquo;</span>
                        @else
                            <a href="{{ $users->previousPageUrl() }}" class="page-arrow">&laquo;</a>
                        @endif

                        {{-- Números de página --}}
                        @foreach ($users->getUrlRange(1, $users->lastPage()) as $page => $url)
                            @if ($page == $users->currentPage())
                                <span class="page-number active">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="page-number">{{ $page }}</a>
                            @endif
                        @endforeach

                        {{-- Flecha Siguiente --}}
                        @if ($users->hasMorePages())
                            <a href="{{ $users->nextPageUrl() }}" class="page-arrow">&raquo;</a>
                        @else
                            <span class="page-arrow disabled">&raquo;</span>
                        @endif
                    </div>
                </div>
            @endif
        @endif
    </div>

@endsection

