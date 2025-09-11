<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => $name,
            'description' => fake()->sentence(12),
            'slug' => Str::slug($name . '-' . fake()->unique()->numerify('###')),
            'is_active' => true,
            'user_id' => User::factory(),
        ];
    }
}
