<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start md:items-center gap-3 md:gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">User Management</h2>
                        <p class="text-sm text-slate-400">Manage system users and permissions</p>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-auto flex items-center justify-between md:justify-end gap-3">
                <a href="{{ route('admin.users.create') }}" class="btn-primary w-full md:w-auto text-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add User
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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

            @if (session('error'))
                <div class="mb-6 card p-4 bg-danger-500/10 border border-danger-500/20 animate-fade-in-up">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-danger-400 mr-3" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span class="text-danger-300 font-medium">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <div class="card p-4 sm:p-8 animate-fade-in-up">
                @if ($users->count() > 0)
                    <div class="hidden lg:block overflow-hidden">
                        <table class="w-full">
                            <thead>
                                <tr class="border-b border-slate-700">
                                    <th
                                        class="text-left py-4 px-4 text-sm font-medium text-slate-300 uppercase tracking-wide">
                                        User</th>
                                    <th
                                        class="text-left py-4 px-4 text-sm font-medium text-slate-300 uppercase tracking-wide">
                                        Email</th>
                                    <th
                                        class="text-left py-4 px-4 text-sm font-medium text-slate-300 uppercase tracking-wide">
                                        Role</th>
                                    <th
                                        class="text-left py-4 px-4 text-sm font-medium text-slate-300 uppercase tracking-wide">
                                        Created</th>
                                    <th
                                        class="text-right py-4 px-4 text-sm font-medium text-slate-300 uppercase tracking-wide">
                                        Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-700">
                                @foreach ($users as $user)
                                    <tr class="hover:bg-dark-700/30 transition-colors duration-200 animate-fade-in-up"
                                        style="animation-delay: {{ $loop->index * 0.05 }}s;">
                                        <td class="py-4 px-4">
                                            <div class="flex items-center">
                                                <div
                                                    class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center mr-3">
                                                    <span
                                                        class="text-sm font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-white">{{ $user->name }}
                                                    </div>
                                                    @if ($user->id === auth()->id())
                                                        <span class="badge-primary text-xs">You</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="text-sm text-slate-300">{{ $user->email }}</div>
                                        </td>
                                        <td class="py-4 px-4">
                                            @if ($user->is_admin)
                                                <span class="badge-warning">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                                        </path>
                                                    </svg>
                                                    Admin
                                                </span>
                                            @else
                                                <span class="badge-secondary">User</span>
                                            @endif
                                        </td>
                                        <td class="py-4 px-4">
                                            <div class="text-sm text-slate-400">
                                                {{ $user->created_at->format('M j, Y') }}</div>
                                        </td>
                                        <td class="py-4 px-4 text-right">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('admin.users.edit', $user) }}"
                                                    class="btn-ghost btn-sm">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                                        </path>
                                                    </svg>
                                                </a>
                                                @if ($user->id !== auth()->id())
                                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                                        method="POST" class="inline-block">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn-ghost btn-sm text-danger-400 hover:text-danger-300"
                                                            onclick="return confirm('Are you sure you want to delete this user?')">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                                viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    stroke-width="2"
                                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Card list for mobile and tablet -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 lg:hidden">
                        @foreach ($users as $user)
                            <div class="p-4 rounded-lg bg-dark-800/60 border border-dark-600/50 h-full">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center">
                                        <span
                                            class="text-sm font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-white font-medium truncate">{{ $user->name }}</div>
                                        <div class="text-xs text-slate-400 truncate">{{ $user->email }}</div>
                                    </div>
                                    <div class="shrink-0">
                                        @if ($user->is_admin)
                                            <span class="badge-warning">Admin</span>
                                        @else
                                            <span class="badge-secondary">User</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="mt-3 flex items-center justify-between text-xs text-slate-500">
                                    <span>Created {{ $user->created_at->format('M j, Y') }}</span>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="btn-ghost btn-sm">Edit</a>
                                        @if ($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn-ghost btn-sm text-danger-400 hover:text-danger-300"
                                                    onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                            </form>
                                        @endif
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
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z">
                                </path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-white mb-2">No users found</h3>
                        <p class="text-slate-400 mb-8 max-w-md mx-auto">Get started by creating the first user account.
                        </p>
                        <a href="{{ route('admin.users.create') }}" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create First User
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
