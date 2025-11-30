@extends('layouts.admin')

@section('title', 'Crear cuenta')

@push('styles')
<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

/* RESET */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* FONDO GENERAL */
body {
    font-family: "Poppins", system-ui, -apple-system, sans-serif;
    background:
        radial-gradient(circle at 10% 20%, #1d4ed8 0, transparent 55%),
        radial-gradient(circle at 90% 80%, #a855f7 0, transparent 55%),
        radial-gradient(circle at 50% 10%, #22c55e 0, transparent 55%),
        #020617;
}

/* CONTENEDOR CENTRADO */
.registro-page {
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 24px;
    position: relative;
    overflow: hidden;
}

/* LUCES SUAVES */
.registro-page::before,
.registro-page::after {
    content: "";
    position: absolute;
    width: 420px;
    height: 420px;
    border-radius: 50%;
    filter: blur(40px);
    opacity: 0.45;
    z-index: 0;
}
.registro-page::before {
    background: #4f46e5;
    top: -120px;
    left: -80px;
}
.registro-page::after {
    background: #ec4899;
    bottom: -140px;
    right: -60px;
}

/* TARJETA PRINCIPAL */
.registro-card {
    display: flex;
    width: 100%;
    max-width: 900px;
    background: linear-gradient(180deg, #2c7bcf, #0c3569);
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, .55);
    position: relative;
    z-index: 1;
}

/* COLUMNA MORADA */
.registro-aside {
    width: 90px;
    background: #6d3c95;
    display: flex;
    flex-direction: column;
    justify-content: space-around;
    align-items: center;
    padding: 16px 4px;
}
.avatar {
    width: 60px;
    height: 60px;
    object-fit: contain;
}

/* COLUMNA FORMULARIO */
.registro-main {
    flex: 1;
    display: flex;
    justify-content: flex-start;
    align-items: flex-start;
    padding: 32px 32px 40px;
}

/* FORMULARIO */
.form-card {
    width: 100%;
    max-width: 430px;
    color: #fff;
    margin: 0 auto;
}
.form-card h1 {
    font-size: 28px;
    margin-bottom: 18px;
}

/* INPUTS */
.form-group {
    margin-bottom: 10px;
}
.form-group label {
    display: block;
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 3px;
}
.form-group input,
.form-group select {
    width: 100%;
    padding: 7px 10px;
    border-radius: 6px;
    border: none;
    outline: none;
    font-size: 14px;
    background: #ffffff;
    color: #1f1f1f;
}
.form-group input::placeholder {
    color: #8a8a8a;
}

/* BOTÓN */
.form-actions {
    margin-top: 14px;
    display: flex;
    justify-content: center;
}
.btn-submit {
    width: 80%;
    padding: 10px 18px;
    border: none;
    border-radius: 999px;
    background: linear-gradient(135deg, #4c1d95, #7c3aed);
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    box-shadow: 0 6px 14px rgba(0, 0, 0, .45);
    transition: .15s;
}
.btn-submit:hover {
    filter: brightness(1.07);
    transform: translateY(-1px);
    box-shadow: 0 10px 20px rgba(0, 0, 0, .55);
}

/* RESPONSIVO */
@media (max-width: 900px) {
    .registro-card {
        flex-direction: column;
        max-width: 480px;
    }

    .registro-aside {
        width: 100%;
        flex-direction: row;
        justify-content: space-around;
        padding: 12px;
    }
    .avatar {
        width: 50px;
        height: 50px;
    }

    .registro-main {
        padding: 24px 20px 32px;
    }
}

@media (max-width: 500px) {
    .form-card h1 {
        font-size: 24px;
    }
}
</style>
@endpush

@section('content')
<div class="registro-page">
    <div class="registro-card">

        <!-- COLUMNA MORADA -->
        <aside class="registro-aside">
            <img src="{{ asset('imagenes/avatar.jpg') }}" alt="Avatar" class="avatar">
            <img src="{{ asset('imagenes/avatar.jpg') }}" alt="Avatar" class="avatar">
            <img src="{{ asset('imagenes/avatar.jpg') }}" alt="Avatar" class="avatar">
            <img src="{{ asset('imagenes/avatar.jpg') }}" alt="Avatar" class="avatar">
            <img src="{{ asset('imagenes/avatar.jpg') }}" alt="Avatar" class="avatar">
        </aside>

        <!-- FORMULARIO -->
        <main class="registro-main">
            <form class="form-card" method="POST" action="{{ route('registro.store') }}">
                @csrf

                <h1>Crear cuenta</h1>

                <div class="form-group">
                    <label>Número de control</label>
                    <input type="text" name="control" placeholder="Número de control">
                </div>

                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="nombre" placeholder="Nombre">
                </div>

                <div class="form-group">
                    <label>Apellido Paterno</label>
                    <input type="text" name="ap_paterno" placeholder="Apellido Paterno">
                </div>

                <div class="form-group">
                    <label>Apellido Materno</label>
                    <input type="text" name="ap_materno" placeholder="Apellido Materno">
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" placeholder="Contraseña">
                </div>

                <div class="form-group">
                    <label>Confirmar contraseña</label>
                    <input type="password" name="password_confirmation" placeholder="Confirmar contraseña">
                </div>

                <div class="form-group">
                    <label>Correo electrónico</label>
                    <input type="email" name="email" placeholder="Correo electrónico">
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="tel" name="telefono" placeholder="Teléfono">
                </div>

                <div class="form-group">
                    <label>Selecciona tu carrera</label>
                    <select name="carrera">
                        <option value="">Seleccione su carrera</option>
                        <option>Ingeniería en Sistemas Computacionales</option>
                        <option>Ingeniería Industrial</option>
                        <option>Ingeniería Civil</option>
                        <option>Otra...</option>
                    </select>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Registrarse</button>
                </div>
            </form>
        </main>

    </div>
</div>
@endsection

