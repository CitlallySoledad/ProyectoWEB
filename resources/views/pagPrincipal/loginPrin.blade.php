<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Pantalla de Inicio de Sesión</title>

  <!-- Carga correcta usando Vite -->
  @vite(['resources/css/login.css'])
</head>

<body>
  <div class="auth-page">

    <!-- LADO IZQUIERDO -->
    <aside class="auth-aside">
      <div class="shape-top"></div>
      <div class="shape-bottom"></div>

      <div class="circle-card card-top">
        <img src="{{ asset('img/login-arriba.png') }}" alt="Ilustración superior">
      </div>

      <div class="circle-card card-bottom">
        <img src="{{ asset('img/login-abajo.png') }}" alt="Ilustración inferior">
      </div>
    </aside>

    <!-- LADO DERECHO -->
    <main class="auth-main">
      <div class="login-card">

        <div class="avatar">
          <svg viewBox="0 0 40 40" aria-hidden="true">
            <circle cx="20" cy="14" r="7" fill="none" stroke="#0c325c" stroke-width="2.3"/>
            <path d="M8 34c2.5-6 6.7-9 12-9s9.5 3 12 9"
                  fill="none" stroke="#0c325c" stroke-width="2.3" stroke-linecap="round"/>
          </svg>
        </div>

        <h1>Iniciar sesión</h1>

        <label class="field-label" for="control">Número de Control</label>
        <div class="field">
          <span class="field-icon">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M12 12a4 4 0 1 0-4-4 4 4 0 0 0 4 4Zm0 2c-4.4 0-8 2.2-8 5v1h16v-1c0-2.8-3.6-5-8-5Z"/>
            </svg>
          </span>
          <input id="control" type="text" placeholder="Número de control">
        </div>

        <label class="field-label" for="password">Contraseña</label>
        <div class="field">
          <span class="field-icon">
            <svg viewBox="0 0 24 24" aria-hidden="true">
              <path d="M17 10V7A5 5 0 0 0 7 7v3"/>
              <rect x="5" y="10" width="14" height="11" rx="2" ry="2"/>
            </svg>
          </span>
          <input id="password" type="password" placeholder="Contraseña">
        </div>

        <button class="btn-primary" type="submit">Iniciar Sesión</button>

        <a href="#" class="link-small">Olvidé mi contraseña</a>

        <div class="register">
          <span>Crear una cuenta</span>
          <a href="#">Regístrate</a>
        </div>

      </div>
    </main>

  </div>
</body>
</html>
