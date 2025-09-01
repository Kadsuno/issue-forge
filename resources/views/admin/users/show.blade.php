<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">User Details</h2>
                        <p class="text-sm text-slate-400">Profile and permissions overview</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.users.index') }}" class="btn-ghost">Back to Users</a>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn-primary">Edit</a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <div class="card p-6">
                        <div class="flex items-center space-x-4">
                            <div
                                class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center">
                                <span class="text-2xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                            </div>
                            <div>
                                <div class="text-lg font-semibold text-white">{{ $user->name }}</div>
                                <div class="text-slate-400 text-sm">{{ $user->email }}</div>
                            </div>
                        </div>
                        <div class="mt-6">
                            @if ($user->is_admin)
                                <span class="badge-warning">Administrator</span>
                            @else
                                <span class="badge-secondary">User</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="md:col-span-2">
                    <div class="card p-6">
                        <h3 class="text-sm font-semibold text-white mb-4">Account Information</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <div class="text-slate-500">Name</div>
                                <div class="text-slate-300">{{ $user->name }}</div>
                            </div>
                            <div>
                                <div class="text-slate-500">Email</div>
                                <div class="text-slate-300">{{ $user->email }}</div>
                            </div>
                            <div>
                                <div class="text-slate-500">Member since</div>
                                <div class="text-slate-300">{{ $user->created_at->format('M j, Y') }}</div>
                            </div>
                            <div>
                                <div class="text-slate-500">Last updated</div>
                                <div class="text-slate-300">{{ $user->updated_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 card p-6">
                        <h3 class="text-sm font-semibold text-white mb-4">Activity</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <div class="text-slate-500">Projects created</div>
                                <div class="text-slate-300">{{ $user->projects->count() }}</div>
                            </div>
                            <div>
                                <div class="text-slate-500">Tickets created</div>
                                <div class="text-slate-300">{{ $user->tickets->count() }}</div>
                            </div>
                            <div>
                                <div class="text-slate-500">Tickets assigned</div>
                                <div class="text-slate-300">{{ $user->assignedTickets->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
