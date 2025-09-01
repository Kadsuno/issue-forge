<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Tickets — {{ $project->name }}</h2>
                    <p class="text-sm text-slate-400">All tickets in this project</p>
                </div>
            </div>
            <a href="{{ route('projects.tickets.create', $project->slug) }}" class="btn-primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                New Ticket
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-0 overflow-hidden">
                @if ($tickets->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead class="bg-dark-700/40 text-slate-300">
                                <tr>
                                    <th class="text-left px-4 py-3">Title</th>
                                    <th class="text-left px-4 py-3">Type</th>
                                    <th class="text-left px-4 py-3">Priority</th>
                                    <th class="text-left px-4 py-3">Status</th>
                                    <th class="text-left px-4 py-3">Severity</th>
                                    <th class="text-left px-4 py-3">Assignee</th>
                                    <th class="text-left px-4 py-3">Due</th>
                                    <th class="text-left px-4 py-3">Est</th>
                                    <th class="text-left px-4 py-3">Labels</th>
                                    <th class="text-right px-4 py-3">Created</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-dark-700/50">
                                @foreach ($tickets as $ticket)
                                    <tr class="hover:bg-dark-700/30">
                                        <td class="px-4 py-3">
                                            <a class="text-white hover:text-primary-400 font-medium"
                                                href="{{ route('tickets.show', $ticket) }}">
                                                <span
                                                    class="text-slate-500 mr-2">{{ $ticket->number }}</span>{{ $ticket->title }}
                                            </a>
                                            <div class="text-xs text-slate-500">{{ $ticket->number }}</div>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span class="badge-secondary">{{ ucfirst($ticket->type ?? 'task') }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            <span
                                                class="badge-{{ $ticket->priority_color ?? 'secondary' }}">{{ ucfirst($ticket->priority) }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            @php(
    $statusClass = match ($ticket->status) {
        'open' => 'badge-primary',
        'in_progress' => 'badge-warning',
        'resolved' => 'badge-success',
        'closed' => 'badge-secondary',
        default => 'badge-primary',
    },
)
                                            <span
                                                class="{{ $statusClass }}">{{ str_replace('_', ' ', ucfirst($ticket->status)) }}</span>
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $ticket->severity ? ucfirst($ticket->severity) : '—' }}</td>
                                        <td class="px-4 py-3">{{ $ticket->assignedUser->name ?? 'Unassigned' }}</td>
                                        <td class="px-4 py-3 {{ $ticket->isOverdue() ? 'text-danger-400' : '' }}">
                                            {{ $ticket->due_date ? $ticket->due_date->format('M j, Y') : '—' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            {{ $ticket->estimate_minutes ? $ticket->estimate_minutes . 'm' : '—' }}
                                        </td>
                                        <td class="px-4 py-3">
                                            @if ($ticket->labels)
                                                <div class="flex flex-wrap gap-1">
                                                    @foreach (array_filter(array_map('trim', explode(',', $ticket->labels))) as $label)
                                                        <span class="badge-secondary">{{ $label }}</span>
                                                    @endforeach
                                                </div>
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-right text-slate-500">
                                            {{ $ticket->created_at->diffForHumans() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="p-10 text-center">
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-dark-700/50 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold mb-2">No tickets in this project</h3>
                        <p class="text-slate-400">Create the first ticket to get started.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
