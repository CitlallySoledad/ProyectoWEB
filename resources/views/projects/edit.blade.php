@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Evaluación del proyecto</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Equipo</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $project->name }}</td>
                <td>{{ $project->team }}</td>
                <td>{{ $project->status }}</td>
                <td>
                    <a href="{{ route('projects.edit', $project) }}">Ver</a>
                    <a href="{{ route('projects.edit', $project) }}">Editar</a>
                    <a href="{{ route('projects.destroy', $project) }}">Eliminar</a>
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
