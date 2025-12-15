<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Project;
use App\Models\Rubric;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
{
    protected $model = Project::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Sistema de',
                'Plataforma de',
                'Aplicación de',
                'App Móvil de',
                'Dashboard de',
                'Portal de',
                'Herramienta de'
            ]) . ' ' . fake()->randomElement([
                'Gestión',
                'Recomendaciones',
                'E-Learning',
                'Salud',
                'Análisis',
                'Inventario',
                'Comunicación',
                'Monitoreo'
            ]) . ' ' . fake()->randomElement([
                'Inteligente',
                'con IA',
                'Adaptativo',
                'en Tiempo Real',
                'Colaborativo',
                'Cloud-Based'
            ]),
            'team_id' => Team::factory(),
            'event_id' => Event::factory(),
            'rubric_id' => Rubric::factory(),
            'status' => fake()->randomElement(['pendiente', 'en_progreso', 'completado']),
            'visibility' => fake()->randomElement(['publico', 'privado']),
        ];
    }

    /**
     * Proyecto con equipo específico
     */
    public function forTeam(Team $team): static
    {
        return $this->state(fn (array $attributes) => [
            'team_id' => $team->id,
        ]);
    }

    /**
     * Proyecto para evento específico
     */
    public function forEvent(Event $event): static
    {
        return $this->state(fn (array $attributes) => [
            'event_id' => $event->id,
        ]);
    }

    /**
     * Proyecto con rúbrica específica
     */
    public function withRubric(Rubric $rubric): static
    {
        return $this->state(fn (array $attributes) => [
            'rubric_id' => $rubric->id,
        ]);
    }

    /**
     * Proyecto público
     */
    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'visibility' => 'publico',
        ]);
    }

    /**
     * Proyecto privado
     */
    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'visibility' => 'privado',
        ]);
    }
}
