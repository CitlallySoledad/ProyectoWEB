# 📋 Validaciones en Form Requests - Proyecto Hackathon

## ✅ Estado de Implementación

### Form Requests Existentes (14 archivos)

#### 1. **UpdateProfileRequest** ✅ MEJORADO
**Ubicación:** `app/Http/Requests/UpdateProfileRequest.php`

**Validaciones:**
- `name`: Obligatorio, 3-255 caracteres, solo letras y espacios
- `email`: Obligatorio, formato RFC/DNS válido, máx 255 caracteres
- `curp`: Opcional, exactamente 18 caracteres, formato CURP válido (mayúsculas)
- `fecha_nacimiento`: Opcional, fecha válida, usuario mayor de 15 años
- `genero`: Opcional, valores permitidos: Masculino, Femenino, Otro
- `estado_civil`: Opcional, valores: Soltero/a, Casado/a, Divorciado/a, Viudo/a, Unión libre
- `telefono`: Opcional, exactamente 10 dígitos numéricos
- `profesion`: Opcional, máx 255 caracteres

**Mensajes:** ✅ Completos en español

---

#### 2. **RegistroRequest** ✅ COMPLETO
**Ubicación:** `app/Http/Requests/RegistroRequest.php`

**Validaciones:**
- `control`: Obligatorio, alfanumérico
- `nombre`: Obligatorio, solo letras (con acentos y ñ)
- `ap_paterno`: Obligatorio, solo letras
- `ap_materno`: Obligatorio, solo letras
- `email`: Obligatorio, RFC/DNS válido, único en la tabla users
- `password`: Obligatorio, mínimo 6 caracteres, confirmación requerida, debe contener letras y números
- `telefono`: Obligatorio, solo números
- `carrera`: Obligatorio, debe ser una de las carreras válidas del ITSPA
- `role`: Obligatorio, solo acepta "student"

**Mensajes:** ✅ Completos en español

---

#### 3. **StoreTeamRequest** ✅ MEJORADO
**Ubicación:** `app/Http/Requests/StoreTeamRequest.php`

**Validaciones:**
- `team_name`: Obligatorio, 3-100 caracteres, solo letras, números y espacios

**Mensajes:** ✅ Agregados en español

---

#### 4. **TeamInvitationRequest** ✅ MEJORADO
**Ubicación:** `app/Http/Requests/TeamInvitationRequest.php`

**Validaciones:**
- `email`: Obligatorio, RFC/DNS válido, máx 255 caracteres, debe existir en users
- `role`: Obligatorio, valores: Back, Front, Diseñador
- `team_id`: Obligatorio, debe existir en teams

**Mensajes:** ✅ Completos en español

---

#### 5. **JoinTeamRequest** ✅ COMPLETO
**Ubicación:** `app/Http/Requests/JoinTeamRequest.php`

**Validaciones:**
- `role`: Obligatorio, valores: Back, Front, Diseñador

**Mensajes:** ✅ Completos en español

---

#### 6. **StoreEventRequest** ✅ COMPLETO
**Ubicación:** `app/Http/Requests/StoreEventRequest.php`

**Validaciones:**
- `title`: Obligatorio, string, máx 255 caracteres
- `description`: Obligatorio, string
- `place`: Obligatorio, string, máx 255 caracteres
- `capacity`: Obligatorio, entero, mínimo 1
- `start_date`: Obligatorio, fecha válida
- `end_date`: Obligatorio, fecha válida, debe ser >= start_date
- `status`: Obligatorio, string, máx 50 caracteres
- `category`: Obligatorio, string, máx 255 caracteres
- `judge_ids`: Opcional, array de IDs válidos de users
- `rubric_ids`: Opcional, array de IDs válidos de rubrics

**Mensajes:** ✅ Completos en español

---

#### 7. **StoreUserRequest** ✅ MEJORADO
**Ubicación:** `app/Http/Requests/StoreUserRequest.php`

**Validaciones:**
- `name`: Obligatorio, 3-255 caracteres, solo letras y espacios
- `email`: Obligatorio, RFC/DNS válido, único, máx 255 caracteres
- `password`: Obligatorio, mínimo 8 caracteres, confirmación requerida, debe contener letras y números
- `role`: Obligatorio, debe existir en tabla roles

**Mensajes:** ✅ Completos en español

---

#### 8. **UpdateEventRequest** ⚠️ REVISAR
**Ubicación:** `app/Http/Requests/UpdateEventRequest.php`
**Estado:** Por revisar (mismas reglas que StoreEventRequest)

---

#### 9. **StoreAdminTeamRequest** ⚠️ REVISAR
**Ubicación:** `app/Http/Requests/StoreAdminTeamRequest.php`
**Estado:** Por revisar

---

#### 10. **UpdateAdminTeamRequest** ⚠️ REVISAR
**Ubicación:** `app/Http/Requests/UpdateAdminTeamRequest.php`
**Estado:** Por revisar

---

#### 11. **StoreRubricRequest** ⚠️ REVISAR
**Ubicación:** `app/Http/Requests/StoreRubricRequest.php`
**Estado:** Por revisar

---

#### 12. **UpdateRubricRequest** ⚠️ REVISAR
**Ubicación:** `app/Http/Requests/UpdateRubricRequest.php`
**Estado:** Por revisar

