<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Project>
 */
final class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        $name = $this->faker->unique()->sentence(3);

        return [
            'name' => $name,
            'description' => $this->faker->paragraph(),
            'slug' => Str::slug($name.'-'.$this->faker->unique()->numberBetween(1, 9999)),
            'is_active' => true,
            'user_id' => \App\Models\User::factory(),
        ];
    }
}
