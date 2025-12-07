<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AssignJudgeRoleSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'judge@example.com';
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name' => 'Juez Demo',
                'password' => Hash::make('password123'),
            ]
        );

        if ($user->hasRole('judge')) {
            $this->command->info("El usuario '{$email}' ya tiene el rol 'judge'.");
            return;
        }

        $user->assignRole('judge');
        $this->command->info("Usuario juez creado/asignado: {$email} / password123");
    }
}
