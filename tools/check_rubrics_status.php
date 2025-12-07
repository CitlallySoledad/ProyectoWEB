<?php
// Script para verificar el estado actual de rúbricas y proyectos

require __DIR__ . '/../bootstrap/autoload.php';
$app = require __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
);

use App\Models\Rubric;
use App\Models\Project;
use App\Models\User;

// Obtener el usuario juez actual (asumiendo ID 1 o el primero que sea juez)
$judge = User::where('role', 'judge')->first();

if (!$judge) {
    echo json_encode(['error' => 'No judge user found']);
    exit;
}

echo "=== JUDGE USER ===\n";
echo "ID: " . $judge->id . "\n";
echo "Name: " . $judge->name . "\n";
echo "Email: " . $judge->email . "\n\n";

// Obtener rúbricas activas e inactivas
$activeRubrics = Rubric::where('status', 'activa')->get();
$inactiveRubrics = Rubric::where('status', 'inactiva')->get();

echo "=== RUBRICS (ACTIVAS) ===\n";
foreach ($activeRubrics as $rubric) {
    echo "ID: " . $rubric->id . " | Name: " . $rubric->name . " | Status: " . $rubric->status . " | Criteria: " . $rubric->criteria->count() . "\n";
}

echo "\n=== RUBRICS (INACTIVAS) ===\n";
foreach ($inactiveRubrics as $rubric) {
    echo "ID: " . $rubric->id . " | Name: " . $rubric->name . " | Status: " . $rubric->status . " | Criteria: " . $rubric->criteria->count() . "\n";
}

// Obtener proyectos asignados a este juez
$projects = Project::whereHas('judges', function ($q) use ($judge) {
    $q->where('users.id', $judge->id);
})->get();

echo "\n=== PROJECTS ASSIGNED TO JUDGE ===\n";
foreach ($projects as $project) {
    echo "ID: " . $project->id . " | Name: " . $project->name . " | Rubric ID: " . ($project->rubric_id ?? 'NULL') . " | Rubric Name: " . ($project->rubric?->name ?? 'NONE') . "\n";
}

echo "\n=== SUMMARY ===\n";
echo "Total active rubrics: " . $activeRubrics->count() . "\n";
echo "Total inactive rubrics: " . $inactiveRubrics->count() . "\n";
echo "Projects assigned to judge: " . $projects->count() . "\n";
echo "Projects with rubric assigned: " . $projects->where('rubric_id', '!=', null)->count() . "\n";
