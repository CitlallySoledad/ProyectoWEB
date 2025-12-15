<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\Rubric;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rubric>
 */
class RubricFactory extends Factory
{
    protected $model = Rubric::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Rúbrica ' . fake()->randomElement([
                'de Evaluación',
                'de Hackathon',
                'de Innovación',
                'de Desarrollo',
                'Técnica',
                'General'
            ]) . ' ' . fake()->year(),
            'event_id' => Event::factory(),
            'status' => fake()->randomElement(['activa', 'inactiva']),
        ];
    }

    /**
     * Rúbrica activa
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'activa',
        ]);
    }

    /**
     * Rúbrica con criterios predefinidos
     */
    public function withCriteria(): static
    {
        return $this->afterCreating(function (Rubric $rubric) {
            $criteria = [
                ['name' => 'Innovación', 'weight' => 25, 'min_score' => 0, 'max_score' => 10],
                ['name' => 'Funcionalidad', 'weight' => 25, 'min_score' => 0, 'max_score' => 10],
                ['name' => 'Diseño UX/UI', 'weight' => 20, 'min_score' => 0, 'max_score' => 10],
                ['name' => 'Código Limpio', 'weight' => 20, 'min_score' => 0, 'max_score' => 10],
                ['name' => 'Presentación', 'weight' => 10, 'min_score' => 0, 'max_score' => 10],
            ];

            foreach ($criteria as $criterion) {
                $rubric->criteria()->create($criterion);
            }
        });
    }
}
