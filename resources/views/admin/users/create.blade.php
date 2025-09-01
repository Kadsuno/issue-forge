<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start md:items-center gap-3 md:gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Create New User</h2>
                        <p class="text-sm text-slate-400">Add a new user to the system</p>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-auto flex items-center justify-between md:justify-end gap-3">
                <a href="{{ route('admin.users.index') }}" class="btn-ghost w-full md:w-auto text-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Users
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-6 sm:p-8 animate-fade-in-up">
                <!-- Flash Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-danger-500/10 border border-danger-500/20 rounded-lg">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-danger-400 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-sm font-medium text-danger-300">There were some problems with your input:
                            </h3>
                        </div>
                        <ul class="list-disc list-inside text-sm text-danger-400">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
                            Full Name <span class="text-danger-400">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="input w-full" placeholder="Enter user's full name...">
                        @error('name')
                            <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                            Email Address <span class="text-danger-400">*</span>
                        </label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                            class="input w-full" placeholder="Enter user's email address...">
                        @error('email')
                            <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                            Password <span class="text-danger-400">*</span>
                        </label>
                        <input type="password" id="password" name="password" required class="input w-full"
                            placeholder="Create a secure password..." minlength="8">
                        @error('password')
                            <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">
                            Confirm Password <span class="text-danger-400">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="input w-full" placeholder="Confirm the password..." minlength="8">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Roles -->
                    <div>
                        <label for="roles" class="block text-sm font-medium text-slate-300 mb-2">
                            Roles
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                            @foreach ($roles as $role)
                                <label class="flex items-center space-x-2">
                                    <input type="checkbox" name="roles[]" value="{{ $role }}"
                                        {{ in_array($role, old('roles', [])) ? 'checked' : '' }}
                                        class="w-4 h-4 bg-dark-700 border-slate-600 rounded text-primary-500 focus:ring-primary-500/20 focus:ring-2">
                                    <span
                                        class="text-sm text-slate-300 capitalize">{{ str_replace('_', ' ', $role) }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('roles.*')
                            <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Admin Status -->
                    <div class="p-4 bg-warning-500/10 border border-warning-500/20 rounded-lg">
                        <label class="flex items-start gap-3">
                            <input type="checkbox" name="is_admin" value="1"
                                {{ old('is_admin') ? 'checked' : '' }}
                                class="w-4 h-4 bg-dark-700 border-slate-600 rounded text-warning-500 focus:ring-warning-500/20 focus:ring-2 mt-0.5">
                            <div>
                                <span class="text-sm font-medium text-warning-300">
                                    Grant Administrator Privileges
                                </span>
                                <p class="text-xs text-warning-400 mt-1">
                                    Administrators can manage users, projects, and system settings. Use this privilege
                                    carefully.
                                </p>
                            </div>
                        </label>
                    </div>

                    <!-- Form Actions -->
                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-6 border-t border-slate-700">
                        <a href="{{ route('admin.users.index') }}" class="btn-ghost w-full sm:w-auto text-center">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Create User
                        </button>
                    </div>
                </form>
            </div>

            <!-- User Creation Tips -->
            <div class="mt-8 card p-6 animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 bg-primary-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-semibold text-white mb-2">User Account Guidelines</h3>
                        <ul class="text-xs text-slate-400 space-y-1">
                            <li>• Use strong passwords with at least 8 characters</li>
                            <li>• Email addresses must be unique and valid</li>
                            <li>• Regular users can create projects and tickets</li>
                            <li>• Admin users have full system access</li>
                            <li>• New users will receive an email to set up their account</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
