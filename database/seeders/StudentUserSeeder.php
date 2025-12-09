<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class StudentUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'student@example.com';

        // Crear (o reutilizar) usuario estudiante
        $user = User::firstOrCreate(
            ['email' => $email],
            [
                'name'     => 'Estudiante Demo',
                'password' => Hash::make('password123'),
                'is_admin' => false,
            ]
        );

        // Asignar rol student
        if (! $user->hasRole('student')) {
            $user->assignRole('student');
        }

        $this->command->info("✅ Usuario estudiante listo: {$email} / password123 (rol: student)");
    }
}
