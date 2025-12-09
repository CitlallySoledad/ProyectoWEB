<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Buscando proyecto de Sara Lopez ===\n\n";

$user = App\Models\User::where('email', 'likagoru@gmail.com')->first();

if (!$user) {
    echo "Usuario no encontrado\n";
    exit;
}

echo "Usuario ID: {$user->id}\n";
echo "Nombre: {$user->name}\n";
echo "Email: {$user->email}\n\n";

// Buscar equipos del usuario
$teams = $user->teams;
echo "Equipos del usuario: {$teams->count()}\n";

foreach ($teams as $team) {
    echo "\n--- Equipo ---\n";
    echo "Team ID: {$team->id}\n";
    echo "Team Name: {$team->name}\n";
    echo "Leader ID: {$team->leader_id}\n";
    
    // Buscar proyecto del equipo
    $project = $team->project;
    if ($project) {
        echo "\n--- Proyecto ---\n";
        echo "Project ID: {$project->id}\n";
        echo "Project Name: {$project->name}\n";
        echo "Status: {$project->status}\n";
        echo "Event ID: {$project->event_id}\n";
        echo "Rubric ID: {$project->rubric_id}\n";
        
        // Ver jueces asignados
        $judges = $project->judges;
        echo "\nJueces asignados: {$judges->count()}\n";
        foreach ($judges as $judge) {
            echo "  - Judge ID {$judge->id}: {$judge->name} ({$judge->email})\n";
        }
    } else {
        echo "\nNo hay proyecto para este equipo\n";
    }
}

// Buscar TODOS los proyectos para verificar
echo "\n\n=== TODOS LOS PROYECTOS ===\n";
$allProjects = App\Models\Project::with('team')->get();
foreach ($allProjects as $p) {
    echo "Project ID {$p->id}: {$p->name} - Team: {$p->team->name} (Team ID: {$p->team_id})\n";
}
