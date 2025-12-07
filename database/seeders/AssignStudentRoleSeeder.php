<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class AssignStudentRoleSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('name', 'Fernando Robles')->first();

        if (! $user) {
            $this->command->error("Usuario 'Fernando Robles' no encontrado. Comprueba el nombre o verifica en la tabla users.");
            return;
        }

        if ($user->hasRole('student')) {
            $this->command->info("El usuario ya tiene el rol 'student'.");
            return;
        }

        $user->assignRole('student');
        $this->command->info("Rol 'student' asignado al usuario: {$user->email} ({$user->name}).");
    }
}
