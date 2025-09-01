<x-app-layout>
    <x-slot name="header">
        <div class="w-full">
            <div>
                <h2 class="text-xl font-bold text-white">Search</h2>
                <p class="text-sm text-slate-400">Results for "{{ $q }}"</p>
            </div>
            <form action="{{ route('search') }}" method="GET" class="mt-3 group relative w-full sm:max-w-md">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <div
                    class="rounded-xl bg-dark-900 border border-dark-800/80 shadow-inner group-focus-within:border-primary-500/40 group-focus-within:ring-1 group-focus-within:ring-primary-500/30">
                    <input type="text" name="q" value="{{ $q }}"
                        placeholder="Search projects and tickets..."
                        class="w-full h-10 pl-10 pr-4 bg-transparent text-sm text-slate-200 placeholder-slate-500 outline-none focus:ring-0 focus:border-0">
                </div>
            </form>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="card p-4 sm:p-6">
                <h3 class="text-sm font-semibold text-white mb-4">Projects</h3>
                @forelse ($projects as $project)
                    <a href="{{ route('projects.show', $project) }}"
                        class="block p-4 rounded-lg hover:bg-dark-700/40 transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-white font-medium">{{ $project->name }}</div>
                                <div class="text-xs text-slate-400">by {{ $project->user->name }} ·
                                    {{ $project->created_at->diffForHumans() }}</div>
                            </div>
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                @empty
                    <div class="text-slate-500 text-sm">No projects found.</div>
                @endforelse
            </div>

            <div class="card p-4 sm:p-6">
                <h3 class="text-sm font-semibold text-white mb-4">Tickets</h3>
                @forelse ($tickets as $ticket)
                    <a href="{{ route('tickets.show', $ticket) }}"
                        class="block p-4 rounded-lg hover:bg-dark-700/40 transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-white font-medium"><span
                                        class="text-slate-500 mr-2">{{ $ticket->number }}</span>{{ $ticket->title }}
                                </div>
                                <div class="text-xs text-slate-400">in {{ $ticket->project->name }} ·
                                    {{ $ticket->created_at->diffForHumans() }}</div>
                            </div>
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </a>
                @empty
                    <div class="text-slate-500 text-sm">No tickets found.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
