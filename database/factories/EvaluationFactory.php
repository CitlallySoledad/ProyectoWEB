<?php

namespace Database\Factories;

use App\Models\Evaluation;
use App\Models\Project;
use App\Models\Rubric;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Evaluation>
 */
class EvaluationFactory extends Factory
{
    protected $model = Evaluation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $project = Project::factory()->create();
        
        return [
            'project_id' => $project->id,
            'project_name' => $project->name,
            'rubric_id' => $project->rubric_id ?? Rubric::factory(),
            'judge_id' => User::factory()->create()->assignRole('judge')->id,
            'creativity' => 0,
            'functionality' => 0,
            'innovation' => 0,
            'comments' => null,
            'total_score' => null,
            'final_score' => null,
            'general_comments' => null,
            'evaluated_at' => null,
            'status' => 'pendiente',
        ];
    }

    /**
     * Evaluación completada
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'creativity' => fake()->numberBetween(5, 10),
            'functionality' => fake()->numberBetween(5, 10),
            'innovation' => fake()->numberBetween(5, 10),
            'comments' => fake()->paragraph(),
            'total_score' => fake()->numberBetween(60, 100),
            'final_score' => fake()->numberBetween(60, 100),
            'general_comments' => fake()->paragraph(2),
            'evaluated_at' => now(),
            'status' => 'completada',
        ]);
    }

    /**
     * Evaluación pendiente
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pendiente',
        ]);
    }

    /**
     * Evaluación para juez específico
     */
    public function byJudge(User $judge): static
    {
        return $this->state(fn (array $attributes) => [
            'judge_id' => $judge->id,
        ]);
    }
}
