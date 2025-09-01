<x-guest-layout>
    <div class="mb-6 text-center">
        <h2 class="text-2xl font-bold text-white mb-2">Welcome Back</h2>
        <p class="text-sm text-slate-400">Sign in to your account</p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-4 p-3 bg-success-500/10 border border-success-500/20 rounded-lg">
            <p class="text-sm text-success-400">{{ session('status') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-medium text-slate-300 mb-2">
                {{ __('Email') }}
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
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
            <input id="password" type="password" name="password" required autocomplete="current-password"
                class="input w-full" placeholder="Enter your password" />
            @error('password')
                <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="flex items-center">
                <input id="remember_me" type="checkbox" name="remember"
                    class="w-4 h-4 bg-dark-700 border-slate-600 rounded text-primary-500 focus:ring-primary-500/20 focus:ring-2">
                <span class="ml-2 text-sm text-slate-300">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-sm text-primary-400 hover:text-primary-300 underline">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="pt-4">
            <button type="submit" class="btn-primary w-full">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1">
                    </path>
                </svg>
                {{ __('Sign In') }}
            </button>
        </div>

        <div class="text-center pt-4 border-t border-slate-700">
            <p class="text-sm text-slate-400">
                Need an account? Contact your system administrator.
            </p>
        </div>
    </form>
</x-guest-layout>
