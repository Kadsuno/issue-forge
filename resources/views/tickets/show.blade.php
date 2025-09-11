<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start md:items-center gap-3 md:gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">{{ $ticket->title }}</h2>
                        <p class="text-sm text-slate-400">{{ $ticket->project->name }}</p>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-auto flex items-center justify-between md:justify-end gap-3">
                <a href="{{ route('projects.show', $ticket->project->slug) }}"
                    class="btn-ghost w-full md:w-auto text-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Project
                </a>
                <a href="{{ route('tickets.edit', $ticket) }}" class="btn-primary w-full md:w-auto text-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Ticket
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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

            <div class="card p-6 sm:p-8 animate-fade-in-up">
                <!-- Ticket Header -->
                <div class="flex flex-col md:flex-row md:justify-between md:items-start mb-8 gap-4">
                    <div class="flex-1">
                        <h1 class="text-2xl font-bold text-white mb-2"><span
                                class="text-slate-500 mr-2">{{ $ticket->number }}</span>{{ $ticket->title }}</h1>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 md:ml-6">
                        @php
                            $priorityClass = match ($ticket->priority) {
                                'urgent' => 'badge-danger',
                                'high' => 'badge-danger',
                                'medium' => 'badge-warning',
                                default => 'badge-success',
                            };

                            $statusClass = match ($ticket->status) {
                                'open' => 'badge-primary',
                                'in_progress' => 'badge-warning',
                                'resolved' => 'badge-success',
                                'closed' => 'badge-secondary',
                                default => 'badge-primary',
                            };
                            $typeClass = 'badge-secondary';
                        @endphp
                        <span class="{{ $priorityClass }}">
                            {{ ucfirst($ticket->priority) }}
                        </span>
                        <span class="{{ $statusClass }}">
                            {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                        </span>
                        <span class="{{ $typeClass }}">
                            {{ ucfirst($ticket->type ?? 'task') }}
                        </span>
                    </div>
                </div>

                @if ($ticket->description)
                    <x-description-card :content="Str::markdown($ticket->description, ['html_input' => 'strip', 'allow_unsafe_links' => false])" />
                @endif

                <!-- Ticket Metadata -->
                <div
                    class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 p-4 sm:p-6 bg-dark-700/30 rounded-lg border border-slate-700">
                    <div>
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Created by</label>
                        <div class="flex items-center mt-1">
                            <div
                                class="w-6 h-6 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center mr-2">
                                <span
                                    class="text-xs font-bold text-white">{{ substr($ticket->user->name, 0, 1) }}</span>
                            </div>
                            <span class="text-sm text-white">{{ $ticket->user->name }}</span>
                        </div>
                    </div>

                    @if ($ticket->assignedUser)
                        <div>
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Assigned
                                to</label>
                            <div class="flex items-center mt-1">
                                <div
                                    class="w-6 h-6 bg-gradient-to-br from-accent-500 to-accent-600 rounded-full flex items-center justify-center mr-2">
                                    <span
                                        class="text-xs font-bold text-white">{{ substr($ticket->assignedUser->name, 0, 1) }}</span>
                                </div>
                                <span class="text-sm text-white">{{ $ticket->assignedUser->name }}</span>
                            </div>
                        </div>
                    @else
                        <div>
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Assigned
                                to</label>
                            <span class="text-sm text-slate-400 mt-1 block">Unassigned</span>
                        </div>
                    @endif

                    <div>
                        <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Created</label>
                        <span class="text-sm text-white mt-1 block">{{ $ticket->created_at->format('M j, Y') }}</span>
                    </div>

                    @if ($ticket->due_date)
                        <div>
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Due Date</label>
                            <span
                                class="text-sm {{ $ticket->isOverdue() ? 'text-danger-400' : 'text-white' }} mt-1 block">
                                {{ $ticket->due_date->format('M j, Y') }}
                                @if ($ticket->isOverdue())
                                    (Overdue)
                                @endif
                            </span>
                        </div>
                    @endif

                    @if ($ticket->estimate_minutes)
                        <div>
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Estimate</label>
                            <span
                                class="text-sm text-white mt-1 block">{{ sprintf('%d:%02d', intdiv($ticket->estimate_minutes, 60), $ticket->estimate_minutes % 60) }}</span>
                        </div>
                    @endif

                    @if ($ticket->severity)
                        <div>
                            <label class="text-xs font-medium text-slate-500 uppercase tracking-wide">Severity</label>
                            <span class="text-sm text-white mt-1 block">{{ ucfirst($ticket->severity) }}</span>
                        </div>
                    @endif
                </div>

                <!-- Quick Actions -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pt-8 border-t border-slate-700 mt-8">
                    <div class="text-sm text-slate-400">
                        Last updated {{ $ticket->updated_at->diffForHumans() }}
                    </div>
                </div>

                @if ($ticket->labels)
                    <div class="mt-6">
                        <div class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">Labels</div>
                        <div class="flex flex-wrap gap-2">
                            @foreach (array_filter(array_map('trim', explode(',', $ticket->labels))) as $label)
                                <span class="badge-secondary">{{ $label }}</span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Watchers -->
                <div class="mt-6">
                    <div class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">Watchers</div>
                    <div class="flex flex-wrap gap-2 mb-3">
                        @forelse ($ticket->watchers as $watcher)
                            <form action="{{ route('tickets.watchers.destroy', [$ticket, $watcher]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="badge-secondary hover:bg-danger-500/20">
                                    {{ $watcher->name }} ×
                                </button>
                            </form>
                        @empty
                            <span class="text-slate-400 text-sm">No watchers yet</span>
                        @endforelse
                    </div>
                    <form action="{{ route('tickets.watchers.store', $ticket) }}" method="POST"
                        class="flex flex-wrap gap-2 items-center">
                        @csrf
                        <select name="user_id" class="input w-full sm:w-auto">
                            @foreach (\App\Models\User::orderBy('name')->get() as $u)
                                <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }})
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-primary w-full sm:w-auto">Add watcher</button>
                    </form>
                </div>

                <!-- Time tracking -->
                <div class="mt-6">
                    <div class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">Time tracking</div>
                    <form action="{{ route('tickets.time.store', $ticket) }}" method="POST"
                        class="flex flex-wrap gap-3 items-end mb-4">
                        @csrf
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">Time (H:MM)</label>
                            <input type="text" name="minutes" class="input" placeholder="1:30"
                                pattern="\d{1,3}:\d{2}" required>
                        </div>
                        <div>
                            <label class="block text-xs text-slate-400 mb-1">Date</label>
                            <input type="date" name="spent_at" value="{{ date('Y-m-d') }}" class="input"
                                required>
                        </div>
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-xs text-slate-400 mb-1">Note (optional)</label>
                            <input type="text" name="note" class="input w-full"
                                placeholder="What did you work on?">
                        </div>
                        <button type="submit" class="btn-primary">Book time</button>
                    </form>
                    @php($entries = $ticket->timeEntries()->with('user')->latest('spent_at')->get())
                    @if ($entries->count())
                        @php($total = $entries->sum('minutes'))
                        <div class="text-xs text-slate-400 mb-2">Total: <span
                                class="text-slate-200 font-semibold">{{ sprintf('%d:%02d', intdiv($total, 60), $total % 60) }}</span>
                        </div>
                        <div class="divide-y divide-slate-700/60">
                            @foreach ($entries as $entry)
                                <div class="py-2 flex items-center justify-between">
                                    <div class="text-sm text-slate-200">
                                        <span
                                            class="text-slate-400 mr-2">{{ $entry->spent_at->format('M j, Y') }}</span>
                                        {{ sprintf('%d:%02d', intdiv($entry->minutes, 60), $entry->minutes % 60) }} —
                                        {{ $entry->user->name }}
                                        @if ($entry->note)
                                            <span class="text-slate-400"> · {{ $entry->note }}</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <details class="relative">
                                            <summary class="btn-ghost cursor-pointer list-none">Edit</summary>
                                            <form action="{{ route('tickets.time.update', $entry) }}" method="POST"
                                                class="absolute right-0 mt-2 card p-3 z-10 w-72">
                                                @csrf
                                                @method('PATCH')
                                                <div class="mb-2">
                                                    <label class="block text-xs text-slate-400 mb-1">Time
                                                        (H:MM)
                                                    </label>
                                                    <input type="text" name="minutes" class="input w-full"
                                                        value="{{ sprintf('%d:%02d', intdiv($entry->minutes, 60), $entry->minutes % 60) }}"
                                                        pattern="\d{1,3}:\d{2}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="block text-xs text-slate-400 mb-1">Date</label>
                                                    <input type="date" name="spent_at"
                                                        value="{{ $entry->spent_at->format('Y-m-d') }}"
                                                        class="input w-full" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="block text-xs text-slate-400 mb-1">Note</label>
                                                    <input type="text" name="note" value="{{ $entry->note }}"
                                                        class="input w-full">
                                                </div>
                                                <div class="flex justify-end gap-2">
                                                    <button type="submit" class="btn-primary btn-sm">Save</button>
                                                </div>
                                            </form>
                                        </details>
                                        <form action="{{ route('tickets.time.destroy', $entry) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-ghost">Remove</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-sm text-slate-400">No time booked yet.</div>
                    @endif
                </div>

                @if ($ticket->parent)
                    <div class="mt-6">
                        <div class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">Parent ticket
                        </div>
                        <a href="{{ route('tickets.show', $ticket->parent) }}"
                            class="text-slate-300 hover:text-primary-400">
                            {{ $ticket->parent->number }} — {{ $ticket->parent->title }}
                        </a>
                    </div>
                @endif

                @if ($ticket->children()->count())
                    <div class="mt-6">
                        <div class="text-xs font-medium text-slate-500 uppercase tracking-wide mb-2">Sub-tickets</div>
                        <ul class="space-y-2">
                            @foreach ($ticket->children as $child)
                                <li>
                                    <a href="{{ route('tickets.show', $child) }}"
                                        class="text-slate-300 hover:text-primary-400">
                                        {{ $child->number }} — {{ $child->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Comments -->
                <div class="mt-6" id="comment-form">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-white">Comments</h3>
                    </div>
                    @php($comments = $ticket->comments()->with('user')->latest()->get())
                    @if ($comments->count())
                        <div class="space-y-4">
                            @foreach ($comments as $comment)
                                <div class="p-4 bg-dark-700/30 rounded-lg border border-slate-700">
                                    <div class="flex items-center justify-between mb-2 gap-2 flex-wrap">
                                        <div class="flex items-center">
                                            <div
                                                class="w-6 h-6 bg-gradient-to-br from-primary-500 to-primary-600 rounded-full flex items-center justify-center mr-2">
                                                <span
                                                    class="text-xs font-bold text-white">{{ substr($comment->user->name, 0, 1) }}</span>
                                            </div>
                                            <span class="text-sm text-white">{{ $comment->user->name }}</span>
                                        </div>
                                        <div class="flex items-center gap-2 md:gap-4 flex-wrap justify-end">
                                            <span class="text-xs text-slate-500"
                                                title="{{ $comment->created_at->diffForHumans() }}">{{ $comment->created_at->format('M j, Y H:i') }}</span>
                                            <a href="{{ route('comments.edit', $comment) }}"
                                                class="btn-ghost">Edit</a>
                                            <form action="{{ route('tickets.comments.destroy', $comment) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-ghost">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="comment-markdown text-sm leading-relaxed">
                                        {!! Str::markdown($comment->body, ['html_input' => 'strip', 'allow_unsafe_links' => false]) !!}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-sm text-slate-400">No comments yet.</div>
                    @endif

                    <form action="{{ route('tickets.comments.store', $ticket) }}" method="POST"
                        class="mt-6 space-y-3">
                        @csrf
                        <textarea name="body" id="comment-body" rows="6" class="input w-full"
                            placeholder="Write a comment using Markdown...">{{ old('body') }}</textarea>
                        @error('body')
                            <p class="text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Add Comment
                            </button>
                        </div>
                    </form>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            if (window.SimpleMDE) {
                                const mde = new SimpleMDE({
                                    element: document.getElementById('comment-body'),
                                    spellChecker: false,
                                    status: false,
                                    autoDownloadFontAwesome: true,
                                    placeholder: 'Write a comment using Markdown...',
                                    toolbar: [
                                        'bold', 'italic', 'heading', '|',
                                        'quote', 'code', 'table', '|',
                                        'unordered-list', 'ordered-list', '|',
                                        'link', 'image', 'horizontal-rule', '|',
                                        'preview'
                                    ],
                                });

                                // Observe side-by-side state and adjust layout widths
                                const container = document.getElementById('comment-form');
                                const codeMirror = container.querySelector('.CodeMirror');
                                const previewSide = container.querySelector('.editor-preview-side');
                                const applySbsClass = () => {
                                    const active = previewSide && previewSide.classList.contains('editor-preview-active-side');
                                    container.classList.toggle('sbs-active', !!active);
                                    if (active) {
                                        codeMirror && (codeMirror.style.width = '50%');
                                        previewSide && (previewSide.style.width = '50%');
                                    } else {
                                        codeMirror && (codeMirror.style.width = '100%');
                                        previewSide && (previewSide.style.width = '');
                                    }
                                };
                                const obs = new MutationObserver(applySbsClass);
                                if (previewSide) {
                                    obs.observe(previewSide, {
                                        attributes: true,
                                        attributeFilter: ['class']
                                    });
                                }
                                // also observe CodeMirror sided class
                                if (codeMirror) {
                                    obs.observe(codeMirror, {
                                        attributes: true,
                                        attributeFilter: ['class']
                                    });
                                }
                                applySbsClass();
                            }

                            // Initialize SimpleMDE lazily when an edit panel opens to avoid layout glitches
                            document.querySelectorAll('details[data-comment-edit]').forEach((detailsEl) => {
                                detailsEl.addEventListener('toggle', () => {
                                    if (!detailsEl.open) return;
                                    const textarea = detailsEl.querySelector(
                                        'textarea[data-editor="comment-edit"]');
                                    if (!textarea) return;

                                    // Create once, then refresh on subsequent opens
                                    if (!textarea._mde && window.SimpleMDE) {
                                        textarea._mde = new SimpleMDE({
                                            element: textarea,
                                            spellChecker: false,
                                            status: false,
                                            autoDownloadFontAwesome: true,
                                            toolbar: [
                                                'bold', 'italic', 'heading', '|',
                                                'quote', 'code', 'table', '|',
                                                'unordered-list', 'ordered-list', '|',
                                                'link', 'image', 'horizontal-rule', '|',
                                                'preview'
                                            ],
                                        });
                                    }

                                    // When opened, force a refresh so widths are calculated correctly
                                    if (textarea._mde) {
                                        requestAnimationFrame(() => {
                                            textarea._mde.codemirror.refresh();
                                            textarea._mde.codemirror.focus();
                                        });
                                    }
                                });
                            });
                        });
                    </script>
                    <style>
                        /* Comment markdown rendering to match preview */
                        .comment-markdown {
                            color: #e2e8f0;
                        }

                        .comment-markdown h1,
                        .comment-markdown h2,
                        .comment-markdown h3,
                        .comment-markdown h4,
                        .comment-markdown h5,
                        .comment-markdown h6 {
                            color: #fff !important;
                            font-weight: 700 !important;
                            margin-top: 0.5rem !important;
                            margin-bottom: 0.5rem !important;
                            line-height: 1.25 !important;
                        }

                        .comment-markdown h1 {
                            font-size: 1.50rem !important;
                        }

                        .comment-markdown h2 {
                            font-size: 1.35rem !important;
                        }

                        .comment-markdown h3 {
                            font-size: 1.20rem !important;
                        }

                        .comment-markdown h4 {
                            font-size: 1.10rem !important;
                        }

                        .comment-markdown h5 {
                            font-size: 1.05rem !important;
                        }

                        .comment-markdown h6 {
                            font-size: 1.00rem !important;
                        }

                        .comment-markdown a {
                            color: #93c5fd;
                            text-decoration: underline;
                        }

                        .comment-markdown code {
                            background: rgba(2, 6, 23, 0.6);
                            color: #eab308;
                            padding: 0.1rem 0.35rem;
                            border-radius: 0.25rem;
                        }

                        .comment-markdown pre {
                            background: rgba(2, 6, 23, 0.7);
                            color: #e2e8f0;
                            border: 1px solid rgba(51, 65, 85, .6);
                            border-radius: .5rem;
                            padding: .75rem 1rem;
                            overflow: auto;
                        }

                        .comment-markdown table {
                            width: 100%;
                            border-collapse: collapse;
                        }

                        .comment-markdown th,
                        .comment-markdown td {
                            border: 1px solid rgba(71, 85, 105, .7);
                            padding: .4rem .6rem;
                        }

                        .comment-markdown th {
                            background: rgba(30, 41, 59, .6);
                        }

                        .comment-markdown blockquote {
                            border-left: 3px solid rgba(99, 102, 241, .6);
                            padding-left: .75rem;
                            color: #cbd5e1;
                        }

                        .comment-markdown ul {
                            list-style: disc;
                            padding-left: 1.25rem;
                            margin: .5rem 0;
                        }

                        .comment-markdown ol {
                            list-style: decimal;
                            padding-left: 1.25rem;
                            margin: .5rem 0;
                        }

                        .comment-markdown li {
                            margin: .25rem 0;
                        }

                        .comment-markdown hr {
                            border: 0;
                            border-top: 1px solid rgba(71, 85, 105, .6);
                            margin: 1rem 0;
                        }

                        /* Scoped SimpleMDE dark styling to match the design system */
                        #comment-form .editor-toolbar {
                            background: rgba(15, 23, 42, 0.6);
                            border: 1px solid rgba(51, 65, 85, 0.7);
                            border-bottom: 0;
                            border-top-left-radius: 0.5rem;
                            border-top-right-radius: 0.5rem;
                            color: #cbd5e1;
                            margin: 0 !important;
                            padding: 6px 8px;
                            min-height: 40px;
                            /* keep height stable across modes */
                        }

                        /* Inline edit editor styles */
                        .comment-edit-form .editor-toolbar {
                            background: rgba(15, 23, 42, 0.6);
                            border: 1px solid rgba(51, 65, 85, 0.7);
                            border-bottom: 0;
                            border-top-left-radius: 0.5rem;
                            border-top-right-radius: 0.5rem;
                            color: #cbd5e1;
                            margin: 0 !important;
                            padding: 6px 8px;
                            min-height: 40px;
                        }

                        .comment-edit-form .CodeMirror {
                            background: rgba(15, 23, 42, 0.85);
                            color: #e2e8f0;
                            border: 1px solid rgba(51, 65, 85, 0.7);
                            border-top: 0;
                            border-bottom-left-radius: 0.5rem;
                            border-bottom-right-radius: 0.5rem;
                            min-height: 260px;
                            margin: 0 !important;
                        }

                        .comment-edit-form .editor-toolbar a {
                            color: #cbd5e1 !important;
                        }

                        .comment-edit-form .editor-toolbar a:hover,
                        .comment-edit-form .editor-toolbar a.active {
                            background: rgba(99, 102, 241, 0.15) !important;
                            color: #93c5fd !important;
                        }

                        #comment-form .editor-toolbar a {
                            color: #cbd5e1 !important;
                        }

                        #comment-form .editor-toolbar a:hover,
                        #comment-form .editor-toolbar a.active {
                            background: rgba(99, 102, 241, 0.15) !important;
                            /* primary-500/15 */
                            color: #93c5fd !important;
                            /* primary-300 */
                        }

                        /* Prevent toolbar restyling in preview mode */
                        #comment-form .editor-toolbar.disabled-for-preview a:not(.no-disable),
                        #comment-form .editor-toolbar a.disabled {
                            opacity: 1 !important;
                            filter: none !important;
                            background: transparent !important;
                            color: #cbd5e1 !important;
                            pointer-events: none;
                            /* keep disabled behavior without visual shift */
                        }

                        #comment-form .editor-toolbar i.separator {
                            border-color: rgba(71, 85, 105, 0.7) !important;
                        }

                        #comment-form .CodeMirror {
                            background: rgba(15, 23, 42, 0.85);
                            color: #e2e8f0;
                            border: 1px solid rgba(51, 65, 85, 0.7);
                            border-top: 0;
                            border-bottom-left-radius: 0.5rem;
                            border-bottom-right-radius: 0.5rem;
                            min-height: 200px;
                            margin: 0 !important;
                        }

                        #comment-form .CodeMirror-gutters {
                            background: transparent;
                            border-right: 0;
                        }

                        #comment-form .CodeMirror-focused {
                            outline: none;
                            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
                            border-color: rgba(99, 102, 241, 0.6);
                        }

                        #comment-form .cm-s-paper .CodeMirror-cursor,
                        #comment-form .CodeMirror-cursor {
                            border-left: 1px solid #93c5fd;
                        }

                        #comment-form .CodeMirror-placeholder {
                            color: #64748b;
                        }

                        /* Improve selection contrast in dark mode */
                        #comment-form .CodeMirror .CodeMirror-selected {
                            background: rgba(99, 102, 241, 0.35) !important;
                            /* primary-500/35 */
                        }

                        #comment-form .CodeMirror-focused .CodeMirror-selected {
                            background: rgba(99, 102, 241, 0.45) !important;
                            /* primary-500/45 */
                        }

                        #comment-form .CodeMirror ::selection {
                            background: rgba(99, 102, 241, 0.35) !important;
                        }

                        #comment-form .CodeMirror ::-moz-selection {
                            background: rgba(99, 102, 241, 0.35) !important;
                        }

                        /* Preview pane (single + side-by-side) */
                        #comment-form .editor-preview,
                        #comment-form .editor-preview-side {
                            background: rgba(15, 23, 42, 0.92) !important;
                            color: #e2e8f0 !important;
                            border: 1px solid rgba(51, 65, 85, 0.7) !important;
                            border-radius: 0.5rem !important;
                            padding: 1rem 1.25rem !important;
                        }

                        /* When not side-by-side, preview overlays editor; blend with toolbar */
                        #comment-form .editor-preview {
                            border-top-left-radius: 0 !important;
                            border-top-right-radius: 0 !important;
                            border-top: 0 !important;
                        }

                        /* Proper side-by-side layout */
                        #comment-form .editor-preview-side {
                            right: 0 !important;
                            left: auto !important;
                        }

                        #comment-form.sbs-active .CodeMirror {
                            width: 50% !important;
                        }

                        #comment-form .editor-preview h1,
                        #comment-form .editor-preview h2,
                        #comment-form .editor-preview h3,
                        #comment-form .editor-preview h4,
                        #comment-form .editor-preview h5,
                        #comment-form .editor-preview h6,
                        #comment-form .editor-preview-side h1,
                        #comment-form .editor-preview-side h2,
                        #comment-form .editor-preview-side h3,
                        #comment-form .editor-preview-side h4,
                        #comment-form .editor-preview-side h5,
                        #comment-form .editor-preview-side h6 {
                            color: #ffffff !important;
                        }

                        #comment-form .editor-preview a,
                        #comment-form .editor-preview-side a {
                            color: #93c5fd !important;
                            text-decoration: underline;
                        }

                        #comment-form .editor-preview code,
                        #comment-form .editor-preview-side code {
                            background: rgba(2, 6, 23, 0.6);
                            color: #eab308;
                            padding: 0.1rem 0.35rem;
                            border-radius: 0.25rem;
                        }

                        #comment-form .editor-preview pre,
                        #comment-form .editor-preview-side pre {
                            background: rgba(2, 6, 23, 0.7);
                            color: #e2e8f0;
                            border: 1px solid rgba(51, 65, 85, 0.6);
                            border-radius: 0.5rem;
                            padding: 0.75rem 1rem;
                            overflow: auto;
                        }

                        #comment-form .editor-preview table,
                        #comment-form .editor-preview-side table {
                            width: 100%;
                            border-collapse: collapse;
                        }

                        #comment-form .editor-preview th,
                        #comment-form .editor-preview td,
                        #comment-form .editor-preview-side th,
                        #comment-form .editor-preview-side td {
                            border: 1px solid rgba(71, 85, 105, 0.7);
                            padding: 0.4rem 0.6rem;
                        }

                        #comment-form .editor-preview th,
                        #comment-form .editor-preview-side th {
                            background: rgba(30, 41, 59, 0.6);
                        }

                        #comment-form .editor-preview blockquote,
                        #comment-form .editor-preview-side blockquote {
                            border-left: 3px solid rgba(99, 102, 241, 0.6);
                            padding-left: 0.75rem;
                            color: #cbd5e1;
                        }

                        /* --- Editor token styling to match preview (headings, quotes, lists) --- */
                        #comment-form .CodeMirror-lines {
                            line-height: 1.6;
                        }

                        #comment-form .cm-header {
                            color: #ffffff !important;
                            font-weight: 700 !important;
                        }

                        #comment-form .cm-header-1 {
                            font-size: 1.50rem !important;
                        }

                        #comment-form .cm-header-2 {
                            font-size: 1.35rem !important;
                        }

                        #comment-form .cm-header-3 {
                            font-size: 1.20rem !important;
                        }

                        #comment-form .cm-header-4 {
                            font-size: 1.10rem !important;
                        }

                        #comment-form .cm-header-5 {
                            font-size: 1.05rem !important;
                        }

                        #comment-form .cm-header-6 {
                            font-size: 1.00rem !important;
                        }

                        /* Blockquotes: color quote marker and text */
                        #comment-form .cm-quote {
                            color: #cbd5e1 !important;
                            font-style: italic !important;
                        }

                        #comment-form .cm-formatting-quote {
                            color: #93c5fd !important;
                        }

                        /* Lists: color markers and improve readability */
                        #comment-form .cm-formatting-list,
                        #comment-form .cm-variable-2 {
                            color: #93c5fd !important;
                        }

                        #comment-form .cm-list-1 {}

                        #comment-form .cm-list-2 {}

                        #comment-form .cm-list-3 {}

                        /* Links inside editor */
                        #comment-form .cm-link {
                            color: #93c5fd !important;
                            text-decoration: underline;
                        }

                        #comment-form .cm-url {
                            color: #60a5fa !important;
                        }

                        /* Theme-specific strengthen (paper) */
                        #comment-form .cm-s-paper .cm-header {
                            color: #ffffff !important;
                            font-weight: 700 !important;
                        }

                        #comment-form .cm-s-paper .cm-formatting-header {
                            color: #93c5fd !important;
                        }

                        #comment-form .cm-s-paper .cm-formatting-list {
                            color: #93c5fd !important;
                        }

                        #comment-form .cm-s-paper .cm-quote {
                            color: #cbd5e1 !important;
                        }

                        #comment-form .CodeMirror-cursor {
                            background: #fff !important;
                            border: none !important;
                            width: 2px;
                            opacity: 1;
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