---

#### 13. **StoreEvaluationScoresRequest** ⚠️ REVISAR
**Ubicación:** `app/Http/Requests/StoreEvaluationScoresRequest.php`
**Estado:** Por revisar

---

#### 14. **ProfileUpdateRequest** ⚠️ DUPLICADO?
**Ubicación:** `app/Http/Requests/ProfileUpdateRequest.php`
**Estado:** Posible duplicado de UpdateProfileRequest - revisar

---

## 🎯 Resumen de Validaciones Implementadas

### ✅ Validaciones Completadas (7/14)
1. UpdateProfileRequest - ✅ Mejorado
2. RegistroRequest - ✅ Completo
3. StoreTeamRequest - ✅ Mejorado
4. TeamInvitationRequest - ✅ Mejorado
5. JoinTeamRequest - ✅ Completo
6. StoreEventRequest - ✅ Completo
7. StoreUserRequest - ✅ Mejorado

### ⚠️ Pendientes de Revisión (7/14)
- UpdateEventRequest
- StoreAdminTeamRequest
- UpdateAdminTeamRequest
- StoreRubricRequest
- UpdateRubricRequest
- StoreEvaluationScoresRequest
- ProfileUpdateRequest (revisar si es duplicado)

---

## 📊 Tipos de Validaciones Usadas

### 1. **Validaciones de String**
- `required` - Campo obligatorio
- `string` - Debe ser texto
- `min:N` - Longitud mínima
- `max:N` - Longitud máxima
- `size:N` - Longitud exacta
- `regex:/pattern/` - Patrón personalizado

### 2. **Validaciones de Email**
- `email:rfc,dns` - Email válido con verificación RFC y DNS
- `unique:table,column` - Email único en la base de datos

### 3. **Validaciones de Números**
- `integer` - Número entero
- `min:N` - Valor mínimo
- `numeric` - Valor numérico

### 4. **Validaciones de Fechas**
- `date` - Fecha válida
- `before:date` - Antes de una fecha
- `after:date` - Después de una fecha
- `after_or_equal:field` - Después o igual a otro campo

### 5. **Validaciones de Selección**
- `in:val1,val2` - Debe ser uno de los valores listados
- `exists:table,column` - Debe existir en la tabla

### 6. **Validaciones de Password**
- `confirmed` - Debe tener campo _confirmation
- `regex:/pattern/` - Patrón de seguridad

### 7. **Validaciones de Arrays**
- `array` - Debe ser un array
- `*.exists:table,id` - Cada elemento debe existir

---

## 🔍 Patrones Regex Utilizados

### 1. **Solo Letras (con acentos y ñ)**
```php
'regex:/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/'
```

### 2. **Alfanumérico**
```php
'regex:/^[A-Za-z0-9]+$/'
```

### 3. **Solo Números**
```php
'regex:/^[0-9]+$/'
```

### 4. **CURP Válido**
```php
'regex:/^[A-Z]{4}[0-9]{6}[HM][A-Z]{5}[0-9A-Z][0-9]$/'
```

### 5. **Teléfono (10 dígitos)**
```php
'regex:/^[0-9]{10}$/'
```

### 6. **Password con letras y números**
```php
'regex:/^(?=.*[A-Za-z])(?=.*\d).+$/'
```

---

## 📝 Mensajes Personalizados en Español

Todos los FormRequests implementados incluyen mensajes personalizados en español para:
- Campos requeridos
- Formatos inválidos
- Longitudes mínimas/máximas
- Valores no permitidos
- Unicidad de campos
- Coincidencia de contraseñas

**Ejemplo:**
```php
public function messages(): array
{
    return [
        'name.required' => 'El nombre es obligatorio.',
        'email.email' => 'Debe ingresar un correo electrónico válido.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
    ];
}
```

---

## 🚀 Mejoras Implementadas

### 1. **Validación de Email Mejorada**
- Cambio de `email` a `email:rfc,dns` para validación más estricta
- Verificación de formato RFC y DNS

### 2. **Validación de Contraseñas Robusta**
- Mínimo 8 caracteres
- Debe contener letras y números
- Confirmación requerida

### 3. **Validación de Nombres con Acentos**
- Regex que acepta caracteres latinos (á, é, í, ó, ú, ñ)
- Soporte para nombres en español

### 4. **Validación de CURP**
- Formato completo de CURP mexicano
- 18 caracteres obligatorios
- Validación de estructura

### 5. **Validación de Edad**
- `before:-15 years` para verificar mayoría de edad
- Fecha de nacimiento válida

### 6. **Validación de Teléfono**
- Exactamente 10 dígitos
- Solo números

---

## 📌 Próximos Pasos

1. ✅ Revisar FormRequests pendientes
2. ✅ Eliminar duplicados (ProfileUpdateRequest vs UpdateProfileRequest)
3. ✅ Agregar validaciones a formularios de Admin
4. ✅ Agregar validaciones a Rubrics y Evaluaciones
5. ✅ Documentar reglas de negocio específicas

---

**Última actualización:** 14 de diciembre de 2025
**Estado general:** 50% completado (7/14 FormRequests validados)
