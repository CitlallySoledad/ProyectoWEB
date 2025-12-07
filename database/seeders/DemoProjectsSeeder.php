<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Event;
use App\Models\Team;
use App\Models\Project;
use App\Models\Rubric;
use App\Models\RubricCriterion;
use App\Models\User;

class DemoProjectsSeeder extends Seeder
{
    public function run()
    {
        // Crear eventos
        $event1 = Event::create([
            'title' => 'Hackathon 2025 - Inicio',
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(10),
        ]);

        $event2 = Event::create([
            'title' => 'Competencia de Innovación Q1',
            'start_date' => now()->addDays(20),
            'end_date' => now()->addDays(25),
        ]);

        // Crear equipos
        $team1 = Team::create(['name' => 'Equipo Phoenix']);
        $team2 = Team::create(['name' => 'Equipo Innovadores']);
        $team3 = Team::create(['name' => 'Equipo TechStars']);

        // Crear proyectos para evento 1
        $project1 = Project::create([
            'name' => 'Sistema de Recomendaciones con IA',
            'team_id' => $team1->id,
            'event_id' => $event1->id,
            'status' => 'pendiente',
        ]);

        $project2 = Project::create([
            'name' => 'Plataforma de E-Learning Adaptativo',
            'team_id' => $team2->id,
            'event_id' => $event1->id,
            'status' => 'pendiente',
        ]);

        $project3 = Project::create([
            'name' => 'App Móvil de Salud Mental',
            'team_id' => $team3->id,
            'event_id' => $event1->id,
            'status' => 'pendiente',
        ]);

        // Crear proyectos para evento 2
        $project4 = Project::create([
            'name' => 'Chatbot Inteligente para Atención al Cliente',
            'team_id' => $team1->id,
            'event_id' => $event2->id,
            'status' => 'pendiente',
        ]);

        $project5 = Project::create([
            'name' => 'Dashboard Analítico Empresarial',
            'team_id' => $team2->id,
            'event_id' => $event2->id,
            'status' => 'pendiente',
        ]);

        // Crear rúbricas para cada evento
        $rubric1 = Rubric::create([
            'name' => 'Rúbrica Hackathon 2025',
            'event_id' => $event1->id,
            'status' => 'activa',
        ]);

        $rubric2 = Rubric::create([
            'name' => 'Rúbrica Innovación Q1',
            'event_id' => $event2->id,
            'status' => 'activa',
        ]);

        // Asignar rúbricas a proyectos
        $project1->update(['rubric_id' => $rubric1->id]);
        $project2->update(['rubric_id' => $rubric1->id]);
        $project3->update(['rubric_id' => $rubric1->id]);
        $project4->update(['rubric_id' => $rubric2->id]);
        $project5->update(['rubric_id' => $rubric2->id]);

        // Crear criterios de rúbrica 1
        $criteria1 = [
            ['name' => 'Innovación', 'weight' => 25],
            ['name' => 'Funcionalidad', 'weight' => 25],
            ['name' => 'Diseño UX/UI', 'weight' => 20],
            ['name' => 'Código Limpio', 'weight' => 20],
            ['name' => 'Presentación', 'weight' => 10],
        ];

        foreach ($criteria1 as $criterion) {
            RubricCriterion::create([
                'rubric_id' => $rubric1->id,
                'name' => $criterion['name'],
                'weight' => $criterion['weight'],
            ]);
        }

        // Crear criterios de rúbrica 2
        $criteria2 = [
            ['name' => 'Impacto Social', 'weight' => 30],
            ['name' => 'Viabilidad', 'weight' => 25],
            ['name' => 'Escalabilidad', 'weight' => 20],
            ['name' => 'Sostenibilidad', 'weight' => 15],
            ['name' => 'Equipo', 'weight' => 10],
        ];

        foreach ($criteria2 as $criterion) {
            RubricCriterion::create([
                'rubric_id' => $rubric2->id,
                'name' => $criterion['name'],
                'weight' => $criterion['weight'],
            ]);
        }

        $this->command->info('✅ Demo projects, teams, events, and rubrics creados exitosamente.');
    }
}

