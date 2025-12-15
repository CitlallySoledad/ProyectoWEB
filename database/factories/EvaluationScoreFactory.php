<?php

namespace Database\Factories;

use App\Models\Evaluation;
use App\Models\EvaluationScore;
use App\Models\RubricCriterion;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EvaluationScore>
 */
class EvaluationScoreFactory extends Factory
{
    protected $model = EvaluationScore::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'evaluation_id' => Evaluation::factory(),
            'rubric_criterion_id' => RubricCriterion::factory(),
            'score' => fake()->numberBetween(0, 10),
            'comment' => fake()->optional()->sentence(),
        ];
    }

    /**
     * Score para evaluación específica
     */
    public function forEvaluation(Evaluation $evaluation): static
    {
        return $this->state(fn (array $attributes) => [
            'evaluation_id' => $evaluation->id,
        ]);
    }

    /**
     * Score para criterio específico
     */
    public function forCriterion(RubricCriterion $criterion): static
    {
        return $this->state(fn (array $attributes) => [
            'rubric_criterion_id' => $criterion->id,
        ]);
    }

    /**
     * Score alto (8-10)
     */
    public function high(): static
    {
        return $this->state(fn (array $attributes) => [
            'score' => fake()->numberBetween(8, 10),
            'comment' => 'Excelente trabajo en este criterio',
        ]);
    }

    /**
     * Score bajo (0-5)
     */
    public function low(): static
    {
        return $this->state(fn (array $attributes) => [
            'score' => fake()->numberBetween(0, 5),
            'comment' => 'Necesita mejorar en este aspecto',
        ]);
    }
}
