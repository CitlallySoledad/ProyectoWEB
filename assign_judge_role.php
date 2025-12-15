<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;

$email = 'likagoru@gmail.com';

$user = User::where('email', $email)->first();

if ($user) {
    // Asignar rol judge
    if (!$user->hasRole('judge')) {
        $user->assignRole('judge');
        echo "✅ Rol 'judge' asignado correctamente a {$email}\n";
        echo "📋 Roles actuales: " . $user->roles->pluck('name')->join(', ') . "\n";
    } else {
        echo "⚠️  El usuario {$email} ya tiene el rol 'judge'\n";
        echo "📋 Roles actuales: " . $user->roles->pluck('name')->join(', ') . "\n";
    }
} else {
    echo "❌ Usuario con email {$email} no encontrado en la base de datos\n";
    echo "💡 Usuarios disponibles:\n";
    User::all()->each(function($u) {
        echo "   - {$u->email}\n";
    });
}
