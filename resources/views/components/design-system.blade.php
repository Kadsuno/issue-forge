{{-- Design System Showcase Component --}}
<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Design System</h2>
                        <p class="text-sm text-slate-400">Modern Dark Theme Components</p>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-12">

            {{-- Color Palette --}}
            <div class="card p-8">
                <h3 class="text-2xl font-bold text-white mb-6">Color Palette</h3>

                {{-- Primary Colors --}}
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-white mb-4">Primary Colors</h4>
                    <div class="grid grid-cols-2 md:grid-cols-5 lg:grid-cols-10 gap-3">
                        @foreach (['50', '100', '200', '300', '400', '500', '600', '700', '800', '900'] as $shade)
                            <div class="text-center">
                                <div
                                    class="w-16 h-16 bg-primary-{{ $shade }} rounded-lg mb-2 border border-white/10">
                                </div>
                                <div class="text-xs text-slate-400">{{ $shade }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Accent Colors --}}
                <div class="mb-8">
                    <h4 class="text-lg font-semibold text-white mb-4">Accent Colors</h4>
                    <div class="grid grid-cols-2 md:grid-cols-5 lg:grid-cols-10 gap-3">
                        @foreach (['50', '100', '200', '300', '400', '500', '600', '700', '800', '900'] as $shade)
                            <div class="text-center">
                                <div
                                    class="w-16 h-16 bg-accent-{{ $shade }} rounded-lg mb-2 border border-white/10">
                                </div>
                                <div class="text-xs text-slate-400">{{ $shade }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Status Colors --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div>
                        <h4 class="text-sm font-semibold text-success-400 mb-3">Success</h4>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach (['400', '500', '600'] as $shade)
                                <div class="w-12 h-12 bg-success-{{ $shade }} rounded-lg"></div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-warning-400 mb-3">Warning</h4>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach (['400', '500', '600'] as $shade)
                                <div class="w-12 h-12 bg-warning-{{ $shade }} rounded-lg"></div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-danger-400 mb-3">Danger</h4>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach (['400', '500', '600'] as $shade)
                                <div class="w-12 h-12 bg-danger-{{ $shade }} rounded-lg"></div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-slate-400 mb-3">Dark</h4>
                        <div class="grid grid-cols-3 gap-2">
                            @foreach (['700', '800', '900'] as $shade)
                                <div class="w-12 h-12 bg-dark-{{ $shade }} rounded-lg border border-white/10">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="card p-8">
                <h3 class="text-2xl font-bold text-white mb-6">Buttons</h3>
                <div class="space-y-6">
                    <div>
                        <h4 class="text-lg font-semibold text-white mb-4">Button Variants</h4>
                        <div class="flex flex-wrap gap-4">
                            <button class="btn-primary">Primary Button</button>
                            <button class="btn-secondary">Secondary Button</button>
                            <button class="btn-accent">Accent Button</button>
                            <button class="btn-ghost">Ghost Button</button>
                            <button class="btn-danger">Danger Button</button>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold text-white mb-4">Button Sizes</h4>
                        <div class="flex flex-wrap items-center gap-4">
                            <button class="btn-primary px-3 py-1 text-sm">Small</button>
                            <button class="btn-primary">Default</button>
                            <button class="btn-primary px-6 py-3 text-lg">Large</button>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold text-white mb-4">Button with Icons</h4>
                        <div class="flex flex-wrap gap-4">
                            <button class="btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Item
                            </button>
                            <button class="btn-secondary">
                                Download
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cards --}}
            <div class="card p-8">
                <h3 class="text-2xl font-bold text-white mb-6">Cards</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div class="card p-6">
                        <h4 class="text-lg font-semibold text-white mb-2">Basic Card</h4>
                        <p class="text-slate-400">Simple card with basic styling</p>
                    </div>

                    <div class="card-glass p-6">
                        <h4 class="text-lg font-semibold text-white mb-2">Glass Card</h4>
                        <p class="text-slate-400">Card with glassmorphism effect</p>
                    </div>

                    <div class="card-hover p-6">
                        <h4 class="text-lg font-semibold text-white mb-2">Hover Card</h4>
                        <p class="text-slate-400">Card with hover animations</p>
                    </div>

                    <div class="card-glow p-6">
                        <h4 class="text-lg font-semibold text-white mb-2">Glow Card</h4>
                        <p class="text-slate-400">Card with glow effect on hover</p>
                    </div>

                    <div class="card p-6 glow-border">
                        <h4 class="text-lg font-semibold text-white mb-2">Border Glow</h4>
                        <p class="text-slate-400">Card with animated border glow</p>
                    </div>

                    <div class="card p-6 shimmer">
                        <h4 class="text-lg font-semibold text-white mb-2">Shimmer Card</h4>
                        <p class="text-slate-400">Card with shimmer animation</p>
                    </div>
                </div>
            </div>

            {{-- Form Elements --}}
            <div class="card p-8">
                <h3 class="text-2xl font-bold text-white mb-6">Form Elements</h3>
                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Input Field</label>
                            <input type="text" class="input" placeholder="Enter your text">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">Error Input</label>
                            <input type="text" class="input-error" placeholder="Invalid input">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Textarea</label>
                        <textarea class="input" rows="4" placeholder="Enter your message"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">Select</label>
                        <select class="input">
                            <option>Option 1</option>
                            <option>Option 2</option>
                            <option>Option 3</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Badges & Status --}}
            <div class="card p-8">
                <h3 class="text-2xl font-bold text-white mb-6">Badges & Status</h3>
                <div class="space-y-4">
                    <div>
                        <h4 class="text-lg font-semibold text-white mb-3">Badge Variants</h4>
                        <div class="flex flex-wrap gap-3">
                            <span class="badge-primary">Primary</span>
                            <span class="badge-success">Success</span>
                            <span class="badge-warning">Warning</span>
                            <span class="badge-danger">Danger</span>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold text-white mb-3">Status Indicators</h4>
                        <div class="space-y-2">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-success-500 rounded-full"></div>
                                <span class="text-slate-300">Online</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-warning-500 rounded-full animate-pulse"></div>
                                <span class="text-slate-300">Away</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-slate-500 rounded-full"></div>
                                <span class="text-slate-300">Offline</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Typography --}}
            <div class="card p-8">
                <h3 class="text-2xl font-bold text-white mb-6">Typography</h3>
                <div class="space-y-4">
                    <div>
                        <h1 class="text-4xl font-bold text-white">Heading 1</h1>
                        <h2 class="text-3xl font-bold text-white">Heading 2</h2>
                        <h3 class="text-2xl font-bold text-white">Heading 3</h3>
                        <h4 class="text-xl font-semibold text-white">Heading 4</h4>
                        <h5 class="text-lg font-medium text-white">Heading 5</h5>
                        <h6 class="text-base font-medium text-white">Heading 6</h6>
                    </div>

                    <div class="space-y-2">
                        <p class="text-slate-300">Regular paragraph text with normal styling.</p>
                        <p class="text-slate-400">Muted text for secondary information.</p>
                        <p class="text-sm text-slate-500">Small text for captions and labels.</p>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold text-white mb-3">Text Effects</h4>
                        <div class="space-y-2">
                            <p class="text-gradient text-2xl font-bold">Gradient Text Effect</p>
                            <p class="text-gradient-accent text-2xl font-bold">Accent Gradient Text</p>
                            <p class="text-white text-shadow-lg text-2xl font-bold">Text with Shadow</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Animations --}}
            <div class="card p-8">
                <h3 class="text-2xl font-bold text-white mb-6">Animations</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="w-16 h-16 bg-primary-500 rounded-lg mx-auto mb-3 animate-bounce-in"></div>
                        <p class="text-slate-300">Bounce In</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-accent-500 rounded-lg mx-auto mb-3 animate-fade-in-up"></div>
                        <p class="text-slate-300">Fade In Up</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-success-500 rounded-lg mx-auto mb-3 animate-slide-in-right"></div>
                        <p class="text-slate-300">Slide In Right</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-warning-500 rounded-lg mx-auto mb-3 animate-pulse-slow"></div>
                        <p class="text-slate-300">Pulse Slow</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-primary-500 rounded-lg mx-auto mb-3 animate-glow"></div>
                        <p class="text-slate-300">Glow Effect</p>
                    </div>

                    <div class="text-center">
                        <div class="w-16 h-16 bg-accent-500 rounded-lg mx-auto mb-3 hover-lift"></div>
                        <p class="text-slate-300">Hover Lift</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
