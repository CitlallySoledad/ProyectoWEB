<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Team;
use App\Models\User;
use App\Models\TeamJoinRequest;

// Obtener un equipo
$team = Team::first();
if (!$team) {
    die("No hay equipos en la base de datos\n");
}

// Obtener un usuario que no sea el líder
$user = User::where('id', '!=', $team->leader_id)->first();
if (!$user) {
    die("No hay usuarios disponibles\n");
}

// Crear solicitud de prueba
$request = TeamJoinRequest::create([
    'team_id' => $team->id,
    'user_id' => $user->id,
    'role' => 'Back',
    'status' => 'pending'
]);

echo "✅ Solicitud creada exitosamente\n";
echo "Team: {$team->name}\n";
echo "User: {$user->name}\n";
echo "Role: Back\n";
echo "Status: pending\n";
