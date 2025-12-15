<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;

class AssignJudgeToProjectsSeeder extends Seeder
{
    public function run()
    {
        // Buscar usuario juez por email
        $judge = User::where('email', 'judge@example.com')->first();
        
        if (!$judge) {
            $this->command->warn('⚠️  No se encontró el usuario juez (judge@example.com).');
            $this->command->info('💡 Ejecuta primero: php artisan db:seed --class=UserSeeder');
            return;
        }

        // Obtener todos los proyectos
        $projects = Project::all();
        
        if ($projects->isEmpty()) {
            $this->command->warn('⚠️  No hay proyectos para asignar.');
            $this->command->info('💡 Ejecuta primero: php artisan db:seed --class=DemoProjectsSeeder');
            return;
        }

        // Asignar juez a todos los proyectos (sin eliminar asignaciones existentes)
        $projectIds = $projects->pluck('id')->toArray();
        $judge->assignedProjects()->syncWithoutDetaching($projectIds);

        $this->command->info('✅ Proyectos asignados al juez: ' . count($projectIds));
        $this->command->info('📊 Juez: ' . $judge->name . ' (' . $judge->email . ')');
    }
}
