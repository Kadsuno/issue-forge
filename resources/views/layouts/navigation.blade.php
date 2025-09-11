<nav x-data="{ open: false, scrolled: false }" @scroll.window="scrolled = window.pageYOffset > 20"
    :class="scrolled ? 'md:backdrop-glass md:shadow-lg md:shadow-black/20' :
        'md:bg-gradient-to-b md:from-dark-800/60 md:to-dark-900/60 md:backdrop-blur-xl'"
    class="fixed top-0 left-0 right-0 z-40 transition-all duration-300 border-b border-dark-700/50 bg-dark-950/90">

    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 gap-2 md:gap-6">
            <div class="flex items-center max-w-full">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 group">
                        <!-- Modern Logo with Animation -->
                        <div class="relative">
                            <div
                                class="w-10 h-10 bg-gradient-to-br from-primary-500 to-accent-500 rounded-xl shadow-lg group-hover:shadow-glow-primary transition-all duration-300 transform group-hover:scale-110 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z">
                                    </path>
                                </svg>
                            </div>
                            <div
                                class="absolute -inset-1 bg-gradient-to-r from-primary-500 to-accent-500 rounded-xl blur opacity-30 group-hover:opacity-50 transition-opacity duration-300">
                            </div>
                        </div>
                        <div class="hidden xl:block">
                            <h1 class="text-xl font-bold text-gradient">IssueForge</h1>
                            <p class="text-xs text-slate-400 -mt-1">Forge Solutions From Issues</p>
                        </div>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="nav-items hidden sm:-my-px sm:ml-3 md:ml-5 lg:ml-6 xl:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="nav-link hover-lift hover-glow text-slate-200/90 hover:text-white hover:bg-dark-700/50 md:px-3 lg:px-3 xl:px-4 {{ request()->routeIs('dashboard') ? 'nav-link-active' : '' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                        </svg>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')"
                        class="nav-link hover-lift hover-glow text-slate-200/90 hover:text-white hover:bg-dark-700/50 md:px-3 lg:px-3 xl:px-4 {{ request()->routeIs('projects.*') ? 'nav-link-active' : '' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                        {{ __('Projects') }}
                    </x-nav-link>
                    @can('viewAny', \App\Models\User::class)
                        <x-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')"
                            class="nav-link hover-lift hover-glow text-slate-200/90 hover:text-white hover:bg-dark-700/50 md:px-3 lg:px-3 xl:px-4 {{ request()->routeIs('admin.users.*') ? 'nav-link-active' : '' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z">
                                </path>
                            </svg>
                            {{ __('Users') }}
                        </x-nav-link>
                    @endcan
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex items-center space-x-2 sm:space-x-4">
                <!-- Search Bar -->
                <!-- Tablet: show Search link -->
                <div class="hidden md:flex lg:hidden">
                    <a href="{{ route('search') }}" aria-label="Search"
                        class="inline-flex items-center p-2 text-slate-300 hover:text-white hover:bg-dark-700/50 rounded-lg transition-colors duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <span class="sr-only">Search</span>
                    </a>
                </div>

                <!-- Desktop and landscape tablet: show Search input -->
                <div class="hidden lg:block flex-1" x-data="{ focused: false }">
                    <form action="{{ route('search') }}" method="GET"
                        class="group relative w-full lg:max-w-lg xl:max-w-xl">
                        <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </span>
                        <div
                            class="rounded-xl bg-dark-900 border border-dark-800/80 shadow-inner group-focus-within:border-primary-500/40 group-focus-within:ring-1 group-focus-within:ring-primary-500/30">
                            <input type="text" name="q" placeholder="Quick search..." @focus="focused = true"
                                @blur="focused = false"
                                class="w-full h-10 pl-10 pr-4 bg-transparent text-sm text-slate-200 placeholder-slate-500 outline-none focus:ring-0 focus:border-0">
                        </div>
                    </form>
                </div>

                <!-- Notifications -->
                <div class="relative" x-data="{ show: false, loading: false, notifications: [], unread: 0, lastId: null }" x-init="(async () => {
                    try {
                        const r = await fetch('{{ route('notifications.index') }}', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                        const ct = r.headers.get('content-type') || '';
                        if (!r.ok || !ct.includes('application/json')) throw new Error('Non-JSON response');
                        const d = await r.json();
                        notifications = Array.isArray(d.notifications) ? d.notifications : [];
                        unread = Number(d.unread) || 0;
                        lastId = notifications.length ? notifications[0].id : null;
                    } catch (e) {
                        notifications = [];
                        unread = 0;
                    }
                    // Poll for realtime-ish updates
                    setInterval(async () => {
                        try {
                            const r = await fetch('{{ route('notifications.index') }}', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
                            const d = await r.json();
                            const prevUnread = unread;
                            const next = Array.isArray(d.notifications) ? d.notifications : [];
                            unread = Number(d.unread) || 0;
                            const newTopId = next.length ? next[0].id : null;
                            const isNew = newTopId && newTopId !== lastId;
                            if (isNew || unread > prevUnread) {
                                const n = next.length ? next[0] : null;
                                const title = n?.data?.ticket_title ? `${n.data.ticket_title}` : '';
                                const number = n?.data?.ticket_number ? `${n.data.ticket_number}` : '';
                                const msg = n?.data?.message || 'New notification';
                                const changesText = Array.isArray(n?.data?.changes) && n.data.changes.length ?
                                    n.data.changes.map(c => `${c.field}: ${c.old ?? '—'} → ${c.new ?? '—'}`).join(', ') :
                                    (n?.data?.snippet || '');
                                window.dispatchEvent(new CustomEvent('toast', {
                                    detail: {
                                        title: msg,
                                        body: [number, title].filter(Boolean).join(' — '),
                                        meta: changesText,
                                        url: n?.data?.url || null,
                                        type: 'info',
                                        timeout: 7000
                                    }
                                }));
                            }
                            notifications = next;
                            lastId = newTopId || lastId;
                        } catch (e) { /* noop */ }
                    }, 10000);
                })()">
                    <button
                        @click="show = !show; if (show) { loading = true; (async () => { try { const r = await fetch('{{ route('notifications.index') }}', { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } }); const ct = r.headers.get('content-type') || ''; if (!r.ok || !ct.includes('application/json')) throw new Error('Non-JSON response'); const d = await r.json(); notifications = Array.isArray(d.notifications) ? d.notifications : []; unread = Number(d.unread) || 0; } catch (e) { /* noop */ } finally { loading = false; } })(); }"
                        class="p-2 rounded-lg text-slate-400 hover:text-white hover:bg-dark-700/50 transition-colors duration-200 relative">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-5-5V9a4 4 0 10-8 0v3l-5 5h5m8 0v1a3 3 0 01-6 0v-1m6 0H9"></path>
                        </svg>
                        <template x-if="unread > 0">
                            <span
                                class="absolute -top-1 -right-1 z-10 w-5 h-5 rounded-full bg-danger-500 text-white text-[10px] font-bold flex items-center justify-center shadow-md ring-2 ring-dark-900"
                                x-text="unread > 99 ? '99' : unread"></span>
                        </template>
                    </button>

                    <!-- Notifications Dropdown -->
                    <div x-show="show" @click.away="show = false" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-80 backdrop-glass bg-dark-800/70 rounded-xl shadow-dark-lg border border-dark-600/50 overflow-hidden">
                        <div class="p-4 border-b border-dark-600/50 flex items-center justify-between">
                            <h3 class="text-sm font-semibold text-white">Notifications</h3>
                            <button
                                @click="(async () => { try { await fetch('{{ route('notifications.readAll') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } }); notifications = notifications.map(n => ({ ...n, read_at: (new Date()).toISOString() })); unread = 0; } catch (e) { /* noop */ } })()"
                                class="text-xs text-slate-400 hover:text-white">Mark all read</button>
                        </div>
                        <div class="max-h-64 overflow-y-auto">
                            <template x-if="loading">
                                <div class="p-4 text-center text-slate-400 text-sm">Loading...</div>
                            </template>
                            <template x-if="!loading && notifications.length === 0">
                                <div class="p-4 text-center text-slate-400 text-sm">No notifications</div>
                            </template>
                            <div class="divide-y divide-dark-700/50" x-show="!loading && notifications.length > 0">
                                <template x-for="n in notifications" :key="n.id">
                                    <div class="relative p-3 hover:bg-dark-700/30 pl-6 group cursor-pointer"
                                        :class="{ 'bg-dark-700/40': !n.read_at }">
                                        <template x-if="!n.read_at">
                                            <span
                                                class="absolute left-2 top-4 w-2 h-2 rounded-full bg-primary-400 shadow"></span>
                                        </template>
                                        <a :href="n.data?.url || '#'"
                                            class="block focus:outline-none focus:ring-2 focus:ring-primary-500/30 rounded-md">
                                            <div class="flex items-center justify-between">
                                                <div class="text-sm"
                                                    :class="{ 'text-white': !n.read_at, 'text-slate-300': !!n.read_at }">
                                                    <div :class="{ 'font-semibold': !n.read_at, 'font-medium': !!n.read_at }"
                                                        class="transition-colors underline-offset-4 group-hover:underline group-hover:text-primary-300"
                                                        x-text="n.data?.message || n.type"></div>
                                                    <template x-if="n.data?.ticket_number">
                                                        <div class="text-xs text-slate-400 mt-0.5">
                                                            <span class="text-slate-300"
                                                                x-text="n.data.ticket_number"></span>
                                                            <span x-text="' — ' + (n.data.ticket_title || '')"></span>
                                                        </div>
                                                    </template>
                                                    <template x-if="Array.isArray(n.data?.changes)">
                                                        <div class="text-xs text-slate-400 mt-1"
                                                            x-text="n.data.changes.map(c=>`${c.field}: ${c.old ?? '—'} → ${c.new ?? '—'}`).join(', ')">
                                                        </div>
                                                    </template>
                                                    <template x-if="n.data?.snippet">
                                                        <div class="text-xs text-slate-400 mt-1 line-clamp-2"
                                                            x-text="n.data.snippet"></div>
                                                    </template>
                                                </div>
                                                <div class="flex items-center ml-3">
                                                    <span class="text-xs text-slate-500" x-text="n.created_at"></span>
                                                    <svg class="w-4 h-4 ml-2 text-slate-500 opacity-0 group-hover:opacity-100 transition-opacity"
                                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </a>
                                        <div class="flex items-center justify-end gap-2 mt-2">
                                            <button x-show="!n.read_at"
                                                @click="(async () => { try { await fetch(`{{ url('/notifications') }}/${n.id}/read`, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } }); n.read_at = (new Date()).toISOString(); unread = Math.max(0, unread - 1); } catch (e) {} })()"
                                                class="text-xs text-primary-400 hover:text-primary-300">Mark
                                                read</button>
                                            <span x-show="!!n.read_at" class="text-xs text-slate-500">Read</span>
                                            <button
                                                @click="(async () => { try { await fetch(`{{ url('/notifications') }}/${n.id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } }); notifications = notifications.filter(x => x.id !== n.id); if (!n.read_at) unread = Math.max(0, unread - 1); } catch (e) {} })()"
                                                class="text-xs text-danger-400 hover:text-danger-300">Delete</button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Settings Dropdown -->
                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="w-64"
                        content-classes="p-0 bg-transparent ring-0 shadow-none">
                        <x-slot name="trigger">
                            <button
                                class="flex items-center px-3 py-2 text-sm font-medium text-slate-200 hover:text-white bg-dark-700/40 hover:bg-dark-600/40 rounded-lg transition-all duration-200 group backdrop-blur">
                                <!-- User Avatar -->
                                <div
                                    class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center mr-3 group-hover:scale-110 transition-transform duration-200">
                                    <span class="text-white text-sm font-semibold">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </span>
                                </div>

                                <div class="text-left">
                                    <div class="font-medium">{{ Auth::user()->name }}</div>
                                    <div class="text-xs text-slate-400 -mt-0.5">
                                        {{ substr(Auth::user()->email, 0, 20) }}...</div>
                                </div>

                                <svg class="ml-2 h-4 w-4 transition-transform duration-200 group-hover:rotate-180"
                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <div
                                class="backdrop-glass bg-dark-800/70 border border-dark-600/50 rounded-xl shadow-dark-lg overflow-hidden">
                                <div class="p-3 border-b border-dark-600/50">
                                    <div class="font-medium text-white">{{ Auth::user()->name }}</div>
                                    <div class="text-sm text-slate-400">{{ Auth::user()->email }}</div>
                                </div>

                                <x-dropdown-link :href="route('profile.edit')"
                                    class="flex items-center px-4 py-3 text-sm text-slate-300 hover:text-white hover:bg-dark-700 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z">
                                        </path>
                                    </svg>
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <div class="border-t border-dark-600/50">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();"
                                            class="flex items-center px-4 py-3 text-sm text-danger-400 hover:text-danger-300 hover:bg-danger-500/10 transition-colors duration-200">
                                            <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                                                </path>
                                            </svg>
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </div>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>

                <!-- Mobile menu button -->
                <div class="sm:hidden">
                    <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-slate-400 hover:text-white hover:bg-dark-700/50 focus:outline-none transition-all duration-200">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="hidden sm:hidden bg-dark-900/95 backdrop-blur-xl border-t border-dark-700/50">

        <!-- Mobile Search -->
        <div class="px-4 pt-3 pb-2">
            <form action="{{ route('search') }}" method="GET" class="group relative w-full">
                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center">
                    <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <div
                    class="rounded-xl bg-dark-900 border border-dark-800/80 shadow-inner group-focus-within:border-primary-500/40 group-focus-within:ring-1 group-focus-within:ring-primary-500/30">
                    <input type="text" name="q" placeholder="Quick search..."
                        class="w-full h-10 pl-10 pr-4 bg-transparent text-sm text-slate-200 placeholder-slate-500 outline-none focus:ring-0 focus:border-0">
                </div>
            </form>
        </div>

        <div class="pt-2 pb-3 space-y-1 px-4">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-dark-700/50 rounded-lg transition-all duration-200">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                </svg>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('projects.index')" :active="request()->routeIs('projects.*')"
                class="flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-dark-700/50 rounded-lg transition-all duration-200">
                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                    </path>
                </svg>
                {{ __('Projects') }}
            </x-responsive-nav-link>

            @can('viewAny', \App\Models\User::class)
                <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')"
                    class="flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-dark-700/50 rounded-lg transition-all duration-200">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z">
                        </path>
                    </svg>
                    {{ __('Users') }}
                </x-responsive-nav-link>
            @endcan
        </div>

        <!-- Mobile User Section -->
        <div class="pt-4 pb-1 border-t border-dark-700/50 px-4">
            <div class="flex items-center px-4 py-3">
                <div
                    class="w-10 h-10 bg-gradient-to-br from-primary-500 to-accent-500 rounded-full flex items-center justify-center mr-3">
                    <span class="text-white font-semibold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                </div>
                <div>
                    <div class="font-medium text-white">{{ Auth::user()->name }}</div>
                    <div class="text-sm text-slate-400">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')"
                    class="flex items-center px-4 py-3 text-slate-300 hover:text-white hover:bg-dark-700/50 rounded-lg transition-all duration-200">
                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="flex items-center px-4 py-3 text-danger-400 hover:text-danger-300 hover:bg-danger-500/10 rounded-lg transition-all duration-200">
                        <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Spacer for fixed navigation -->
<div class="h-16"></div>
