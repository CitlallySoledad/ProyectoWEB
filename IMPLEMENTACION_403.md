# 🔧 Manejo de Errores 403 - Implementación Completada

## ✅ Cambios Realizados

### 1. **Handler de Excepciones** (`bootstrap/app.php`)
Se agregó manejo personalizado para:
- ✅ **Errores 403** (Forbidden) - Middleware de roles
- ✅ **AuthorizationException** - FormRequests y Policies
- ✅ **Errores 404** (Not Found) - Bonus

**Comportamiento nuevo:**
- En lugar de mostrar página de error 403, **redirige al usuario** con mensaje flash
- Soporte para peticiones AJAX/JSON
- Mensajes claros y específicos

---

### 2. **Componente de Mensajes Flash** (`resources/views/components/flash-message.blade.php`)
Componente reutilizable para mostrar mensajes de sesión:
- ✅ `success` - Mensajes de éxito (verde)
- ✅ `error` - Mensajes de error (rojo)
- ✅ `warning` - Advertencias (amarillo)
- ✅ `info` - Información (azul)
- ✅ Errores de validación automáticos

---

## 📖 Cómo Usar

### **En tus vistas Blade:**

Agrega esto donde quieras mostrar los mensajes (típicamente después del `<body>` o dentro del contenedor principal):

```blade
{{-- Incluir mensajes flash --}}
<x-flash-message />
```

O la forma tradicional:
```blade
@include('components.flash-message')
```

### **Ejemplo en un layout:**

```blade
@extends('layouts.admin-panel')

@section('content')
    <div class="container">
        {{-- Los mensajes aparecerán aquí --}}
        <x-flash-message />
        
        {{-- Tu contenido --}}
        <h1>Panel de Administración</h1>
        ...
    </div>
@endsection
```

---

## 🎯 Escenarios Cubiertos

### **Escenario 1: Usuario sin permisos intenta acceder a /admin**
**Antes:**
```
403 Forbidden - Página de error blanca
```

**Después:**
```
Redirección automática ← + Flash message rojo:
"No tienes permisos para acceder a esta sección."
```

---

### **Escenario 2: Estudiante intenta acceder a ruta de juez**
**Antes:**
```
403 Forbidden
```

**Después:**
```
Redirección + Flash message:
"No tienes permisos para acceder a esta sección."
```

---

### **Escenario 3: FormRequest con authorize() = false**
**Antes:**
```
403 This action is unauthorized.
```

**Después:**
```
Redirección + Flash message personalizado:
"No tienes permisos para realizar esta acción."
```

Puedes personalizar el mensaje en tu FormRequest:

```php
protected function failedAuthorization()
{
    throw new \Illuminate\Auth\Access\AuthorizationException(
        'Solo el líder del equipo puede realizar esta acción.'
    );
}
```

---

## 🚀 Próximos Pasos (Opcional)

### **1. Agregar el componente a tus layouts principales:**

**Admin Panel** (`resources/views/layouts/admin-panel.blade.php`):
```blade
<!-- Agregar después de abrir el contenido principal -->
<div class="panel-content">
    <x-flash-message />
    @yield('content')
</div>
```

**Judge Panel** (`resources/views/layouts/judge-panel.blade.php`):
```blade
<!-- Agregar en la sección de contenido -->
<main class="main-content">
    <x-flash-message />
    @yield('content')
</main>
```

**Student/Panel** (en tus vistas principales):
```blade
@extends('layouts.app')

@section('content')
    <x-flash-message />
    <!-- resto del contenido -->
@endsection
```

---

### **2. Personalizar mensajes por rol (opcional)**

Si quieres mensajes más específicos según el rol, crea un middleware personalizado:

**Crear:** `app/Http/Middleware/CheckRole.php`
```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!$request->user()) {
            return redirect()->route('login')
                ->with('error', 'Debes iniciar sesión para continuar.');
        }

        if (!$request->user()->hasRole($role)) {
            $messages = [
                'admin' => 'Esta sección es exclusiva para administradores.',
                'judge' => 'Esta sección es exclusiva para jueces evaluadores.',
                'student' => 'Esta sección es exclusiva para estudiantes.',
            ];

            return redirect()->back()
                ->with('error', $messages[$role] ?? 'No tienes permisos suficientes.');
        }

        return $next($request);
    }
}
```

**Registrar en** `bootstrap/app.php`:
```php
->withMiddleware(function (Middleware $middleware): void {
    $middleware->alias([
        // ... otros middleware
        'check.role' => \App\Http\Middleware\CheckRole::class,
    ]);
})
```

**Usar en rutas** (`routes/web.php`):
```php
// Cambiar de:
Route::middleware(['auth', 'role:admin'])->group(function () {

// A:
Route::middleware(['auth', 'check.role:admin'])->group(function () {
```

---

## 🧪 Probar la Implementación

1. **Cerrar sesión**
2. **Iniciar sesión como estudiante**
3. **Intentar acceder a:** `http://tuapp.test/admin`
4. **Resultado esperado:** Redirección + mensaje "No tienes permisos..."

---

## 📝 Notas Importantes

- ✅ Los mensajes se muestran **una sola vez** (flash messages)
- ✅ Compatible con **Bootstrap 5** (usa clases de alert)
- ✅ Incluye **iconos de Bootstrap Icons**
- ✅ Los mensajes se **cierran automáticamente** con el botón X
- ✅ Funciona con **peticiones AJAX** (retorna JSON)

---

## 🎨 Personalización de Estilos

Si quieres estilos personalizados, puedes agregar CSS:

```css
/* En tu archivo CSS principal */
.alert {
    margin-bottom: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.alert-success {
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border: none;
}

.alert-danger {
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: white;
    border: none;
}
```

---

## ✨ Conclusión

Ahora tu aplicación:
- ✅ **No muestra errores 403 crudos**
- ✅ **Redirige con mensajes claros**
- ✅ **Mejora la experiencia del usuario**
- ✅ **Funciona con AJAX/JSON**
- ✅ **Es fácil de mantener**

**¡Implementación completada!** 🎉
