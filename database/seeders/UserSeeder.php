<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Crear usuarios de ejemplo para diferentes roles.
     * Este seeder centraliza la creación de usuarios demo.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Administrador Principal',
                'email' => 'admin@admin.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_admin' => true,
            ],
            [
                'name' => 'Juez Principal',
                'email' => 'judge@example.com',
                'password' => Hash::make('password123'),
                'role' => 'judge',
                'is_admin' => false,
            ],
            [
                'name' => 'Estudiante Demo',
                'email' => 'student@example.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'is_admin' => false,
            ],
            [
                'name' => 'María García',
                'email' => 'maria.garcia@student.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'is_admin' => false,
            ],
            [
                'name' => 'Carlos López',
                'email' => 'carlos.lopez@student.com',
                'password' => Hash::make('password123'),
                'role' => 'student',
                'is_admin' => false,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'is_admin' => $userData['is_admin'] ?? false,
                ]
            );

            // Asignar rol si no lo tiene
            if (!$user->hasRole($userData['role'])) {
                $user->assignRole($userData['role']);
            }
        }

        $this->command->info('✅ Usuarios demo creados: ' . count($users) . ' usuarios');
        $this->command->info('📧 Correos: admin@admin.com, judge@example.com, student@example.com');
        $this->command->info('🔑 Contraseña para todos: password123');
    }
}
