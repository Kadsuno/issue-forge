<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-success-500 to-success-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">My Tickets</h2>
                    <p class="text-sm text-slate-400">Tickets assigned to you</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 150)">
            <!-- Loading Skeleton -->
            <div x-show="loading" x-transition:leave="animate-fade-out">
                <x-skeleton-list :items="5" />
            </div>

            <!-- Actual Content -->
            <div class="card p-4 sm:p-6" x-show="!loading" x-transition:enter="animate-fade-in-up">
                @if ($tickets->count() > 0)
                    <div class="divide-y divide-dark-700/50">
                        @foreach ($tickets as $ticket)
                            <a href="{{ route('tickets.show', $ticket) }}"
                                class="block py-4 hover:bg-dark-700/30 rounded-lg px-2 transition-colors duration-200">
                                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                                    <div class="flex items-start gap-3">
                                        <div
                                            class="w-2 h-2 rounded-full {{ $ticket->status_color ?? 'bg-slate-500' }} mt-1">
                                        </div>
                                        <div>
                                            <div class="text-white font-medium flex items-center gap-2 flex-wrap">
                                                <span class="text-slate-500">{{ $ticket->number }}</span>
                                                <span>{{ $ticket->title }}</span>
                                                <span
                                                    class="badge-secondary">{{ ucfirst($ticket->type ?? 'task') }}</span>
                                                <span
                                                    class="badge-{{ $ticket->priority_color ?? 'secondary' }}">{{ ucfirst($ticket->priority) }}</span>
                                                <span
                                                    class="badge-{{ $ticket->status === 'resolved' ? 'success' : ($ticket->status === 'in_progress' ? 'warning' : ($ticket->status === 'closed' ? 'secondary' : 'primary')) }}">
                                                    {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                                </span>
                                            </div>
                                            <div
                                                class="text-xs text-slate-400 mt-1 flex flex-wrap gap-2 sm:gap-3 items-center">
                                                <span>{{ $ticket->project->name }}</span>
                                                @if ($ticket->assignedUser)
                                                    <span>• Assigned: <span
                                                            class="text-slate-300">{{ $ticket->assignedUser->name }}</span></span>
                                                @endif
                                                @if ($ticket->severity)
                                                    <span>• Severity: <span
                                                            class="text-slate-300">{{ ucfirst($ticket->severity) }}</span></span>
                                                @endif
                                                @if ($ticket->estimate_minutes)
                                                    <span>• Est: <span
                                                            class="text-slate-300">{{ $ticket->estimate_minutes }}m</span></span>
                                                @endif
                                                @if ($ticket->due_date)
                                                    <span class="{{ $ticket->isOverdue() ? 'text-danger-400' : '' }}">•
                                                        Due: {{ $ticket->due_date->format('M j, Y') }}</span>
                                                @endif
                                            </div>
                                            @if ($ticket->labels)
                                                <div class="mt-2 flex flex-wrap gap-2">
                                                    @foreach (array_filter(array_map('trim', explode(',', $ticket->labels))) as $label)
                                                        <span class="badge-secondary">{{ $label }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="text-xs text-slate-500 whitespace-nowrap sm:ml-4">
                                        {{ $ticket->created_at->diffForHumans() }}
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16">
                        <div
                            class="w-16 h-16 mx-auto mb-4 bg-dark-700/50 rounded-full flex items-center justify-center">
                            <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="text-white font-semibold mb-2">No assigned tickets</h3>
                        <p class="text-slate-400">You have no tickets assigned to you right now.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
