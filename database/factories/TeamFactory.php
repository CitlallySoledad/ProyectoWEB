<?php

namespace Database\Factories;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Equipo ' . fake()->word() . ' ' . fake()->randomNumber(2),
            'leader_id' => User::factory(), // Crea un usuario automáticamente
        ];
    }

    /**
     * Team con líder específico
     */
    public function withLeader(User $leader): static
    {
        return $this->state(fn (array $attributes) => [
            'leader_id' => $leader->id,
        ]);
    }

    /**
     * Team con miembros adjuntos
     */
    public function withMembers(int $count = 3): static
    {
        return $this->afterCreating(function (Team $team) use ($count) {
            // Adjuntar el líder primero
            $team->members()->attach($team->leader_id, ['role' => null]);
            
            // Adjuntar miembros adicionales
            $members = User::factory()->count($count - 1)->create();
            foreach ($members as $member) {
                $team->members()->attach($member->id, [
                    'role' => fake()->randomElement(['Back', 'Front', 'Diseñador', null])
                ]);
            }
        });
    }
}
