<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Arreglando proyecto Reverso ===\n\n";

$project = App\Models\Project::with('event')->where('name', 'Reverso')->first();

if (!$project) {
    echo "ERROR: Proyecto no encontrado\n";
    exit;
}

echo "Proyecto: {$project->name}\n";
echo "Event: {$project->event->title}\n";
echo "Status: {$project->status}\n\n";

// Asignar rúbrica del evento
$event = $project->event;
$rubricIds = $event->rubric_ids ?? [];

if (empty($rubricIds)) {
    echo "ERROR: El evento no tiene rúbricas asignadas\n";
    exit;
}

$rubricId = is_array($rubricIds) ? $rubricIds[0] : $rubricIds;
echo "Asignando rúbrica ID: {$rubricId}\n";
$project->update(['rubric_id' => $rubricId]);

// Asignar jueces del evento
$judgeIds = $event->judge_ids ?? [];

if (empty($judgeIds)) {
    echo "ERROR: El evento no tiene jueces asignados\n";
    exit;
}

echo "Jueces del evento: " . implode(', ', $judgeIds) . "\n\n";

foreach ($judgeIds as $judgeId) {
    $judge = App\Models\User::find($judgeId);
    
    $exists = DB::table('project_judge')
        ->where('project_id', $project->id)
        ->where('user_id', $judgeId)
        ->exists();
    
    if (!$exists) {
        DB::table('project_judge')->insert([
            'project_id' => $project->id,
            'user_id' => $judgeId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        echo "✅ Asignado juez: {$judge->name} ({$judge->email})\n";
    } else {
        echo "⏭️  Ya asignado: {$judge->name} ({$judge->email})\n";
    }
}

echo "\n✅ Proyecto arreglado. Ahora los jueces deberían verlo.\n";
