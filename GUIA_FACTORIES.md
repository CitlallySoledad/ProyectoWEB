# 🏭 Guía de Factories - Laravel

## 📦 Factories Creados

Ahora el proyecto tiene factories para **TODOS** los modelos principales:

```
database/factories/
├── UserFactory.php              ✅ YA EXISTÍA
├── TeamFactory.php              ✅ CREADO
├── EventFactory.php             ✅ CREADO
├── ProjectFactory.php           ✅ CREADO
├── RubricFactory.php            ✅ CREADO
├── RubricCriterionFactory.php   ✅ CREADO
├── EvaluationFactory.php        ✅ CREADO
└── EvaluationScoreFactory.php   ✅ CREADO
```

---

## 🎯 Uso Básico

### 1️⃣ Crear Instancia sin Guardar (make)
```php
// Solo crea el objeto en memoria, NO lo guarda en DB
$team = Team::factory()->make();
echo $team->name; // "Equipo Phoenix 42"
```

### 2️⃣ Crear y Guardar en DB (create)
```php
// Crea Y guarda en la base de datos
$team = Team::factory()->create();
// Ahora existe en la tabla teams con un ID
```

### 3️⃣ Crear Múltiples Registros
```php
// Crear 10 equipos de una vez
$teams = Team::factory()->count(10)->create();

// Crear 50 eventos
$events = Event::factory()->count(50)->create();
```

### 4️⃣ Sobrescribir Atributos
```php
// Crear equipo con nombre específico
$team = Team::factory()->create([
    'name' => 'Mi Equipo Personalizado',
]);

// Crear evento activo
$event = Event::factory()->create([
    'status' => 'activo',
    'title' => 'Hackathon Especial',
]);
```

---

## ✨ Estados y Helpers de Factories

### TeamFactory

```php
// Equipo con líder específico
$leader = User::factory()->create();
$team = Team::factory()->withLeader($leader)->create();

// Equipo con 4 miembros (incluyendo líder)
$team = Team::factory()->withMembers(4)->create();
// Automáticamente adjunta líder + 3 miembros adicionales con roles
```

### EventFactory

```php
// Evento activo
$event = Event::factory()->active()->create();
// start_date: hace 2 días, end_date: en 5 días, status: activo

// Evento próximo
$event = Event::factory()->upcoming()->create();
// start_date: en 10 días, status: próximo

// Evento finalizado
$event = Event::factory()->finished()->create();
// start_date: hace 30 días, status: finalizado
```

### ProjectFactory

```php
// Proyecto para equipo específico
$team = Team::factory()->create();
$project = Project::factory()->forTeam($team)->create();

// Proyecto para evento específico
$event = Event::factory()->create();
$project = Project::factory()->forEvent($event)->create();

// Proyecto público
$project = Project::factory()->public()->create();

// Proyecto privado
$project = Project::factory()->private()->create();

// Combinaciones
$project = Project::factory()
    ->forTeam($team)
    ->forEvent($event)
    ->public()
    ->create();
```

### RubricFactory

```php
// Rúbrica activa
$rubric = Rubric::factory()->active()->create();

// Rúbrica con 5 criterios predefinidos
$rubric = Rubric::factory()->withCriteria()->create();
// Automáticamente crea: Innovación, Funcionalidad, Diseño UX/UI, Código Limpio, Presentación
```

### RubricCriterionFactory

```php
// Criterio para rúbrica específica
$rubric = Rubric::factory()->create();
$criterion = RubricCriterion::factory()
    ->forRubric($rubric)
    ->create(['name' => 'Innovación']);
```

### EvaluationFactory

```php
// Evaluación pendiente (por defecto)
$evaluation = Evaluation::factory()->create();

// Evaluación completada con scores
$evaluation = Evaluation::factory()->completed()->create();
// creativity: 5-10, functionality: 5-10, status: completada

// Evaluación por juez específico
$judge = User::factory()->create()->assignRole('judge');
$evaluation = Evaluation::factory()->byJudge($judge)->create();
```

### EvaluationScoreFactory

```php
// Score normal
$score = EvaluationScore::factory()->create();

// Score alto (8-10)
$score = EvaluationScore::factory()->high()->create();

// Score bajo (0-5)
$score = EvaluationScore::factory()->low()->create();

// Score para evaluación específica
$evaluation = Evaluation::factory()->create();
$score = EvaluationScore::factory()
    ->forEvaluation($evaluation)
    ->create();
```

