<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Event;
use Carbon\Carbon;

echo "🧪 PRUEBA DE ESTADOS AUTOMÁTICOS DE EVENTOS\n";
echo str_repeat("=", 60) . "\n\n";

// Crear eventos de prueba con diferentes fechas
$testEvents = [
    [
        'title' => 'Evento Futuro (Publicado)',
        'status' => 'publicado',
        'start_date' => Carbon::now()->addDays(10),
        'end_date' => Carbon::now()->addDays(12),
        'capacity' => 10,
    ],
    [
        'title' => 'Evento En Curso (Activo)',
        'status' => 'activo',
        'start_date' => Carbon::now()->subDays(2),
        'end_date' => Carbon::now()->addDays(3),
        'capacity' => 10,
    ],
    [
        'title' => 'Evento Pasado (Cerrado)',
        'status' => 'cerrado',
        'start_date' => Carbon::now()->subDays(10),
        'end_date' => Carbon::now()->subDays(5),
        'capacity' => 10,
    ],
    [
        'title' => 'Evento Borrador (No cambia)',
        'status' => 'borrador',
        'start_date' => Carbon::now()->subDays(5),
        'end_date' => Carbon::now()->addDays(5),
        'capacity' => 10,
    ],
];

echo "📝 Creando eventos de prueba...\n\n";

foreach ($testEvents as $data) {
    $event = Event::create($data);
    
    echo "Evento: {$event->title}\n";
    echo "  📅 Inicio: {$event->start_date->format('Y-m-d')}\n";
    echo "  📅 Fin: {$event->end_date->format('Y-m-d')}\n";
    echo "  💾 Estado en BD: {$event->getRawStatusAttribute()}\n";
    echo "  ✨ Estado calculado: {$event->status}\n";
    echo "  ✅ Acepta inscripciones: " . ($event->acceptsRegistrations() ? 'SÍ' : 'NO') . "\n";
    echo "\n";
}

echo str_repeat("=", 60) . "\n";
echo "✅ Prueba completada. Los estados se calculan automáticamente.\n";
echo "💡 Para actualizar estados en BD: php artisan events:update-statuses\n";
