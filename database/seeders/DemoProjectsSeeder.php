<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        // Usar transacción para asegurar consistencia de datos
        DB::transaction(function () {
            // Obtener usuario estudiante para asignar como líder de equipos
            $student = User::where('email', 'student@example.com')->first();
            if (!$student) {
                $this->command->warn('⚠️  Usuario estudiante no encontrado. Creando uno temporal...');
                $student = User::create([
                    'name' => 'Estudiante Demo',
                    'email' => 'student@example.com',
                    'password' => \Hash::make('password123'),
                ]);
                $student->assignRole('student');
            }

            // Crear eventos con TODOS los campos obligatorios
            $event1 = Event::create([
                'title' => 'Hackathon 2025 - Inicio',
                'description' => 'Evento de desarrollo de software enfocado en soluciones innovadoras con tecnologías emergentes. Equipos de estudiantes competirán para crear prototipos funcionales.',
                'place' => 'Auditorio Principal ITSPA',
                'capacity' => 100,
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(10),
                'status' => 'activo',
                'category' => 'Hackathon',
            ]);

            $event2 = Event::create([
                'title' => 'Competencia de Innovación Q1',
                'description' => 'Competencia trimestral de proyectos innovadores que impacten positivamente en la comunidad. Se evaluará viabilidad, impacto social y escalabilidad.',
                'place' => 'Laboratorio de Innovación',
                'capacity' => 75,
                'start_date' => now()->addDays(20),
                'end_date' => now()->addDays(25),
                'status' => 'próximo',
                'category' => 'Innovación',
            ]);

            // Crear equipos con líder asignado
            $team1 = Team::create([
                'name' => 'Equipo Phoenix',
                'leader_id' => $student->id,
            ]);
            // Agregar líder como miembro del equipo
            $team1->members()->attach($student->id, ['role' => null]);

            $team2 = Team::create([
                'name' => 'Equipo Innovadores',
                'leader_id' => $student->id,
            ]);
            $team2->members()->attach($student->id, ['role' => null]);

            $team3 = Team::create([
                'name' => 'Equipo TechStars',
                'leader_id' => $student->id,
            ]);
            $team3->members()->attach($student->id, ['role' => null]);

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

            // Crear criterios de rúbrica 1 con scores
            $criteria1 = [
                ['name' => 'Innovación', 'weight' => 25, 'min_score' => 0, 'max_score' => 10],
                ['name' => 'Funcionalidad', 'weight' => 25, 'min_score' => 0, 'max_score' => 10],
                ['name' => 'Diseño UX/UI', 'weight' => 20, 'min_score' => 0, 'max_score' => 10],
                ['name' => 'Código Limpio', 'weight' => 20, 'min_score' => 0, 'max_score' => 10],
                ['name' => 'Presentación', 'weight' => 10, 'min_score' => 0, 'max_score' => 10],
            ];

            foreach ($criteria1 as $criterion) {
                RubricCriterion::create([
                    'rubric_id' => $rubric1->id,
                    'name' => $criterion['name'],
                    'weight' => $criterion['weight'],
                    'min_score' => $criterion['min_score'],
                    'max_score' => $criterion['max_score'],
                ]);
            }

            // Crear criterios de rúbrica 2 con scores
            $criteria2 = [
                ['name' => 'Impacto Social', 'weight' => 30, 'min_score' => 0, 'max_score' => 10],
                ['name' => 'Viabilidad', 'weight' => 25, 'min_score' => 0, 'max_score' => 10],
                ['name' => 'Escalabilidad', 'weight' => 20, 'min_score' => 0, 'max_score' => 10],
                ['name' => 'Sostenibilidad', 'weight' => 15, 'min_score' => 0, 'max_score' => 10],
                ['name' => 'Equipo', 'weight' => 10, 'min_score' => 0, 'max_score' => 10],
            ];

            foreach ($criteria2 as $criterion) {
                RubricCriterion::create([
                    'rubric_id' => $rubric2->id,
                    'name' => $criterion['name'],
                    'weight' => $criterion['weight'],
                    'min_score' => $criterion['min_score'],
                    'max_score' => $criterion['max_score'],
                ]);
            }

            $this->command->info('✅ Demo projects, teams, events, and rubrics creados exitosamente.');
        });
    }
}

