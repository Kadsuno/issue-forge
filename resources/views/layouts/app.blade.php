<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'IssueForge') }}</title>

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('favicon.svg') }}">

    <!-- Web App Manifest -->
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">

    <!-- Theme Color -->
    <meta name="theme-color" content="#6366f1">
    <meta name="msapplication-TileColor" content="#6366f1">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">



    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">

    <!-- Alpine is bundled via Vite in resources/js/app.js -->
    <script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>
</head>

<body class="font-sans antialiased main-content">
    <div class="min-h-screen relative">
        <!-- Background Effects -->
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div
                class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-br from-primary-500/10 to-transparent rounded-full blur-3xl animate-pulse-slow">
            </div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-gradient-to-tr from-accent-500/10 to-transparent rounded-full blur-3xl animate-pulse-slow"
                style="animation-delay: 1s;"></div>
        </div>

        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="page-header relative z-10">
                <div class="max-w-7xl mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8">
                    <div class="animate-fade-in-up">
                        {{ $header }}
                    </div>
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="relative z-10">
            <div class="animate-fade-in-up" style="animation-delay: 0.1s;">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="relative z-10 mt-12 border-t border-dark-700/50">
            <div
                class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6 pb-12 md:pb-8 flex flex-col md:flex-col lg:flex-row items-center md:items-center lg:items-center md:justify-center lg:justify-between gap-4 md:gap-2 lg:gap-0 text-sm">
                <div class="text-slate-400">
                    © {{ date('Y') }} {{ config('app.name', 'IssueForge') }}. All rights reserved.
                </div>
                <nav
                    class="mt-3 md:mt-2 lg:mt-0 flex flex-wrap justify-center md:justify-center lg:justify-end gap-x-4 md:gap-x-6 lg:gap-x-8 gap-y-2 text-xs sm:text-sm text-slate-400">
                    <a href="{{ route('dashboard') }}"
                        class="px-2 py-1 rounded hover:text-white hover:bg-dark-700/40">Dashboard</a>
                    <a href="{{ route('projects.index') }}"
                        class="px-2 py-1 rounded hover:text-white hover:bg-dark-700/40">Projects</a>
                    <a href="{{ route('tickets.mine') }}"
                        class="px-2 py-1 rounded hover:text-white hover:bg-dark-700/40">My Tickets</a>
                    <span class="hidden md:inline text-slate-600">|</span>
                    <a href="{{ route('imprint') }}"
                        class="px-2 py-1 rounded hover:text-white hover:bg-dark-700/40">Imprint</a>
                    <a href="{{ route('privacy') }}"
                        class="px-2 py-1 rounded hover:text-white hover:bg-dark-700/40">Privacy Policy</a>
                </nav>
            </div>
        </footer>

        <!-- Floating Action Button with Quick Actions -->
        <div class="hidden lg:block fixed bottom-6 right-6 z-50" x-data="{ open: false, tooltip: false }"
            @keydown.escape.window="open=false">
            <button @click="open = !open"
                class="btn-primary rounded-full p-4 shadow-2xl hover:shadow-glow-primary transition-all duration-300 group relative"
                @mouseenter="tooltip = true" @mouseleave="tooltip = false" aria-label="Quick Actions">
                <svg class="w-6 h-6 group-hover:rotate-45 transition-transform duration-300" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>

                <!-- Tooltip -->
                <div x-show="tooltip && !open" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                    x-transition:leave-end="opacity-0 scale-95"
                    class="absolute bottom-full right-0 mb-2 px-3 py-1 bg-dark-800 text-white text-sm rounded-lg shadow-lg whitespace-nowrap">
                    Quick Actions
                </div>
            </button>

            <!-- Quick Actions Panel -->
            <div x-show="open" @click.outside="open=false" x-transition:enter="transition ease-out duration-150"
                x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-100"
                x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-2"
                class="absolute right-0 bottom-full mb-3 w-56 card p-2 origin-bottom-right">
                @unless (request()->routeIs('projects.index'))
                    <a href="{{ route('projects.create') }}"
                        class="flex items-center px-3 py-2 rounded-lg hover:bg-dark-700/40 transition-colors duration-150">
                        <svg class="w-4 h-4 mr-2 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span class="text-sm text-slate-200">New Project</span>
                    </a>
                @endunless
                <a href="{{ route('tickets.mine') }}"
                    class="flex items-center px-3 py-2 rounded-lg hover:bg-dark-700/40 transition-colors duration-150">
                    <svg class="w-4 h-4 mr-2 text-success-400" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586l5.414 5.414V19a2 2 0 01-2 2z" />
                    </svg>
                    <span class="text-sm text-slate-200">My Tickets</span>
                </a>
                <a href="{{ route('search', ['q' => '']) }}"
                    class="flex items-center px-3 py-2 rounded-lg hover:bg-dark-700/40 transition-colors duration-150">
                    <svg class="w-4 h-4 mr-2 text-slate-300" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    <span class="text-sm text-slate-200">Search</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Page Load Animation -->
    <div id="page-loader" class="fixed inset-0 bg-dark-950 z-50 flex items-center justify-center">
        <div class="flex space-x-2">
            <div class="w-3 h-3 bg-primary-500 rounded-full animate-bounce"></div>
            <div class="w-3 h-3 bg-accent-500 rounded-full animate-bounce" style="animation-delay: 0.1s;"></div>
            <div class="w-3 h-3 bg-success-500 rounded-full animate-bounce" style="animation-delay: 0.2s;"></div>
        </div>
    </div>

    <!-- Toasts (slide in from bottom-right) -->
    <div x-data="{
        toasts: [],
        add(d) {
            const id = Date.now() + Math.random();
            const detail = d || {};
            const t = {
                id,
                title: detail.title || detail.message || 'Notification',
                body: detail.body || detail.subtitle || null,
                meta: detail.meta || null,
                url: detail.url || null,
                type: detail.type || 'info',
                timeoutMs: Number(detail.timeout || 6000),
                closing: false,
            };
            this.toasts.push(t);
            t._timer = setTimeout(() => this.close(id), t.timeoutMs);
        },
        close(id) {
            const t = this.toasts.find(x => x.id === id);
            if (!t) return;
            t.closing = true;
            if (t._timer) clearTimeout(t._timer);
            setTimeout(() => this.remove(id), 250);
        },
        remove(id) {
            const i = this.toasts.findIndex(x => x.id === id);
            if (i !== -1) {
                const t = this.toasts[i];
                if (t && t._timer) clearTimeout(t._timer);
                this.toasts.splice(i, 1);
            }
        }
    }" @toast.window="add($event.detail || {})"
        class="fixed bottom-4 right-4 z-[60] space-y-3">
        <template x-for="t in toasts" :key="t.id">
            <div x-show="!t.closing" @mouseenter="t._timer && clearTimeout(t._timer)"
                @mouseleave="t._timer = setTimeout(() => close(t.id), 1400)"
                @click="t.url ? (window.location.href = t.url) : close(t.id)"
                x-transition:enter="transition transform ease-out duration-400"
                x-transition:enter-start="opacity-0 translate-y-6 scale-[0.98] blur-sm"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100 blur-0"
                x-transition:leave="transition transform ease-in duration-250"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100 blur-0"
                x-transition:leave-end="opacity-0 translate-y-4 scale-95 blur-sm"
                class="relative min-w-[300px] max-w-md px-4 py-3 rounded-xl shadow-2xl border backdrop-glass cursor-pointer">
                <div class="absolute left-0 top-0 bottom-0 w-1 rounded-l-xl"
                    :class="{
                        'bg-gradient-to-b from-primary-500 to-accent-500': t.type==='info',
                        'bg-success-500': t.type==='success',
                        'bg-danger-500': t.type==='error'
                    }">
                </div>
                <div class="flex items-start pr-8"
                    :class="{
                        'text-slate-100': t.type==='info',
                        'text-success-100': t.type==='success',
                        'text-danger-100': t.type==='error'
                    }">
                    <div class="mt-0.5 mr-3 shrink-0">
                        <template x-if="t.type==='success'">
                            <svg class="w-5 h-5 text-success-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                        </template>
                        <template x-if="t.type==='error'">
                            <svg class="w-5 h-5 text-danger-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </template>
                        <template x-if="t.type==='info'">
                            <svg class="w-5 h-5 text-primary-400" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                            </svg>
                        </template>
                    </div>
                    <div class="text-sm leading-5">
                        <div class="font-semibold" x-text="t.title"></div>
                        <template x-if="t.body">
                            <div class="text-slate-300 mt-0.5" x-text="t.body"></div>
                        </template>
                        <template x-if="t.meta">
                            <div class="text-slate-400 mt-1 text-xs" x-text="t.meta"></div>
                        </template>
                    </div>
                    <template x-if="t.url">
                        <svg class="w-4 h-4 ml-3 text-slate-400 absolute right-3 top-1/2 -translate-y-1/2"
                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </template>
                </div>

                <div class="absolute inset-0 -z-10 rounded-xl"
                    :class="{
                        'shadow-[0_0_30px_-10px_rgba(99,102,241,0.6)]': t.type==='info',
                        'shadow-[0_0_30px_-12px_rgba(34,197,94,0.6)]': t.type==='success',
                        'shadow-[0_0_30px_-12px_rgba(239,68,68,0.6)]': t.type==='error'
                    }">
                </div>
                <div class="absolute inset-0 rounded-xl border"
                    :class="{
                        'border-dark-600/60 bg-dark-800/90': t.type==='info',
                        'border-success-700/40 bg-success-900/70': t.type==='success',
                        'border-danger-700/40 bg-danger-900/70': t.type==='error'
                    }">
                </div>
            </div>
        </template>
    </div>

    <script>
        // Hide loader after page load
        window.addEventListener('load', function() {
            setTimeout(() => {
                document.getElementById('page-loader').style.opacity = '0';
                setTimeout(() => {
                    document.getElementById('page-loader').style.display = 'none';
                }, 300);
            }, 100);
        });

        // Toasts are handled by the Alpine container above. Dispatch with:
        // window.dispatchEvent(new CustomEvent('toast', { detail: { message: '...', type: 'info|success|error' } }))
    </script>
</body>

</html>
