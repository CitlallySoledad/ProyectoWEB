<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateJudgeLizSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'lizsanjuanvasquez@gmail.com';
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Liz Sanjuan Vasquez',
                'password' => Hash::make('password123'),
            ]
        );

        if ($user->hasRole('judge')) {
            $this->command->info("El usuario '{$email}' ya tiene el rol 'judge'.");
            return;
        }

        $user->assignRole('judge');
        $this->command->info("Juez creado/asignado exitosamente:");
        $this->command->info("Email: {$email}");
        $this->command->info("Contraseña: password123");
        $this->command->info("Rol: judge");
    }
}
