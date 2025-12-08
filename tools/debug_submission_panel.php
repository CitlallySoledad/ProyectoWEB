<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Team;
use App\Models\Project;

echo "=== Diagnóstico del Panel de Submisión ===\n\n";

$user = User::find(3); // Liz SANJUAN

// Obtener equipos elegibles (como en el controlador)
$now = now()->startOfDay();
$eligibleTeams = Team::where('leader_id', $user->id)
    ->whereHas('events', function ($query) use ($now) {
        $query->where('status', '!=', 'borrador')
            ->where('start_date', '<=', $now)
            ->where(function($q) use ($now) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $now);
            });
    })
    ->withCount('members')
    ->with(['events' => function ($query) use ($now) {
        $query->where('status', '!=', 'borrador')
            ->where('start_date', '<=', $now)
            ->where(function($q) use ($now) {
                $q->whereNull('end_date')
                  ->orWhere('end_date', '>=', $now);
            });
    }])
    ->get();

echo "Equipos elegibles: " . $eligibleTeams->count() . "\n";
foreach ($eligibleTeams as $team) {
    echo "  • {$team->name} ({$team->members_count} miembros)\n";
}

// Buscar proyecto existente
$project = Project::whereIn('team_id', $eligibleTeams->pluck('id'))->first();

echo "\nProyecto existente: " . ($project ? $project->name : 'ninguno') . "\n";

if ($eligibleTeams->isEmpty()) {
    echo "\n❌ El input está DESHABILITADO porque \$eligibleTeams->isEmpty() = true\n";
} else {
    echo "\n✅ El input debería estar HABILITADO\n";
}

echo "\n=== Valores en la vista ===\n";
echo "eligibleTeams->isEmpty(): " . ($eligibleTeams->isEmpty() ? 'true' : 'false') . "\n";
echo "project: " . ($project ? 'objeto Project' : 'null') . "\n";
echo "project->name: " . ($project ? $project->name : 'n/a') . "\n";
