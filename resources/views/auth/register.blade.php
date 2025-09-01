<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-white mb-2">Create Account</h2>
        <p class="text-sm text-slate-400">Join our ticket management system</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
                {{ __('Name') }}
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                autocomplete="name" class="input w-full" placeholder="Enter your full name" />
            @error('name')
                <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                {{ __('Email') }}
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
                autocomplete="username" class="input w-full" placeholder="Enter your email address" />
            @error('email')
                <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div>
            <label for="password" class="block text-sm font-medium text-slate-300 mb-2">
                {{ __('Password') }}
            </label>
            <input id="password" type="password" name="password" required autocomplete="new-password"
                class="input w-full" placeholder="Create a secure password" />
            @error('password')
                <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-2">
                {{ __('Confirm Password') }}
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                autocomplete="new-password" class="input w-full" placeholder="Confirm your password" />
            @error('password_confirmation')
                <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
            @enderror
        </div>

        <div class="pt-4">
            <button type="submit" class="btn-primary w-full">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                {{ __('Create Account') }}
            </button>
        </div>

        <div class="text-center pt-4 border-t border-slate-700">
            <p class="text-sm text-slate-400">
                Already have an account?
                <a href="{{ route('login') }}" class="text-primary-400 hover:text-primary-300 underline font-medium">
                    Sign in here
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
