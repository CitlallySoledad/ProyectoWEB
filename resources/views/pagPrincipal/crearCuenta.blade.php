<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Crear cuenta</title>
  <link rel="stylesheet" href="crearCuenta.css">
</head>
<body>
  <div class="registro-page">
    <!-- COLUMNA MORADA CON AVATARES -->
    <aside class="registro-aside">
      <!-- Usa tu imagen local -->
      <img src="img/avatar.png" alt="Avatar" class="avatar">
      <img src="img/avatar.png" alt="Avatar" class="avatar">
      <img src="img/avatar.png" alt="Avatar" class="avatar">
      <img src="img/avatar.png" alt="Avatar" class="avatar">
      <img src="img/avatar.png" alt="Avatar" class="avatar">
    </aside>

    <!-- COLUMNA AZUL CON FORMULARIO -->
    <main class="registro-main">
      <form class="form-card">
        <h1>Crear cuenta</h1>

        <div class="form-group">
          <label for="control">Número de control</label>
          <input type="text" id="control" placeholder="Número de control">
        </div>

        <div class="form-group">
          <label for="nombre">Nombre</label>
          <input type="text" id="nombre" placeholder="Nombre">
        </div>

        <div class="form-group">
          <label for="ap-pat">Apellido Paterno</label>
          <input type="text" id="ap-pat" placeholder="Apellido Paterno">
        </div>

        <div class="form-group">
          <label for="ap-mat">Apellido Materno</label>
          <input type="text" id="ap-mat" placeholder="Apellido Materno">
        </div>

        <div class="form-group">
          <label for="pass">Contraseña</label>
          <input type="password" id="pass" placeholder="Contraseña">
        </div>

        <div class="form-group">
          <label for="pass2">Confirmar contraseña</label>
          <input type="password" id="pass2" placeholder="Confirmar contraseña">
        </div>

        <div class="form-group">
          <label for="correo">Ingresa tu correo electronico</label>
          <input type="email" id="correo" placeholder="Correo electronico">
        </div>

        <div class="form-group">
          <label for="tel">Ingresa tu telefono</label>
          <input type="tel" id="tel" placeholder="Telefono">
        </div>

        <div class="form-group">
          <label for="carrera">Selecciona tu carrera</label>
          <select id="carrera">
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
</body>
</html>