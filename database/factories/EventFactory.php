<?php

namespace Database\Factories;

use App\Models\Event;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    protected $model = Event::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+3 months');
        $endDate = fake()->dateTimeBetween($startDate, $startDate->format('Y-m-d') . ' +2 weeks');

        return [
            'title' => fake()->randomElement([
                'Hackathon',
                'Competencia de Innovación',
                'Desafío Tecnológico',
                'Maratón de Programación',
                'Concurso de Desarrollo'
            ]) . ' ' . fake()->year(),
            'description' => fake()->paragraph(3),
            'place' => fake()->randomElement([
                'Auditorio Principal',
                'Laboratorio de Innovación',
                'Campus Central',
                'Centro de Desarrollo',
                'Sala de Conferencias'
            ]) . ' - ' . fake()->company(),
            'capacity' => fake()->numberBetween(50, 200),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'status' => fake()->randomElement(['activo', 'próximo', 'finalizado']),
            'category' => fake()->randomElement([
                'Hackathon',
                'Innovación',
                'Desarrollo Web',
                'Inteligencia Artificial',
                'Mobile Apps',
                'IoT'
            ]),
            'judge_ids' => null,
            'rubric_ids' => null,
        ];
    }

    /**
     * Evento activo
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'activo',
            'start_date' => now()->subDays(2),
            'end_date' => now()->addDays(5),
        ]);
    }

    /**
     * Evento próximo
     */
    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'próximo',
            'start_date' => now()->addDays(10),
            'end_date' => now()->addDays(15),
        ]);
    }

    /**
     * Evento finalizado
     */
    public function finished(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'finalizado',
            'start_date' => now()->subDays(30),
            'end_date' => now()->subDays(25),
        ]);
    }
}
