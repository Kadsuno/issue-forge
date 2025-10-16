<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Ticket;
use App\Models\WorkflowState;
use Illuminate\Console\Command;

final class MigrateLegacyStatuses extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'workflow:migrate-legacy-statuses';

    /**
     * The console command description.
     */
    protected $description = 'Migrate legacy enum ticket statuses to workflow states';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting migration of legacy ticket statuses...');

        // Mapping of legacy statuses to workflow state slugs
        $statusMapping = [
            'open' => 'open',
            'in_progress' => 'in_progress',
            'resolved' => 'resolved',
            'closed' => 'closed',
        ];

        $tickets = Ticket::all();
        $updated = 0;
        $skipped = 0;

        foreach ($tickets as $ticket) {
            $currentStatus = $ticket->status;

            // Check if status needs mapping
            if (! isset($statusMapping[$currentStatus])) {
                $this->warn("Ticket #{$ticket->id} has unknown status: {$currentStatus}");
                $ticket->update(['status' => 'open']);
                $updated++;

                continue;
            }

            $targetSlug = $statusMapping[$currentStatus];

            // Verify that workflow state exists
            $workflowState = WorkflowState::where('slug', $targetSlug)
                ->whereNull('project_id')
                ->first();

            if (! $workflowState) {
                $this->error("Workflow state '{$targetSlug}' not found! Please run seeders first.");

                return self::FAILURE;
            }

            // Status already matches, skip
            if ($ticket->status === $targetSlug) {
                $skipped++;

                continue;
            }

            $ticket->update(['status' => $targetSlug]);
            $updated++;
        }

        $this->info("Migration complete!");
        $this->info("- Updated: {$updated}");
        $this->info("- Skipped: {$skipped}");

        return self::SUCCESS;
    }
}

