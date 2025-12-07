@extends('layouts.admin-panel')

@section('title', 'Usuarios administradores')

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
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

@endsection

