<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-accent-500 to-accent-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Reports & Analytics</h2>
                    <p class="text-sm text-slate-400">Overview of tickets and projects</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Totals -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="card p-6">
                    <p class="text-sm text-slate-400">Projects</p>
                    <p class="text-2xl text-white font-bold">{{ $totals['projects'] }}</p>
                </div>
                <div class="card p-6">
                    <p class="text-sm text-slate-400">Tickets</p>
                    <p class="text-2xl text-white font-bold">{{ $totals['tickets'] }}</p>
                </div>
                <div class="card p-6">
                    <p class="text-sm text-slate-400">Open Tickets</p>
                    <p class="text-2xl text-white font-bold">{{ $totals['open'] }}</p>
                </div>
                <div class="card p-6">
                    <p class="text-sm text-slate-400">Assigned To Me</p>
                    <p class="text-2xl text-white font-bold">{{ $totals['assigned_to_me'] }}</p>
                </div>
            </div>

            <!-- Distributions -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="card p-6">
                    <h3 class="text-sm font-semibold text-white mb-4">Tickets by Status</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach ($ticketsByStatus as $row)
                            <li class="flex items-center justify-between">
                                <span class="text-slate-300 capitalize">{{ $row->status }}</span>
                                <span class="text-slate-400">{{ $row->count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div class="card p-6">
                    <h3 class="text-sm font-semibold text-white mb-4">Tickets by Priority</h3>
                    <ul class="space-y-2 text-sm">
                        @foreach ($ticketsByPriority as $row)
                            <li class="flex items-center justify-between">
                                <span class="text-slate-300 capitalize">{{ $row->priority }}</span>
                                <span class="text-slate-400">{{ $row->count }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Top Projects -->
            <div class="card p-6">
                <h3 class="text-sm font-semibold text-white mb-4">Tickets per Project (Top 10)</h3>
                <div class="space-y-2">
                    @forelse ($ticketsPerProject as $row)
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-300">{{ $row->project->name ?? 'Unknown Project' }}</span>
                            <span class="text-slate-400">{{ $row->count }}</span>
                        </div>
                    @empty
                        <p class="text-slate-500 text-sm">No data yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
