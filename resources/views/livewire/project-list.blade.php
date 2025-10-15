<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold text-white mb-2">Your Projects</h1>
            <p class="text-slate-400">Manage and organize your projects</p>
        </div>

    </div>

    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="card p-4 mb-6 border-l-4 border-success-500 bg-success-500/10" role="alert">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-success-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="text-success-300">{{ session('message') }}</span>
            </div>
        </div>
    @endif

    <!-- Create Form -->
    @if ($showCreateForm)
        <div class="card p-6 mb-8 animate-fade-in-up">
            <div class="flex items-center mb-6">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-white">Create New Project</h2>
            </div>

            <form wire:submit.prevent="createProject">
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">Project Name</label>
                        <input type="text" id="name" wire:model="name" class="input"
                            placeholder="Enter project name">
                        @error('name')
                            <span class="text-danger-400 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label for="description"
                            class="block text-sm font-medium text-slate-300 mb-2">Description</label>
                        <textarea id="description" wire:model="description" rows="3" class="input"
                            placeholder="Enter project description"></textarea>
                        @error('description')
                            <span class="text-danger-400 text-sm mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" id="is_active" wire:model="is_active"
                            class="h-4 w-4 text-primary-500 focus:ring-primary-500 bg-dark-700 border-dark-600 rounded">
                        <label for="is_active" class="ml-3 block text-sm text-slate-300">
                            Active project
                        </label>
                    </div>
                </div>

                <div class="mt-8 flex justify-end space-x-3">
                    <a href="{{ route('projects.create') }}" class="btn-primary">Go to full create form</a>
                </div>
            </form>
        </div>
    @endif

    <!-- Search -->
    <div class="mb-8">
        <div class="relative">
            <input type="text" wire:model.live="search" placeholder="Search projects..." class="input pl-10"
                aria-label="Search projects by name or description">
            <svg class="absolute left-3 top-3.5 w-4 h-4 text-slate-400" fill="none" stroke="currentColor"
                viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
    </div>

    <!-- Loading Skeleton (shown during wire:loading) -->
    <div wire:loading wire:target="search,gotoPage,previousPage,nextPage" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <x-skeleton-card :rows="3" />
        <x-skeleton-card :rows="3" />
        <x-skeleton-card :rows="3" />
    </div>

    <!-- Projects Grid -->
    <div wire:loading.remove wire:target="search,gotoPage,previousPage,nextPage" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($projects as $project)
            <div class="card-hover p-6 group animate-fade-in-up" style="animation-delay: {{ $loop->index * 0.1 }}s;">
                <div class="flex items-center justify-between mb-4">
                    <h3
                        class="text-lg font-semibold text-white group-hover:text-primary-400 transition-colors duration-200">
                        <a href="{{ route('projects.show', $project->slug) }}" class="flex items-center">
                            <div
                                class="w-2 h-2 bg-{{ $project->is_active ? 'success' : 'slate' }}-500 rounded-full mr-3">
                            </div>
                            {{ $project->name }}
                        </a>
                    </h3>
                    <div class="flex items-center space-x-2">
                        <span class="{{ $project->is_active ? 'badge-success' : 'badge-warning' }}">
                            {{ $project->is_active ? 'Active' : 'Inactive' }}
                        </span>
                        <button wire:click="toggleProjectStatus({{ $project->id }})"
                            class="text-slate-400 hover:text-white transition-colors duration-200 p-1 rounded hover:bg-dark-700 relative"
                            title="Toggle status"
                            aria-label="Toggle project {{ $project->name }} status"
                            wire:loading.attr="disabled"
                            wire:target="toggleProjectStatus({{ $project->id }})">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                                wire:loading.remove wire:target="toggleProjectStatus({{ $project->id }})">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z">
                                </path>
                            </svg>
                            <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"
                                wire:loading wire:target="toggleProjectStatus({{ $project->id }})">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                @if ($project->description)
                    <p class="text-slate-300 text-sm mb-4 leading-relaxed">
                        {{ Str::limit($project->description, 100) }}</p>
                @endif

                <div class="space-y-3 mb-4">
                    <div class="flex items-center justify-between text-sm text-slate-400">
                        <div class="flex items-center">
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-lg bg-dark-700/40 border border-dark-600/50">
                                <svg class="w-4 h-4 mr-1 text-slate-300" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                    </path>
                                </svg>
                                <span class="text-slate-300">{{ $project->tickets_count }} tickets</span>
                            </span>
                        </div>
                        <div class="flex items-center">
                            <div
                                class="w-6 h-6 mr-2 rounded-full bg-gradient-to-br from-primary-500 to-accent-500 flex items-center justify-center text-[10px] text-white">
                                {{ substr($project->user->name, 0, 1) }}
                            </div>
                            <span class="text-slate-300">{{ $project->user->name }}</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-2">
                        <div
                            class="rounded-lg bg-success-500/10 border border-success-500/20 px-3 py-2 text-center hover:bg-success-500/15 transition-colors">
                            <div class="text-xs text-success-300">Open</div>
                            <div class="text-base font-semibold text-success-200">{{ $project->open_tickets_count }}
                            </div>
                        </div>
                        <div
                            class="rounded-lg bg-primary-500/10 border border-primary-500/20 px-3 py-2 text-center hover:bg-primary-500/15 transition-colors">
                            <div class="text-xs text-primary-300">In Progress</div>
                            <div class="text-base font-semibold text-primary-200">
                                {{ $project->in_progress_tickets_count }}</div>
                        </div>
                        <div
                            class="rounded-lg bg-dark-600/20 border border-dark-600/30 px-3 py-2 text-center hover:bg-dark-600/30 transition-colors">
                            <div class="text-xs text-slate-300">Closed</div>
                            <div class="text-base font-semibold text-slate-200">{{ $project->closed_tickets_count }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center text-xs text-slate-500">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3M12 6a9 9 0 110 18 9 9 0 010-18z"></path>
                        </svg>
                        <span>Last activity:
                            {{ optional($project->latest_ticket_at ?? $project->updated_at)->diffForHumans() }}</span>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-4 border-t border-dark-600">
                    <span class="text-xs text-slate-500">
                        {{ $project->created_at->diffForHumans() }}
                    </span>
                    <a href="{{ route('projects.show', $project->slug) }}" class="btn-ghost text-sm py-1 px-3">
                        View
                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </a>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="text-center py-16 animate-fade-in-up">
                    <div class="w-24 h-24 mx-auto mb-6 bg-dark-700/50 rounded-full flex items-center justify-center">
                        <svg class="w-12 h-12 text-slate-500" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-white mb-2">No projects yet</h3>
                    <p class="text-slate-400 mb-8 max-w-md mx-auto">Get started by creating your first project.
                        Organize your tickets and collaborate with your team.</p>
                    <button wire:click="toggleCreateForm" class="btn-primary" wire:loading.attr="disabled" wire:target="toggleCreateForm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            wire:loading.remove wire:target="toggleCreateForm">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            wire:loading wire:target="toggleCreateForm">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Create Your First Project
                    </button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if ($projects->hasPages())
        <div class="mt-8">
            <div class="flex justify-center">
                {{ $projects->links() }}
            </div>
        </div>
    @endif
</div>
