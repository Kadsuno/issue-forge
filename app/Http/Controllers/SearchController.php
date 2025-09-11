<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Handle search requests across projects and tickets.
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
        $query = trim((string) $request->get('q', ''));

        $projects = collect();
        $tickets = collect();

        if ($query !== '') {
            // Fast-path: ticket number lookups ("123", "#123", or "PREFIX-123")
            if (preg_match('/^(?:#)?(\d+)$/', $query, $m)) {
                $id = (int) $m[1];
                if ($id > 0) {
                    $ticket = Ticket::with(['project', 'user', 'assignedUser'])->find($id);
                    if ($ticket) {
                        return redirect()->route('tickets.show', $ticket);
                    }
                }
            } elseif (preg_match('/^([A-Za-z][A-Za-z0-9]{1,10})-(\d+)$/', $query, $m)) {
                $prefix = strtoupper($m[1]);
                $id = (int) $m[2];
                $ticket = Ticket::with(['project', 'user', 'assignedUser'])
                    ->whereKey($id)
                    ->whereHas('project', function ($q) use ($prefix) {
                        $q->whereRaw('UPPER(ticket_prefix) = ?', [$prefix]);
                    })
                    ->first();
                if ($ticket) {
                    return redirect()->route('tickets.show', $ticket);
                }
            }

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
                        ->orWhere('description', 'like', "%{$query}%")
                        ->orWhere('slug', 'like', "%{$query}%");
                })
                // Include numeric id matches in general results
                ->when(preg_match('/^(?:#)?(\d+)$/', $query, $m), function ($q) use ($m) {
                    $q->orWhere('id', (int) $m[1]);
                })
                // Include PREFIX-123 matches by id constrained to prefix
                ->when(preg_match('/^([A-Za-z][A-Za-z0-9]{1,10})-(\d+)$/', $query, $m), function ($q) use ($m) {
                    $prefix = strtoupper($m[1]);
                    $id = (int) $m[2];
                    $q->orWhere(function ($qq) use ($id, $prefix) {
                        $qq->where('id', $id)
                            ->whereHas('project', function ($p) use ($prefix) {
                                $p->whereRaw('UPPER(ticket_prefix) = ?', [$prefix]);
                            });
                    });
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
