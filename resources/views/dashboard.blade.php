<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">{{ __('Dashboard') }}</h2>
                        <p class="text-sm text-slate-400">Welcome back, {{ Auth::user()->name }}!</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-2 text-sm text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ now()->format('M d, Y') }}
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            <!-- Getting Started Guide -->
            <div class="card-glass p-6 sm:p-8 hover-lift">
                <div class="flex items-start justify-between mb-4 sm:mb-6">
                    <div>
                        <h3 class="text-2xl font-bold text-gradient mb-2">Getting Started</h3>
                        <p class="text-slate-300 text-lg leading-relaxed max-w-2xl">
                            Your lightweight workflow to organize projects and tickets.
                        </p>
                    </div>
                    <div class="hidden sm:block">
                        <div
                            class="w-20 h-20 bg-gradient-to-br from-primary-500/20 to-accent-500/20 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                            <svg class="w-10 h-10 text-primary-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                    <div class="p-4 bg-dark-800/30 rounded-lg border border-dark-600/30">
                        <div class="flex items-center mb-2">
                            <span
                                class="mr-2 inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary-600 text-white text-xs font-semibold">1</span>
                            <span class="text-white font-medium">Create a project</span>
                        </div>
                        <p class="text-sm text-slate-400">Define scope and team.</p>
                    </div>
                    <div class="p-4 bg-dark-800/30 rounded-lg border border-dark-600/30">
                        <div class="flex items-center mb-2">
                            <span
                                class="mr-2 inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary-600 text-white text-xs font-semibold">2</span>
                            <span class="text-white font-medium">Add tickets</span>
                        </div>
                        <p class="text-sm text-slate-400">Bug, task, or feature.</p>
                    </div>
                    <div class="p-4 bg-dark-800/30 rounded-lg border border-dark-600/30">
                        <div class="flex items-center mb-2">
                            <span
                                class="mr-2 inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary-600 text-white text-xs font-semibold">3</span>
                            <span class="text-white font-medium">Assign & prioritize</span>
                        </div>
                        <p class="text-sm text-slate-400">Set assignee and priority.</p>
                    </div>
                    <div class="p-4 bg-dark-800/30 rounded-lg border border-dark-600/30">
                        <div class="flex items-center mb-2">
                            <span
                                class="mr-2 inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary-600 text-white text-xs font-semibold">4</span>
                            <span class="text-white font-medium">Work the workflow</span>
                        </div>
                        <p class="text-sm text-slate-400">Open → In progress → Closed.</p>
                    </div>
                    <div class="p-4 bg-dark-800/30 rounded-lg border border-dark-600/30">
                        <div class="flex items-center mb-2">
                            <span
                                class="mr-2 inline-flex items-center justify-center w-6 h-6 rounded-full bg-primary-600 text-white text-xs font-semibold">5</span>
                            <span class="text-white font-medium">Collaborate</span>
                        </div>
                        <p class="text-sm text-slate-400">Comments, mentions, and history.</p>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Grid -->
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                    <h3 class="text-xl font-bold text-white">Quick Actions</h3>
                    <div class="text-sm text-slate-400">Get started with these common tasks</div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Projects Action Card -->
                    <a href="{{ route('projects.index') }}"
                        class="group card-hover p-6 transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center group-hover:shadow-glow-primary transition-all duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4
                                class="text-lg font-semibold text-white mb-2 group-hover:text-primary-400 transition-colors duration-300">
                                Projects</h4>
                            <p class="text-slate-400 text-sm leading-relaxed">Manage and organize your projects with
                                ease. Create, edit, and track progress.</p>
                        </div>
                        <div class="mt-4 flex items-center text-xs text-slate-500">
                            <div class="w-2 h-2 bg-primary-500 rounded-full mr-2"></div>
                            Manage your projects
                        </div>
                    </a>

                    <!-- Tickets Action Card -->
                    <a href="{{ route('tickets.mine') }}"
                        class="group card-hover p-6 transform hover:scale-105 transition-all duration-300">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-success-500 to-success-600 rounded-xl flex items-center justify-center group-hover:shadow-glow-primary transition-all duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                            </div>
                            <div class="opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4
                                class="text-lg font-semibold text-white mb-2 group-hover:text-success-400 transition-colors duration-300">
                                Tickets</h4>
                            <p class="text-slate-400 text-sm leading-relaxed">See all tickets assigned to you. Update
                                status and priorities quickly.</p>
                        </div>
                        <div class="mt-4 flex items-center text-xs text-slate-500">
                            <div class="w-2 h-2 bg-success-500 rounded-full mr-2"></div>
                            Go to my tickets
                        </div>
                    </a>

                    <!-- Reports Action Card -->
                    <a href="{{ route('reports.index') }}"
                        class="group card-hover p-6 transform hover:scale-105 transition-all duration-300 relative overflow-hidden">
                        <div class="flex items-center justify-between mb-4">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-accent-500 to-accent-600 rounded-xl flex items-center justify-center group-hover:shadow-glow-accent transition-all duration-300">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <h4
                                class="text-lg font-semibold text-white mb-2 group-hover:text-accent-400 transition-colors duration-300">
                                Reports & Analytics</h4>
                            <p class="text-slate-400 text-sm leading-relaxed">View detailed analytics and generate
                                comprehensive reports on project progress.</p>
                        </div>
                        <div class="mt-4 flex items-center text-xs text-slate-500">
                            <div class="w-2 h-2 bg-accent-500 rounded-full mr-2"></div>
                            View analytics
                        </div>
                    </a>
                </div>
            </div>

            <!-- Stats Overview -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-400">Total Projects</p>
                            <p class="text-2xl font-bold text-white">{{ $stats['total_projects'] }}</p>
                        </div>
                        <div class="w-8 h-8 bg-primary-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-400">Total Tickets</p>
                            <p class="text-2xl font-bold text-white">{{ $stats['total_tickets'] }}</p>
                        </div>
                        <div class="w-8 h-8 bg-accent-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-accent-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-400">Open Tickets</p>
                            <p class="text-2xl font-bold text-white">{{ $stats['open_tickets'] }}</p>
                        </div>
                        <div class="w-8 h-8 bg-warning-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-warning-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-slate-400">My Tickets</p>
                            <p class="text-2xl font-bold text-white">{{ $stats['my_tickets'] }}</p>
                        </div>
                        <div class="w-8 h-8 bg-success-500/20 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-success-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" x-data="{ loading: true }" x-init="setTimeout(() => loading = false, 100)">
                <!-- Recent Projects -->
                <div class="card p-6" x-show="!loading" x-transition:enter="animate-fade-in-up">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-primary-500/20 to-accent-500/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-white">Recent Projects</h3>
                        </div>
                        <a href="{{ route('projects.index') }}"
                            class="text-sm text-slate-400 hover:text-white transition-colors duration-200">View All</a>
                    </div>

                    @if ($recentProjects->count() > 0)
                        <div class="space-y-4" data-stagger>
                            @foreach ($recentProjects as $project)
                                <a href="{{ route('projects.show', $project) }}"
                                    class="flex items-center justify-between p-4 bg-dark-800/30 rounded-lg border border-dark-600/30 hover:bg-dark-700/30 transition-colors duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-600 rounded-lg flex items-center justify-center">
                                            <span
                                                class="text-xs font-bold text-white">{{ substr($project->name, 0, 1) }}</span>
                                        </div>
                                        <div>
                                            <h4 class="font-medium text-white">{{ $project->name }}</h4>
                                            <p class="text-sm text-slate-400">by {{ $project->user->name }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        @if ($project->is_active)
                                            <span class="badge-success">Active</span>
                                        @else
                                            <span class="badge-secondary">Inactive</span>
                                        @endif
                                        <span
                                            class="text-xs text-slate-500">{{ $project->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-slate-400">No projects yet</p>
                            <a href="{{ route('projects.create') }}" class="btn-primary btn-sm mt-4">Create
                                Project</a>
                        </div>
                    @endif
                </div>

                <!-- Recent Projects Skeleton -->
                <div x-show="loading" x-transition:leave="animate-fade-out">
                    <x-skeleton-list :items="3" />
                </div>

                <!-- Recent Tickets -->
                <div class="card p-6" x-show="!loading" x-transition:enter="animate-fade-in-up">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div
                                class="w-8 h-8 bg-gradient-to-br from-accent-500/20 to-accent-600/20 rounded-lg flex items-center justify-center">
                                <svg class="w-4 h-4 text-accent-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-white">Recent Tickets</h3>
                        </div>
                        <a href="{{ route('tickets.mine') }}"
                            class="text-sm text-slate-400 hover:text-white transition-colors duration-200">View All</a>
                    </div>

                    @if ($recentTickets->count() > 0)
                        <div class="space-y-3" data-stagger>
                            @foreach ($recentTickets as $ticket)
                                <a href="{{ route('tickets.show', $ticket) }}"
                                    class="flex items-center justify-between p-3 bg-dark-800/30 rounded-lg border border-dark-600/30 hover:bg-dark-700/30 transition-colors duration-200">
                                    <div class="flex items-center space-x-3">
                                        <div
                                            class="w-2 h-2 rounded-full {{ $ticket->status_color ?? 'bg-slate-500' }}">
                                        </div>
                                        <div>
                                            <h5 class="text-sm font-medium text-white">
                                                <span
                                                    class="text-slate-500 mr-2">{{ $ticket->number }}</span>{{ Str::limit($ticket->title, 30) }}
                                            </h5>
                                            <p class="text-xs text-slate-400">{{ $ticket->project->name }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span
                                            class="badge-{{ $ticket->priority_color ?? 'secondary' }} text-xs">{{ ucfirst($ticket->priority) }}</span>
                                        <span
                                            class="text-xs text-slate-500">{{ $ticket->created_at->diffForHumans() }}</span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-slate-400">No tickets yet</p>
                            <a href="{{ route('projects.index') }}" class="btn-primary btn-sm mt-4">Go to
                                Projects</a>
                        </div>
                    @endif
                </div>

                <!-- Recent Tickets Skeleton -->
                <div x-show="loading" x-transition:leave="animate-fade-out">
                    <x-skeleton-list :items="4" />
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
