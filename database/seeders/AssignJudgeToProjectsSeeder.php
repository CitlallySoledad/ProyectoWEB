<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;

class AssignJudgeToProjectsSeeder extends Seeder
{
    public function run()
    {
        // buscar usuario juez por email (ajusta si usas otro correo)
        $judge = User::where('email', 'judge@example.com')->first();
        if (! $judge) {
            $this->command->info('No se encontró el usuario juez (judge@example.com). Omite asignación.');
            return;
        }

        $projectIds = Project::pluck('id')->toArray();
        if (empty($projectIds)) {
            $this->command->info('No hay proyectos para asignar.');
            return;
        }

        // adjuntar sin eliminar asignaciones existentes
        $judge->assignedProjects()->syncWithoutDetaching($projectIds);

        $this->command->info('Proyectos asignados al juez: ' . count($projectIds));
    }
}
