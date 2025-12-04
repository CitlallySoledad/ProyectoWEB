@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Lista de proyectos a evaluar</h2>
    <form action="{{ route('projects.store') }}" method="POST">
        @csrf
        <input type="text" name="name" placeholder="Buscar Proyecto">
        <button type="submit">Crear</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Proyecto</th>
                <th>Estado</th>
                <th>Miembros</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($projects as $project)
                <tr>
                    <td>{{ $project->name }}</td>
                    <td>{{ $project->team }}</td>
                    <td>{{ $project->status }}</td>
                    <td>{{ count($project->members) }}</td>
                    <td>
                        @if($project->status == 'Pendiente')
                            <a href="{{ route('projects.evaluate', $project) }}">Evaluar</a>
                        @else
                            <a href="{{ route('projects.edit', $project) }}">Ver Evaluación</a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
