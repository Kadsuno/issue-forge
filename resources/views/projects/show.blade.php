<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start md:items-center gap-3 md:gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">{{ $project->name }}</h2>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-auto flex items-center justify-between md:justify-end gap-3">
                <span class="{{ $project->is_active ? 'badge-success' : 'badge-warning' }}">
                    {{ $project->is_active ? 'Active' : 'Inactive' }}
                </span>
                <a href="{{ route('projects.edit', $project->slug) }}" class="btn-primary w-auto md:w-auto">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                    Edit Project
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if ($project->description)
                <x-description-card :content="Str::markdown($project->description, ['html_input' => 'strip', 'allow_unsafe_links' => false])" />
            @endif
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="mb-6 card p-4 bg-success-500/10 border border-success-500/20 animate-fade-in-up">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-success-400 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-success-300 font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <div class="card p-6 sm:p-8 animate-fade-in-up">
                <!-- Project Overview -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                    <div class="lg:col-span-2">
                        <div class="backdrop-glass bg-dark-800/60 border border-dark-600/50 rounded-xl p-6">
                            @php
                                $total = max(
                                    (int) $project->tickets_count,
                                    (int) $project->open_tickets_count +
                                        (int) $project->in_progress_tickets_count +
                                        (int) $project->closed_tickets_count,
                                );
                                $pctOpen = $total ? round(($project->open_tickets_count * 100) / $total) : 0;
                                $pctIn = $total ? round(($project->in_progress_tickets_count * 100) / $total) : 0;
                                $pctClosed = max(0, 100 - $pctOpen - $pctIn);
                            @endphp

                            <!-- Ticket distribution bar -->
                            <div class="mb-6">
                                <div class="h-2 w-full bg-dark-700/60 rounded-full overflow-hidden flex">
                                    <div class="h-full bg-success-500/70" style="width: {{ $pctOpen }}%"></div>
                                    <div class="h-full bg-primary-500/70" style="width: {{ $pctIn }}%"></div>
                                    <div class="h-full bg-slate-500/60" style="width: {{ $pctClosed }}%"></div>
                                </div>
                                <div class="mt-2 grid grid-cols-3 text-xs text-slate-400">
                                    <span class="text-success-300">Open {{ $pctOpen }}%</span>
                                    <span class="text-primary-300 text-center">In Progress {{ $pctIn }}%</span>
                                    <span class="text-right">Closed {{ $pctClosed }}%</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                                <!-- Left column -->
                                <dl class="space-y-4">
                                    <div>
                                        <dt class="text-slate-400">Start date</dt>
                                        <dd class="mt-1 text-white font-medium">
                                            {{ optional($project->start_date)->format('M j, Y') ?? '—' }}</dd>
                                    </div>
                                    <div>
                                        <dt class="text-slate-400">Due date</dt>
                                        <dd class="mt-1 text-white font-medium">
                                            {{ optional($project->due_date)->format('M j, Y') ?? '—' }}</dd>
                                    </div>
                                </dl>

                                <!-- Right column -->
                                <div>
                                    <div class="flex items-center mb-3 text-slate-400">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13 16h-1v-4h-1m1-4h.01M12 6a9 9 0 110 18 9 9 0 010-18z" />
                                        </svg>
                                        <span class="font-semibold text-slate-300">Details</span>
                                    </div>
                                    <dl class="space-y-3">
                                        <div>
                                            <dt class="text-slate-400">Default assignee</dt>
                                            <dd class="mt-1 text-white font-medium">
                                                {{ $project->defaultAssignee->name ?? '—' }}</dd>
                                        </div>
                                        <div class="flex items-center">
                                            <dt class="text-slate-400 mr-3">Visibility</dt>
                                            <dd><span
                                                    class="{{ ($project->visibility ?? 'team') === 'public' ? 'badge-success' : (($project->visibility ?? 'team') === 'private' ? 'badge-secondary' : 'badge-primary') }} capitalize">{{ $project->visibility ?? 'team' }}</span>
                                            </dd>
                                        </div>
                                        <div class="flex items-center">
                                            <dt class="text-slate-400 mr-3">Priority</dt>
                                            <dd><span
                                                    class="{{ ($project->priority ?? 'medium') === 'high' ? 'badge-danger' : (($project->priority ?? 'medium') === 'low' ? 'badge-success' : 'badge-warning') }} capitalize">{{ $project->priority ?? 'medium' }}</span>
                                            </dd>
                                        </div>
                                        <div>
                                            <dt class="text-slate-400">Ticket prefix</dt>
                                            <dd class="mt-1 text-white font-medium">
                                                {{ $project->ticket_prefix ?? '—' }}</dd>
                                        </div>
                                        <div class="flex items-center">
                                            <dt class="text-slate-400 mr-3">Brand color</dt>
                                            <dd class="flex items-center">
                                                <span
                                                    class="inline-block w-4 h-4 rounded-full mr-2 border border-dark-600/60"
                                                    style="background: {{ $project->color ?? '#64748b' }}"></span>
                                                <span
                                                    class="text-white font-medium">{{ $project->color ?? '—' }}</span>
                                            </dd>
                                        </div>
                                        <div class="flex items-center text-xs text-slate-500 pt-1">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 8v4l3 3M12 6a9 9 0 110 18 9 9 0 010-18z" />
                                            </svg>
                                            <span>Last activity:
                                                {{ optional($project->latest_ticket_at ?? $project->updated_at)->diffForHumans() }}</span>
                                        </div>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 lg:mt-0">
                        <div class="grid grid-cols-3 gap-2">
                            <div
                                class="rounded-lg bg-success-500/10 border border-success-500/20 px-3 py-3 text-center">
                                <div class="text-xs text-success-300">Open</div>
                                <div class="text-xl font-bold text-success-200">{{ $project->open_tickets_count }}
                                </div>
                            </div>
                            <div
                                class="rounded-lg bg-primary-500/10 border border-primary-500/20 px-3 py-3 text-center">
                                <div class="text-xs text-primary-300">In Progress</div>
                                <div class="text-xl font-bold text-primary-200">
                                    {{ $project->in_progress_tickets_count }}</div>
                            </div>
                            <div class="rounded-lg bg-dark-600/20 border border-dark-600/30 px-3 py-3 text-center">
                                <div class="text-xs text-slate-300">Closed</div>
                                <div class="text-xl font-bold text-slate-200">{{ $project->closed_tickets_count }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8">
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 bg-gradient-to-br from-success-500 to-success-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-white">Tickets</h3>
                    </div>
                    <a href="{{ route('projects.tickets.create', $project->slug) }}"
                        class="btn-primary w-full md:w-auto text-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        New Ticket
                    </a>
                </div>

                @if ($project->tickets->count() > 0)
                    <div class="space-y-4">
                        @foreach ($project->tickets as $ticket)
                            <div class="card p-6 group transition-all duration-200 hover:border-primary-500/30 animate-fade-in"
                                style="animation-delay: {{ $loop->index * 0.05 }}s;">
                                <div class="flex flex-col md:flex-row md:justify-between items-start gap-4">
                                    <div class="flex-1">
                                        <h4
                                            class="font-semibold text-white text-base sm:text-lg mb-2 group-hover:text-primary-400 transition-colors duration-200">
                                            <a href="{{ route('tickets.show', $ticket) }}"
                                                class="flex items-center flex-wrap">
                                                <div
                                                    class="w-2 h-2 bg-{{ $ticket->priority === 'high' ? 'danger' : ($ticket->priority === 'medium' ? 'warning' : 'success') }}-500 rounded-full mr-3">
                                                </div>
                                                <span
                                                    class="text-slate-500 mr-2">{{ $ticket->number }}</span>{{ $ticket->title }}
                                                <span
                                                    class="ml-2 badge-secondary">{{ ucfirst($ticket->type ?? 'task') }}</span>
                                            </a>
                                        </h4>
                                        @if ($ticket->description)
                                            <p class="text-slate-300 text-sm mb-4 leading-relaxed">
                                                {{ Str::limit($ticket->description, 120) }}
                                            </p>
                                        @endif
                                        <div class="flex items-center flex-wrap gap-4 text-xs text-slate-400">
                                            <div class="flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                                    </path>
                                                </svg>
                                                <span>{{ $ticket->user->name }}</span>
                                            </div>
                                            @if ($ticket->assigned_to)
                                                <div class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4"></path>
                                                    </svg>
                                                    <span>{{ $ticket->assignedUser->name }}</span>
                                                </div>
                                            @endif
                                            @if ($ticket->severity)
                                                <div class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <span>Severity: {{ ucfirst($ticket->severity) }}</span>
                                                </div>
                                            @endif
                                            <div class="flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                    viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span>{{ $ticket->created_at->diffForHumans() }}</span>
                                            </div>
                                            @if ($ticket->due_date)
                                                <div
                                                    class="flex items-center {{ $ticket->isOverdue() ? 'text-danger-400' : 'text-warning-400' }}">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 9l2 2 4-4"></path>
                                                    </svg>
                                                    <span>Due {{ $ticket->due_date->format('M j, Y') }}</span>
                                                </div>
                                            @endif
                                            @if ($ticket->estimate_minutes)
                                                <div class="flex items-center">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M12 8v4l3 3M12 6a9 9 0 110 18 9 9 0 010-18z" />
                                                    </svg>
                                                    <span>Est: {{ $ticket->estimate_minutes }}m</span>
                                                </div>
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
                                    <div class="flex items-center gap-2 md:ml-6">
                                        @php
                                            $priorityClass = match ($ticket->priority) {
                                                'urgent' => 'badge-danger',
                                                'high' => 'badge-danger',
                                                'medium' => 'badge-warning',
                                                default => 'badge-success',
                                            };

                                            $statusClass = match ($ticket->status) {
                                                'open' => 'badge-primary',
                                                'in_progress' => 'badge-warning',
                                                'resolved' => 'badge-success',
                                                'closed' => 'badge-secondary',
                                                default => 'badge-primary',
                                            };
                                        @endphp
                                        <span class="{{ $priorityClass }}">
                                            {{ ucfirst($ticket->priority) }}
                                        </span>
                                        <span class="{{ $statusClass }}">
                                            {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-16 animate-fade-in-up">
                        <div
                            class="w-24 h-24 mx-auto mb-6 bg-dark-700/50 rounded-full flex items-center justify-center">
                            <svg class="w-12 h-12 text-slate-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">No tickets yet</h3>
                        <p class="text-slate-400 mb-8 max-w-md mx-auto">Start organizing your work by creating the
                            first ticket for this project.</p>
                        <a href="{{ route('projects.tickets.create', $project->slug) }}" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create First Ticket
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
