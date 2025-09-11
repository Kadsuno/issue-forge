<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Handle search requests across projects and tickets.
     */
    public function __invoke(Request $request): View
    {
        $query = trim((string) $request->get('q', ''));

        $projects = collect();
        $tickets = collect();

        if ($query !== '') {
            $projects = Project::query()
                ->with('user')
                ->where(function ($q) use ($query) {
                    $q->where('name', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                })
                ->latest()
                ->limit(10)
                ->get();

            $tickets = Ticket::query()
                ->with(['project', 'user', 'assignedUser'])
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('description', 'like', "%{$query}%");
                })
                ->latest()
                ->limit(10)
                ->get();
        }

        return view('search.results', [
            'q' => $query,
            'projects' => $projects,
            'tickets' => $tickets,
        ]);
    }
}
