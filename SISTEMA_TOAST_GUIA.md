# 🎉 Sistema de Notificaciones Toast - Guía de Uso

## 📦 Componente Implementado

Se ha creado un sistema completo de notificaciones toast moderno y animado.

**Archivos creados:**
- `resources/views/components/toast-notification.blade.php` - Componente principal
- `public/js/toast-helpers.js` - Funciones auxiliares JavaScript

**Layouts actualizados:**
- ✅ `layouts/admin-panel.blade.php`
- ✅ `perfil.blade.php`
- ✅ `crearEquipo.blade.php`

---

## 🚀 Uso en Controladores (Laravel)

### Mensajes Flash
Los mensajes flash de Laravel se muestran automáticamente como toast:

```php
// Mensaje de éxito (verde)
return redirect()->route('panel.perfil')
    ->with('success', 'Perfil actualizado correctamente');

// Mensaje de error (rojo)
return redirect()->back()
    ->with('error', 'No se pudo guardar los cambios');

// Mensaje de advertencia (amarillo)
return redirect()->route('panel.participante')
    ->with('warning', 'Verifica tus datos antes de continuar');

// Mensaje informativo (azul)
return redirect()->route('panel.perfil')
    ->with('info', 'Tu sesión expirará en 5 minutos');
```

### Errores de Validación
Los errores de validación también se muestran automáticamente:

```php
// Se muestran automáticamente como toast rojos
$request->validate([
    'name' => 'required|min:3',
    'email' => 'required|email'
]);
```

---

## 💻 Uso en JavaScript

### Métodos Disponibles

```javascript
// Éxito (verde)
Toast.success('Operación completada exitosamente');

// Error (rojo)
Toast.error('Ocurrió un error al procesar la solicitud');

// Advertencia (amarillo)
Toast.warning('Por favor revisa los campos marcados');

// Información (azul)
Toast.info('Recuerda guardar los cambios');
```

### Con Duración Personalizada

```javascript
// Por defecto: success=3s, error=4s, warning=3.5s, info=3s
Toast.success('Guardado', 2000); // 2 segundos
Toast.error('Error grave', 6000); // 6 segundos
```

---

## 🎨 Características del Toast

### Diseño Moderno
- ✅ Animaciones suaves (slide-in/slide-out)
- ✅ Íconos de Bootstrap Icons
- ✅ Barra de progreso animada
- ✅ Botón de cerrar
- ✅ 4 tipos de mensajes con colores distintos
- ✅ Posicionado en la esquina superior derecha
- ✅ Apilamiento automático de múltiples toasts
- ✅ Auto-cierre con temporizador

