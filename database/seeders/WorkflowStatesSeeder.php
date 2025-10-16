<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Services\WorkflowService;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

final class WorkflowStatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $workflowService = new WorkflowService;

        // Seed predefined states
        $workflowService->seedPredefinedStates();

        // Set permissions for restricted states
        $this->setStatePermissions();
    }

    /**
     * Set role permissions for certain workflow states
     */
    private function setStatePermissions(): void
    {
        $adminRole = Role::where('name', 'admin')->first();
        $pmRole = Role::where('name', 'project_manager')->first();

        if (! $adminRole || ! $pmRole) {
            return;
        }

        // Get the wontfix state - only admin and PM can set
        $wontfixState = \App\Models\WorkflowState::where('slug', 'wontfix')->first();
        if ($wontfixState) {
            $wontfixState->roles()->syncWithoutDetaching([
                $adminRole->id => ['can_set_to' => true],
                $pmRole->id => ['can_set_to' => true],
            ]);
        }
    }
}

