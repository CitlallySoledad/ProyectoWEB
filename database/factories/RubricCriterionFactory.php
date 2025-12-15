<?php

namespace Database\Factories;

use App\Models\Rubric;
use App\Models\RubricCriterion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\RubricCriterion>
 */
class RubricCriterionFactory extends Factory
{
    protected $model = RubricCriterion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'rubric_id' => Rubric::factory(),
            'name' => fake()->randomElement([
                'Innovación',
                'Funcionalidad',
                'Diseño UX/UI',
                'Código Limpio',
                'Presentación',
                'Documentación',
                'Escalabilidad',
                'Seguridad',
                'Rendimiento',
                'Creatividad'
            ]),
            'weight' => fake()->numberBetween(10, 30),
            'min_score' => 0,
            'max_score' => 10,
        ];
    }

    /**
     * Criterio para rúbrica específica
     */
    public function forRubric(Rubric $rubric): static
    {
        return $this->state(fn (array $attributes) => [
            'rubric_id' => $rubric->id,
        ]);
    }
}
