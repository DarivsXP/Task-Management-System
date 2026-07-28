<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => fake()->sentence(4),
            'description' => fake()->paragraph(),
            'due_date' => fake()->optional(0.8)->dateTimeBetween('now', '+1 month'),
            'status' => fake()->randomElement(['Pending', 'In Progress', 'Completed']),
        ];
    }
}
