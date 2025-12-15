<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // ORDEN CORRECTO DE EJECUCIÓN:
        // 1️⃣ Roles y permisos (SIEMPRE PRIMERO)
        $this->call(RolePermissionSeeder::class);
        
        // 2️⃣ Usuarios (ahora consolidados en UserSeeder)
        $this->call(UserSeeder::class);
        
        // 3️⃣ Datos demo (eventos, equipos, proyectos, rúbricas)
        $this->call(DemoProjectsSeeder::class);
        
        // 4️⃣ Relaciones adicionales (asignar jueces a proyectos)
        $this->call(AssignJudgeToProjectsSeeder::class);
        
        $this->command->info('');
        $this->command->info('🎉 ========================================');
        $this->command->info('✅ Base de datos poblada exitosamente');
        $this->command->info('🎉 ========================================');
        $this->command->info('');
        $this->command->info('👤 Usuarios creados:');
        $this->command->info('   📧 Admin: admin@admin.com / password123');
        $this->command->info('   📧 Juez: judge@example.com / password123');
        $this->command->info('   📧 Estudiante: student@example.com / password123');
        $this->command->info('');
    }
}
