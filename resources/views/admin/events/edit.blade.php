@extends('layouts.admin')

@section('title', 'Editar evento')

@section('content')
<div class="admin-page-wrapper">
    <div class="admin-page-card">

        {{-- SIDEBAR IZQUIERDA --}}
        <div class="admin-sidebar">
            <a href="{{ route('admin.events.index') }}" class="admin-sidebar-back">
                <i class="bi bi-chevron-left"></i>
            </a>

            <div class="admin-sidebar-icon active">
                <i class="bi bi-calendar-event"></i>
            </div>
            <div class="admin-sidebar-icon">
                <i class="bi bi-people-fill"></i>
            </div>
            <div class="admin-sidebar-icon">
                <i class="bi bi-grid-1x2"></i>
            </div>
            <div class="admin-sidebar-icon">
                <i class="bi bi-person-badge"></i>
            </div>
        </div>

        {{-- CONTENIDO PRINCIPAL --}}
        <div class="admin-page-main">
            <div class="d-flex justify-content-end mb-2">
                <div class="admin-page-user">
                    <i class="bi bi-person-circle"></i>
                    <span>Admin</span>
                </div>
            </div>

            <h1 class="admin-form-title">Editar evento</h1>

            {{-- FORMULARIO --}}
            <form action="{{ route('admin.events.update', $event->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="admin-form-grid">
                    {{-- Columna izquierda --}}
                    <div class="admin-form-col">
                        {{-- Título --}}
                        <div class="admin-form-row">
                            <label class="admin-form-label" for="title">Título del evento</label>
                            <input
                                type="text"
                                id="title"
                                name="title"
                                class="admin-form-input"
                                placeholder="Título del evento"
                                value="{{ old('title', $event->title) }}"
                            >
                        </div>

                        {{-- Descripción --}}
                        <div class="admin-form-row">
                            <label class="admin-form-label" for="description">Descripción</label>
                            <textarea
                                id="description"
                                name="description"
                                class="admin-form-textarea"
                                placeholder="Descripción"
                            >{{ old('description', $event->description) }}</textarea>
                        </div>

                        {{-- Lugar --}}
                        <div class="admin-form-row">
                            <label class="admin-form-label" for="place">Lugar/campus</label>
                            <input
                                type="text"
                                id="place"
                                name="place"
                                class="admin-form-input"
                                placeholder="Lugar/campus"
                                value="{{ old('place', $event->place) }}"
                            >
                        </div>

                        {{-- Estado --}}
                        <div class="admin-form-row">
                            <label class="admin-form-label" for="status">Estado</label>
                            <select id="status" name="status" class="admin-form-select">
                                <option value="borrador" {{ old('status', $event->status) == 'borrador' ? 'selected' : '' }}>Borrador</option>
                                <option value="activo" {{ old('status', $event->status) == 'activo' ? 'selected' : '' }}>Activo</option>
                                <option value="cerrado" {{ old('status', $event->status) == 'cerrado' ? 'selected' : '' }}>Cerrado</option>
                            </select>
                        </div>
                    </div>

                    {{-- Columna derecha --}}
                    <div class="admin-form-col">
                        {{-- Capacidad --}}
                        <div class="admin-form-row">
                            <label class="admin-form-label" for="capacity">Capacidad</label>
                            <input
                                type="number"
                                id="capacity"
                                name="capacity"
                                class="admin-form-input"
                                placeholder="10"
                                value="{{ old('capacity', $event->capacity) }}"
                                min="1"
                            >
                        </div>

                        {{-- Fecha inicio --}}
                        <div class="admin-form-row">
                            <label class="admin-form-label" for="start_date">Fecha inicio</label>
                            <input
                                type="date"
                                id="start_date"
                                name="start_date"
                                class="admin-form-input"
                                value="{{ old('start_date', optional($event->start_date)->format('Y-m-d')) }}"
                            >
                        </div>

                        {{-- Fecha fin --}}
                        <div class="admin-form-row">
                            <label class="admin-form-label" for="end_date">Fecha fin</label>
                            <input
                                type="date"
                                id="end_date"
                                name="end_date"
                                class="admin-form-input"
                                value="{{ old('end_date', optional($event->end_date)->format('Y-m-d')) }}"
                            >
                        </div>
                    </div>
                </div>

                {{-- BOTONES --}}
                <div class="admin-form-footer">
                    <a href="{{ route('admin.events.index') }}" class="btn admin-btn-pill admin-btn-secondary">
                        Cancelar
                    </a>

                    <button type="submit" class="admin-btn-pill admin-btn-primary">
                        Guardar cambios
                    </button>

                    <button type="button" class="admin-btn-pill admin-btn-disabled" disabled>
                        Crear evento
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>
@endsection
