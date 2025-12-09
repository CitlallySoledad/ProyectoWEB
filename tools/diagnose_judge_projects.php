<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Diagnóstico de Proyectos para Jueces ===\n\n";

$judgeId = 2; // Juez Demo

echo "Juez ID: $judgeId\n";
$judge = App\Models\User::find($judgeId);
echo "Nombre: " . $judge->name . "\n";
echo "Email: " . $judge->email . "\n\n";

echo "--- Proyectos asignados al juez (según project_judge) ---\n";
$projectIds = DB::table('project_judge')
    ->where('user_id', $judgeId)
    ->pluck('project_id')
    ->toArray();
echo "IDs: " . implode(', ', $projectIds) . "\n";
echo "Total: " . count($projectIds) . "\n\n";

echo "--- Verificar con whereHas('judges') ---\n";
$projectsWithWhereHas = App\Models\Project::whereHas('judges', function($q) use ($judgeId) {
    $q->where('users.id', $judgeId);
})->get(['id', 'name']);

echo "Proyectos encontrados con whereHas: " . $projectsWithWhereHas->count() . "\n";
foreach ($projectsWithWhereHas as $p) {
    echo "  - ID {$p->id}: {$p->name}\n";
}
echo "\n";

echo "--- Verificar filtro de evaluaciones ---\n";
$projectsWithoutEvaluations = App\Models\Project::whereHas('judges', function($q) use ($judgeId) {
    $q->where('users.id', $judgeId);
})
->whereDoesntHave('evaluations', function ($qb) use ($judgeId) {
    $qb->where('judge_id', $judgeId);
})
->get(['id', 'name']);

echo "Proyectos sin evaluar por este juez: " . $projectsWithoutEvaluations->count() . "\n";
foreach ($projectsWithoutEvaluations as $p) {
    echo "  - ID {$p->id}: {$p->name}\n";
}
echo "\n";

echo "--- Verificar evaluaciones del juez ---\n";
$evaluations = DB::table('evaluations')
    ->where('judge_id', $judgeId)
    ->get(['id', 'project_id', 'status']);
echo "Evaluaciones del juez: " . $evaluations->count() . "\n";
foreach ($evaluations as $e) {
    echo "  - Eval ID {$e->id}: Project {$e->project_id} - Status: {$e->status}\n";
}

echo "\n=== Fin del Diagnóstico ===\n";
