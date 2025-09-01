<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class ReportsController extends Controller
{
    /**
     * Display a simple analytics overview.
     */
    public function index(): View
    {
        // Basic totals
        $totals = [
            'projects' => Project::count(),
            'tickets' => Ticket::count(),
            'open' => Ticket::where('status', '!=', 'closed')->count(),
            'assigned_to_me' => Ticket::where('assigned_to', Auth::id())->count(),
        ];

        // Tickets by status
        $ticketsByStatus = Ticket::query()
            ->select('status')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('status')
            ->orderBy('count', 'desc')
            ->get();

        // Tickets by priority
        $ticketsByPriority = Ticket::query()
            ->select('priority')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('priority')
            ->orderBy('count', 'desc')
            ->get();

        // Tickets per project (top 10)
        $ticketsPerProject = Ticket::query()
            ->select('project_id')
            ->selectRaw('COUNT(*) as count')
            ->groupBy('project_id')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($row) {
                $row->project = Project::find($row->project_id);
                return $row;
            });

        return view('reports.index', [
            'totals' => $totals,
            'ticketsByStatus' => $ticketsByStatus,
            'ticketsByPriority' => $ticketsByPriority,
            'ticketsPerProject' => $ticketsPerProject,
        ]);
    }
}
