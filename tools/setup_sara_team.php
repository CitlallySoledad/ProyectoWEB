<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Verificar configuración para Sara Lopez ===\n\n";

// Eventos activos
$now = now()->startOfDay();
$activeEvents = App\Models\Event::where('status', '!=', 'borrador')
    ->where('start_date', '<=', $now)
    ->where(function($q) use ($now) {
        $q->whereNull('end_date')
          ->orWhere('end_date', '>=', $now);
    })
    ->get(['id', 'name', 'start_date', 'end_date', 'status']);

echo "Eventos activos: {$activeEvents->count()}\n";
foreach ($activeEvents as $event) {
    echo "  - ID {$event->id}: {$event->name} (Status: {$event->status})\n";
}

// Equipo SR
$team = App\Models\Team::find(4);
echo "\n--- Equipo SR ---\n";
echo "ID: {$team->id}\n";
echo "Nombre: {$team->name}\n";
echo "Leader ID: {$team->leader_id}\n";

// Ver si está inscrito en eventos
$teamEvents = DB::table('event_team')->where('team_id', 4)->get();
echo "\nInscripciones del Equipo SR: {$teamEvents->count()}\n";
foreach ($teamEvents as $enrollment) {
    $event = App\Models\Event::find($enrollment->event_id);
    echo "  - Evento ID {$enrollment->event_id}: {$event->name}\n";
}

// Si no está inscrito, inscribirlo en el primer evento activo
if ($teamEvents->count() === 0 && $activeEvents->count() > 0) {
    $firstEvent = $activeEvents->first();
    DB::table('event_team')->insert([
        'event_id' => $firstEvent->id,
        'team_id' => 4,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "\n✅ Equipo SR inscrito en evento: {$firstEvent->name}\n";
}

echo "\n=== Listo para crear proyecto ===\n";
