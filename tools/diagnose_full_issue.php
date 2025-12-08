<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Team;
use App\Models\Event;

echo "=== Diagnóstico Completo ===\n\n";

// Usuario autenticado (simulando sesión)
$user = User::find(3);
echo "Usuario: {$user->name} (ID: {$user->id})\n\n";

// 1. Equipos donde es líder
echo "1️⃣ EQUIPOS DONDE ES LÍDER:\n";
$leaderTeams = Team::where('leader_id', $user->id)->get();
echo "   Total: {$leaderTeams->count()}\n";
foreach ($leaderTeams as $team) {
    echo "   • {$team->name} (ID: {$team->id})\n";
}

// 2. Eventos activos
echo "\n2️⃣ EVENTOS ACTIVOS:\n";
$activeEvents = Event::all();
foreach ($activeEvents as $event) {
    $status = $event->status; // Usa el accessor
    echo "   • {$event->name} (ID: {$event->id})\n";
    echo "     - Status en BD: {$event->attributes['status']}\n";
    echo "     - Status calculado: {$status}\n";
    echo "     - Start: {$event->start_date}\n";
    echo "     - End: {$event->end_date}\n";
    echo "     - Activo?: " . ($status === 'activo' ? '✅ SÍ' : '❌ NO') . "\n";
}

// 3. Relación equipo-evento
echo "\n3️⃣ EQUIPOS INSCRITOS EN EVENTOS:\n";
foreach ($leaderTeams as $team) {
    $events = $team->events;
    echo "   Equipo: {$team->name}\n";
    if ($events->isEmpty()) {
        echo "     ❌ No está inscrito en ningún evento\n";
    } else {
        foreach ($events as $event) {
            $status = $event->status;
            echo "     • Evento: {$event->name} (Status: {$status})\n";
        }
    }
}

// 4. La consulta exacta del controlador
echo "\n4️⃣ CONSULTA DEL CONTROLADOR (equipos elegibles):\n";
$eligibleTeams = Team::where('leader_id', $user->id)
    ->whereHas('events', function ($query) {
        $query->where('status', 'activo');
    })
    ->withCount('members')
    ->with(['events' => function ($query) {
        $query->where('status', 'activo');
    }])
    ->get();

echo "   Equipos elegibles encontrados: {$eligibleTeams->count()}\n";
foreach ($eligibleTeams as $team) {
    echo "   ✅ {$team->name} ({$team->members_count} miembros)\n";
    foreach ($team->events as $event) {
        echo "      - Evento: {$event->name}\n";
    }
}

if ($eligibleTeams->isEmpty()) {
    echo "\n❌ PROBLEMA DETECTADO:\n";
    echo "La consulta whereHas('events', fn(\$q) => \$q->where('status', 'activo'))\n";
    echo "está buscando en la COLUMNA 'status' de la tabla 'events'.\n";
    echo "Pero 'status' es un ACCESSOR (calculado), no una columna real.\n";
}
