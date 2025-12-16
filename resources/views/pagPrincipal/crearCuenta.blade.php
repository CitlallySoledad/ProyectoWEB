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

.pwd-wrapper {
    position: relative;
}

.pwd-wrapper input {
    padding-right: 42px;
}

.pwd-toggle {
    position: absolute;
    right: 10px;
    top: 50%;
    transform: translateY(-50%);
    border: none;
    background: transparent;
    cursor: pointer;
    color: #6b7280;
    font-size: 0.95rem;
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

/* MENSAJES DE ERROR */
.alert {
    padding: 10px 14px;
    border-radius: 8px;
    margin-bottom: 16px;
    font-size: 13px;
}
.alert-error {
    background: rgba(239, 68, 68, 0.9);
    color: #fff;
    border: 1px solid rgba(239, 68, 68, 1);
}
.alert ul {
    list-style: none;
    margin: 0;
    padding: 0;
}
.alert li {
    margin: 4px 0;
}
.error-message {
    display: block;
    color: #fecaca;
    font-size: 12px;
    margin-top: 4px;
}
.required {
    color: #fca5a5;
    font-weight: bold;
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

                @if ($errors->any())
                    <div class="alert alert-error">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label>Número de control <span class="required">*</span></label>
                    <input type="text" name="control" placeholder="Letras y números" value="{{ old('control') }}" required pattern="[A-Za-z0-9]+">
                    @error('control')<span class="error-message">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Nombre <span class="required">*</span></label>
                    <input type="text" name="nombre" placeholder="Solo letras" value="{{ old('nombre') }}" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+">
                    @error('nombre')<span class="error-message">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Apellido Paterno <span class="required">*</span></label>
                    <input type="text" name="ap_paterno" placeholder="Solo letras" value="{{ old('ap_paterno') }}" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+">
                    @error('ap_paterno')<span class="error-message">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Apellido Materno <span class="required">*</span></label>
                    <input type="text" name="ap_materno" placeholder="Solo letras" value="{{ old('ap_materno') }}" required pattern="[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+">
                    @error('ap_materno')<span class="error-message">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Contraseña <span class="required">*</span></label>
                    <div class="pwd-wrapper">
                        <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Mínimo 8 caracteres, mayúscula, minúscula y número"
                        required
                        minlength="8"
                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d).{8,}$"
                        title="Mínimo 8 caracteres e incluir mayúscula, minúscula y número"
                    >
                    <button type="button" class="pwd-toggle" onclick="togglePwd('password','togglePwdIcon')">
                        <i id="togglePwdIcon" class="bi bi-eye"></i>
                    </button>
                </div>
                @error('password')<span class="error-message">{{ $message }}</span>@enderror
            </div>

                <div class="form-group">
                    <label>Confirmar contraseña <span class="required">*</span></label>
                    <div class="pwd-wrapper">
                        <input
                        type="password"
                        id="password_confirmation"
                        name="password_confirmation"
                        placeholder="Confirmar contraseña"
                        required
                        minlength="8"
                        pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d).{8,}$"
                        title="Mínimo 8 caracteres e incluir mayúscula, minúscula y número"
                    >
                    <button type="button" class="pwd-toggle" onclick="togglePwd('password_confirmation','togglePwdIcon2')">
                        <i id="togglePwdIcon2" class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

                <div class="form-group">
                    <label>Correo electrónico <span class="required">*</span></label>
                    <input type="email" name="email" placeholder="ejemplo@dominio.com" value="{{ old('email') }}" required>
                    @error('email')<span class="error-message">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Teléfono <span class="required">*</span></label>
                    <input type="tel" name="telefono" placeholder="Solo números" value="{{ old('telefono') }}" required pattern="[0-9]+">
                    @error('telefono')<span class="error-message">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Selecciona tu carrera <span class="required">*</span></label>
                    <select name="carrera" required>
                        <option value="">Seleccione su carrera</option>
                        <option value="Contador Público" {{ old('carrera') == 'Contador Público' ? 'selected' : '' }}>Contador Público</option>
                        <option value="Licenciatura en Administración" {{ old('carrera') == 'Licenciatura en Administración' ? 'selected' : '' }}>Licenciatura en Administración</option>
                        <option value="Ingeniería Química" {{ old('carrera') == 'Ingeniería Química' ? 'selected' : '' }}>Ingeniería Química</option>
                        <option value="Ingeniería Mecánica" {{ old('carrera') == 'Ingeniería Mecánica' ? 'selected' : '' }}>Ingeniería Mecánica</option>
                        <option value="Ingeniería Industrial" {{ old('carrera') == 'Ingeniería Industrial' ? 'selected' : '' }}>Ingeniería Industrial</option>
                        <option value="Ingeniería en Sistemas Computacionales" {{ old('carrera') == 'Ingeniería en Sistemas Computacionales' ? 'selected' : '' }}>Ingeniería en Sistemas Computacionales</option>
                        <option value="Ingeniería en Gestión Empresarial" {{ old('carrera') == 'Ingeniería en Gestión Empresarial' ? 'selected' : '' }}>Ingeniería en Gestión Empresarial</option>
                        <option value="Ingeniería Electrónica" {{ old('carrera') == 'Ingeniería Electrónica' ? 'selected' : '' }}>Ingeniería Electrónica</option>
                        <option value="Ingeniería Eléctrica" {{ old('carrera') == 'Ingeniería Eléctrica' ? 'selected' : '' }}>Ingeniería Eléctrica</option>
                        <option value="Ingeniería Civil" {{ old('carrera') == 'Ingeniería Civil' ? 'selected' : '' }}>Ingeniería Civil</option>
                    </select>
                    @error('carrera')<span class="error-message">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>Rol <span class="required">*</span></label>
                    <select name="role" required>
                        <option value="student" selected>Estudiante</option>
                    </select>
                    @error('role')<span class="error-message">{{ $message }}</span>@enderror
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-submit">Registrarse</button>
                </div>
            </form>
        </main>

</div>
</div>
@endsection

@push('scripts')
<script>
    function togglePwd(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (!input) return;
        if (input.type === 'password') {
            input.type = 'text';
            if (icon) {
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        } else {
            input.type = 'password';
            if (icon) {
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    }
</script>
@endpush