### Colores y Estilos
- **Success** (verde #22c55e): Operaciones exitosas
- **Error** (rojo #ef4444): Errores y fallos
- **Warning** (amarillo #f59e0b): Advertencias
- **Info** (azul #3b82f6): Información general

---

## 📝 Ejemplos de Uso Completos

### Ejemplo 1: Crear Equipo
```php
// TeamController.php
public function store(StoreTeamRequest $request)
{
    $team = Team::create($request->validated());
    
    return redirect()->route('panel.lista-equipo')
        ->with('success', '¡Equipo "' . $team->name . '" creado exitosamente!');
}
```

### Ejemplo 2: Eliminar con Confirmación
```html
<!-- En la vista -->
<form method="POST" action="{{ route('admin.teams.destroy', $team->id) }}" 
      class="confirm-submit" 
      data-confirm-message="¿Eliminar el equipo {{ $team->name }}?">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn btn-danger">
        <i class="bi bi-trash"></i> Eliminar
    </button>
</form>
```

```php
// Controlador
public function destroy(Team $team)
{
    $teamName = $team->name;
    $team->delete();
    
    return redirect()->route('admin.teams.index')
        ->with('success', "Equipo '$teamName' eliminado correctamente");
}
```

### Ejemplo 3: Actualizar Perfil
```php
// ProfileController.php
public function updateDatos(UpdateProfileRequest $request)
{
    $user = $request->user();
    $user->update($request->validated());
    
    return redirect()->route('panel.perfil')
        ->with('success', 'Tus datos personales han sido actualizados');
}
```

### Ejemplo 4: Subir Foto con Error
```php
// ProfileController.php
public function updatePhoto(Request $request)
{
    try {
        // ... lógica de subida ...
        
        return redirect()->route('panel.perfil')
            ->with('success', 'Foto de perfil actualizada correctamente');
            
    } catch (\Exception $e) {
        return redirect()->route('panel.perfil')
            ->with('error', 'Error al subir la foto: ' . $e->getMessage());
    }
}
```

### Ejemplo 5: Invitar a Equipo
```php
// TeamController.php
public function sendInvitation(TeamInvitationRequest $request)
{
    $user = User::where('email', $request->email)->first();
    
    if (!$user) {
        return redirect()->back()
            ->with('error', 'El usuario con email ' . $request->email . ' no existe');
    }
    
    // ... lógica de invitación ...
    
    return redirect()->back()
        ->with('success', 'Invitación enviada a ' . $user->name);
}
```

---

## 🔧 Confirmaciones de Acciones Peligrosas

### Usando el Helper JavaScript

```html
<!-- Botón de eliminación con confirmación -->
<button data-confirm-delete 
        data-confirm-message="¿Eliminar este registro permanentemente?"
        class="btn btn-danger">
    Eliminar
</button>
```

### Usando JavaScript Personalizado

```javascript
document.getElementById('deleteBtn').addEventListener('click', async function() {
    const confirmed = await confirmDelete('¿Estás seguro?');
    if (confirmed) {
        // Ejecutar acción
        form.submit();
    }
    // Si no confirma, automáticamente muestra Toast.info('Operación cancelada')
});
```

---

## 🎯 Mejores Prácticas

### ✅ DO (Hacer)
```php
// Mensajes claros y específicos
->with('success', 'Equipo "Los Innovadores" creado correctamente')

// Incluir contexto relevante
->with('error', 'No se pudo eliminar. El equipo tiene proyectos activos')

// Usar el tipo correcto de mensaje
->with('warning', 'Completa tu perfil antes de unirte a un equipo')
```

### ❌ DON'T (Evitar)
```php
// Mensajes genéricos
->with('success', 'OK')

// Demasiado largos
->with('error', 'Se ha producido un error en el sistema al intentar procesar tu solicitud debido a que los datos ingresados no cumplen con los requisitos establecidos...')

// Usar tipo incorrecto
->with('info', 'Error crítico en el sistema') // Debería ser 'error'
```

---

## 📱 Responsive

El sistema de toast es completamente responsive:
- En desktop: Esquina superior derecha, ancho fijo 320px
- En móvil: Se adapta automáticamente manteniendo legibilidad
- Las animaciones funcionan en todos los dispositivos

---

## 🎨 Personalización de Estilos

Si necesitas ajustar los estilos, edita el componente:
`resources/views/components/toast-notification.blade.php`

Variables CSS principales:
```css
.toast-notification {
    background: white;          /* Fondo del toast */
    border-radius: 12px;        /* Redondeo */
    box-shadow: ...;            /* Sombra */
    animation-duration: 0.3s;   /* Velocidad de animación */
}
```

---

## 🔍 Debugging

Para verificar que funciona:

1. Abre la consola del navegador (F12)
2. Ejecuta en la consola:
```javascript
Toast.success('Prueba de toast exitoso');
Toast.error('Prueba de toast de error');
Toast.warning('Prueba de toast de advertencia');
Toast.info('Prueba de toast informativo');
```

---

## ✨ Próximas Mejoras Sugeridas

1. ⏱️ Toast con acciones (botón "Deshacer")
2. 📍 Posiciones alternativas (top-left, bottom-right, etc.)
3. 🎵 Sonidos de notificación opcionales
4. 📊 Toasts con gráficos o iconos personalizados
5. 🌙 Modo oscuro automático

---

**Creado:** 14 de diciembre de 2025  
**Versión:** 1.0  
**Estado:** ✅ Implementado y listo para usar
