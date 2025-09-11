<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ticket>
 */
final class TicketFactory extends Factory
{
    protected $model = Ticket::class;

    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => $this->faker->sentence(6),
            'description' => $this->faker->paragraph(),
            'status' => Ticket::STATUS_OPEN,
            'priority' => Ticket::PRIORITY_MEDIUM,
        ];
    }
}
