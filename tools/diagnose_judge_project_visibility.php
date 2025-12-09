<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Diagnóstico: Por qué el juez no ve el proyecto ===\n\n";

// Información del juez
$judge = App\Models\User::where('email', 'jdkdnhejnd@gmail.com')->first();
echo "--- Juez ---\n";
echo "ID: {$judge->id}\n";
echo "Nombre: {$judge->name}\n";
echo "Email: {$judge->email}\n\n";

// Proyecto de Sara Lopez
$project = App\Models\Project::with(['team', 'event'])->where('name', 'Reverso')->first();
echo "--- Proyecto ---\n";
echo "ID: {$project->id}\n";
echo "Nombre: {$project->name}\n";
echo "Team ID: {$project->team_id}\n";
echo "Team Name: {$project->team->name}\n";
echo "Event ID: {$project->event_id}\n";
echo "Event Name: {$project->event->title}\n";
echo "Status: {$project->status}\n";
echo "Rubric ID: " . ($project->rubric_id ?? 'NULL') . "\n\n";

// Verificar si el proyecto tiene jueces asignados
echo "--- Jueces asignados al proyecto (tabla project_judge) ---\n";
$assignedJudges = DB::table('project_judge')->where('project_id', $project->id)->get();
echo "Total asignados: {$assignedJudges->count()}\n";
foreach ($assignedJudges as $assignment) {
    $j = App\Models\User::find($assignment->user_id);
    echo "  - Judge ID {$assignment->user_id}: {$j->name} ({$j->email})\n";
}

// Verificar si el juez está en la lista
$isAssigned = $assignedJudges->where('user_id', $judge->id)->count() > 0;
echo "\n¿El juez Lucia está asignado al proyecto? " . ($isAssigned ? "SÍ" : "NO") . "\n\n";

// Verificar jueces del evento
echo "--- Jueces asignados al evento (judge_ids en evento) ---\n";
$event = $project->event;
$judgeIds = $event->judge_ids ?? [];
echo "Judge IDs del evento: " . json_encode($judgeIds) . "\n";
echo "¿Lucia está en judge_ids del evento? " . (in_array((string)$judge->id, $judgeIds) || in_array($judge->id, $judgeIds) ? "SÍ" : "NO") . "\n\n";

// Verificar rúbrica
echo "--- Rúbrica ---\n";
if ($project->rubric_id) {
    $rubric = App\Models\Rubric::find($project->rubric_id);
    if ($rubric) {
        echo "Rubric ID: {$rubric->id}\n";
        echo "Rubric Name: {$rubric->name}\n";
    } else {
        echo "ERROR: rubric_id está configurado pero no existe en la base de datos\n";
    }
} else {
    echo "ERROR: El proyecto NO tiene rúbrica asignada (rubric_id es NULL)\n";
}

echo "\n--- Solución ---\n";
if (!$isAssigned) {
    echo "❌ El proyecto NO está asignado al juez en project_judge\n";
    echo "   Ejecuta: DB::table('project_judge')->insert(['project_id' => {$project->id}, 'user_id' => {$judge->id}]);\n\n";
}
if (!$project->rubric_id) {
    echo "❌ El proyecto NO tiene rúbrica asignada\n";
    echo "   Necesitas asignar una rúbrica al proyecto\n\n";
}
if ($isAssigned && $project->rubric_id) {
    echo "✅ Todo está configurado correctamente. El proyecto debería aparecer.\n";
}