---

## 🧪 Ejemplos en Tests

### Test de Creación de Equipo

```php
it('creates a team with a leader', function () {
    $leader = User::factory()->create()->assignRole('student');
    $team = Team::factory()->withLeader($leader)->create();
    
    expect($team->leader_id)->toBe($leader->id);
});
```

### Test de Proyecto con Relaciones

```php
it('creates a project with all relations', function () {
    $team = Team::factory()->withMembers(3)->create();
    $event = Event::factory()->active()->create();
    $rubric = Rubric::factory()->withCriteria()->create();
    
    $project = Project::factory()->create([
        'team_id' => $team->id,
        'event_id' => $event->id,
        'rubric_id' => $rubric->id,
    ]);
    
    expect($project->team)->toBeInstanceOf(Team::class);
    expect($project->event)->toBeInstanceOf(Event::class);
    expect($project->rubric)->toBeInstanceOf(Rubric::class);
});
```

### Test de Evaluación Completa

```php
it('calculates total score from evaluation scores', function () {
    $rubric = Rubric::factory()->withCriteria()->create();
    $project = Project::factory()->create(['rubric_id' => $rubric->id]);
    $evaluation = Evaluation::factory()->create([
        'project_id' => $project->id,
        'rubric_id' => $rubric->id,
    ]);
    
    // Crear scores para cada criterio
    foreach ($rubric->criteria as $criterion) {
        EvaluationScore::factory()->create([
            'evaluation_id' => $evaluation->id,
            'rubric_criterion_id' => $criterion->id,
            'score' => 8,
        ]);
    }
    
    expect($evaluation->scores)->toHaveCount(5);
});
```

---

## 🚀 Uso en Seeders

### Antes (Sin Factories)

```php
// ❌ Código manual y repetitivo
$team1 = Team::create([
    'name' => 'Equipo Phoenix',
    'leader_id' => $student->id,
]);

$team2 = Team::create([
    'name' => 'Equipo Innovadores',
    'leader_id' => $student->id,
]);
```

### Ahora (Con Factories)

```php
// ✅ Conciso y flexible
$student = User::where('email', 'student@example.com')->first();

$teams = Team::factory()
    ->count(3)
    ->withLeader($student)
    ->withMembers(4)
    ->create();
```

---

## 📊 Ventajas de Usar Factories

| Ventaja | Descripción |
|---------|-------------|
| **Tests más rápidos** | `User::factory()->create()` en lugar de 10 líneas |
| **Datos realistas** | Usa Faker para generar datos variados |
| **Relaciones fáciles** | `Project::factory()->forTeam($team)` |
| **Estados reutilizables** | `Event::factory()->active()->create()` |
| **Seeders limpios** | Menos código repetitivo |
| **Pruebas masivas** | `Team::factory()->count(100)->create()` |

---

## 🔧 Comandos Útiles

```bash
# Probar factories en Tinker
php artisan tinker
>>> Team::factory()->make()
>>> Event::factory()->count(5)->create()

# Ejecutar script de prueba
php test_factories.php
```

---

## 📝 Datos Generados por Factories

### Team
- **name**: "Equipo [palabra] [número]" (ej: "Equipo Phoenix 42")
- **leader_id**: Usuario creado automáticamente

### Event
- **title**: Tipo de evento + año (ej: "Hackathon 2025")
- **description**: Párrafo de 3 frases
- **place**: Ubicación aleatoria (ej: "Auditorio Principal - Company Inc")
- **capacity**: Entre 50 y 200
- **status**: activo, próximo, o finalizado

### Project
- **name**: Combinación de términos (ej: "Sistema de Gestión Inteligente")
- **status**: pendiente, en_progreso, o completado
- **visibility**: publico o privado

### Rubric
- **name**: "Rúbrica de [tipo] [año]"
- **status**: activa o inactiva

### RubricCriterion
- **name**: Innovación, Funcionalidad, Diseño, etc.
- **weight**: Entre 10 y 30
- **min_score**: 0
- **max_score**: 10

---

**Fecha de creación:** 14 de diciembre de 2025  
**Estado:** ✅ Factories funcionando al 100%  
**Total de factories:** 8
