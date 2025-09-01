<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
            <div class="flex items-start md:items-center gap-3 md:gap-4">
                <div class="flex items-center gap-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-warning-500 to-warning-600 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Edit Ticket</h2>
                        <p class="text-sm text-slate-400">Update ticket details</p>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-auto flex items-center justify-between md:justify-end gap-3">
                <a href="{{ route('tickets.show', $ticket) }}" class="btn-ghost w-full md:w-auto text-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Ticket
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-6 sm:p-8 animate-fade-in-up">
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-danger-500/10 border border-danger-500/20 rounded-lg">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-danger-400 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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

                <form action="{{ route('tickets.update', $ticket) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Title -->
                            <div>
                                <label for="title" class="block text-sm font-medium text-slate-300 mb-2">Title <span
                                        class="text-danger-400">*</span></label>
                                <input type="text" id="title" name="title"
                                    value="{{ old('title', $ticket->title) }}" required class="input w-full"
                                    placeholder="Enter a clear, descriptive title...">
                                @error('title')
                                    <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description"
                                    class="block text-sm font-medium text-slate-300 mb-2">Description</label>
                                <div id="ticket-editor">
                                    <textarea id="description" name="description" rows="6" class="input w-full resize-none"
                                        placeholder="Provide detailed information...">{{ old('description', $ticket->description) }}</textarea>
                                </div>
                                @error('description')
                                    <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Due Date -->
                            <div>
                                <label for="due_date" class="block text-sm font-medium text-slate-300 mb-2">Due
                                    Date</label>
                                <input type="date" id="due_date" name="due_date"
                                    value="{{ old('due_date', optional($ticket->due_date)->format('Y-m-d')) }}"
                                    min="{{ date('Y-m-d', strtotime('+1 day')) }}" class="input w-full">
                                @error('due_date')
                                    <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Status -->
                            <div>
                                <label for="status" class="block text-sm font-medium text-slate-300 mb-2">Status <span
                                        class="text-danger-400">*</span></label>
                                <select id="status" name="status" required class="input w-full">
                                    @foreach ($statuses as $key => $label)
                                        <option value="{{ $key }}" @selected(old('status', $ticket->status) == $key)>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-slate-300 mb-2">Type</label>
                                <select id="type" name="type" class="input w-full">
                                    @foreach (\App\Models\Ticket::getTypes() as $key => $label)
                                        <option value="{{ $key }}" @selected(old('type', $ticket->type ?? 'task') == $key)>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Priority -->
                            <div>
                                <label for="priority" class="block text-sm font-medium text-slate-300 mb-2">Priority
                                    <span class="text-danger-400">*</span></label>
                                <select id="priority" name="priority" required class="input w-full">
                                    @foreach ($priorities as $key => $label)
                                        <option value="{{ $key }}" @selected(old('priority', $ticket->priority) == $key)>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('priority')
                                    <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Severity -->
                            <div>
                                <label for="severity"
                                    class="block text-sm font-medium text-slate-300 mb-2">Severity</label>
                                <select id="severity" name="severity" class="input w-full">
                                    <option value="">None</option>
                                    @foreach (\App\Models\Ticket::getSeverities() as $key => $label)
                                        <option value="{{ $key }}" @selected(old('severity', $ticket->severity) == $key)>
                                            {{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('severity')
                                    <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Assigned To -->
                            <div>
                                <label for="assigned_to" class="block text-sm font-medium text-slate-300 mb-2">Assign
                                    To</label>
                                <select id="assigned_to" name="assigned_to" class="input w-full">
                                    <option value="">Unassigned</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}" @selected(old('assigned_to', $ticket->assigned_to) == $user->id)>
                                            {{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('assigned_to')
                                    <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Parent Ticket (Searchable) -->
                            <div>
                                <label class="block text-sm font-medium text-slate-300 mb-2">Parent Ticket</label>
                                @php
                                    $selectedParentId = old('parent_ticket_id', $ticket->parent_ticket_id);
                                    $selectedParent = $parentOptions->firstWhere('id', $selectedParentId);
                                    $parentInitialLabel = $selectedParent
                                        ? $selectedParent->id . ' — ' . Str::limit($selectedParent->title, 60)
                                        : '';
                                    $parentItems = $parentOptions
                                        ->map(function ($p) {
                                            return [
                                                'id' => $p->id,
                                                'label' => $p->id . ' — ' . Str::limit($p->title, 60),
                                            ];
                                        })
                                        ->values();
                                @endphp
                                <div x-data='{
                                        query: @json($parentInitialLabel ?? ''),
                                        open: false,
                                        selectedId: @json($selectedParentId ?? null),
                                        items: @json($parentItems ?? []),
                                        select(item){ this.selectedId=item.id; this.query=item.label; this.open=false; },
                                        clear(){ this.selectedId=null; this.query=""; },
                                        filtered(){ if(!this.query) return this.items; const q=this.query.toLowerCase(); return this.items.filter(i=>i.label.toLowerCase().includes(q)); }
                                    }'
                                    class="relative" @click.outside="open=false">
                                    <input type="hidden" name="parent_ticket_id" :value="selectedId ?? ''">
                                    <div class="flex items-center gap-2">
                                        <input type="text" x-model="query" @focus="open=true" @input="open=true"
                                            placeholder="Search by number or title..." class="input w-full">
                                        <button type="button" class="btn-ghost" x-show="selectedId"
                                            @click="clear()">Clear</button>
                                    </div>
                                    <div class="absolute z-10 mt-2 w-full card p-0" x-show="open" x-transition>
                                        <ul class="max-h-56 overflow-auto divide-y divide-slate-700">
                                            <template x-for="item in filtered()" :key="item.id">
                                                <li>
                                                    <button type="button"
                                                        class="w-full text-left px-3 py-2 hover:bg-dark-700/40"
                                                        @click="select(item)" x-text="item.label"></button>
                                                </li>
                                            </template>
                                            <li class="px-3 py-2 text-xs text-slate-500"
                                                x-show="filtered().length===0">No matches</li>
                                        </ul>
                                    </div>
                                </div>
                                @error('parent_ticket_id')
                                    <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Estimate (H:MM) -->
                            <div>
                                <label for="estimate_hm"
                                    class="block text-sm font-medium text-slate-300 mb-2">Estimate (H:MM)</label>
                                @php($estMinutes = old('estimate_minutes', $ticket->estimate_minutes))
                                @php($estValue = $estMinutes !== null ? sprintf('%d:%02d', intdiv((int) $estMinutes, 60), ((int) $estMinutes) % 60) : '')
                                <input type="text" id="estimate_hm" name="estimate_hm" pattern="\d{1,3}:\d{2}"
                                    value="{{ $estValue }}" class="input w-full" placeholder="e.g. 2:00">
                                <input type="hidden" id="estimate_minutes" name="estimate_minutes"
                                    value="{{ $estMinutes }}">
                                <script>
                                    (function() {
                                        const hm = document.getElementById('estimate_hm');
                                        const min = document.getElementById('estimate_minutes');
                                        if (!hm || !min) return;
                                        const toMinutes = (v) => {
                                            const m = String(v || '').match(/^(\d{1,3}):(\d{2})$/);
                                            if (!m) return '';
                                            return (parseInt(m[1], 10) * 60 + parseInt(m[2], 10)) || 0;
                                        };
                                        hm.addEventListener('input', () => {
                                            min.value = toMinutes(hm.value);
                                        });
                                    })();
                                </script>
                                @error('estimate_minutes')
                                    <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Labels -->
                            <div>
                                <label for="labels" class="block text-sm font-medium text-slate-300 mb-2">Labels
                                    (comma separated)</label>
                                <input type="text" id="labels" name="labels"
                                    value="{{ old('labels', $ticket->labels) }}" class="input w-full"
                                    placeholder="bug, backend, regression">
                                @error('labels')
                                    <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-6 border-t border-slate-700">
                        <a href="{{ route('tickets.show', $ticket) }}"
                            class="btn-ghost w-full sm:w-auto text-center">Cancel</a>
                        <button type="submit" class="btn-primary w-full sm:w-auto">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Update Ticket
                        </button>
                    </div>
                </form>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        if (window.SimpleMDE) {
                            new SimpleMDE({
                                element: document.getElementById('description'),
                                spellChecker: false,
                                status: false,
                                autoDownloadFontAwesome: true,
                                placeholder: 'Provide detailed information...',
                                toolbar: [
                                    'bold', 'italic', 'heading', '|',
                                    'quote', 'code', 'table', '|',
                                    'unordered-list', 'ordered-list', '|',
                                    'link', 'image', 'horizontal-rule', '|',
                                    'preview'
                                ],
                            });
                        }
                    });
                </script>
                <style>
                    #ticket-editor .editor-toolbar {
                        background: rgba(15, 23, 42, .6);
                        border: 1px solid rgba(51, 65, 85, .7);
                        border-bottom: 0;
                        border-top-left-radius: .5rem;
                        border-top-right-radius: .5rem;
                        color: #cbd5e1;
                        margin: 0 !important;
                        padding: 6px 8px;
                        min-height: 40px
                    }

                    #ticket-editor .editor-toolbar a {
                        color: #cbd5e1 !important
                    }

                    #ticket-editor .editor-toolbar a:hover,
                    #ticket-editor .editor-toolbar a.active {
                        background: rgba(99, 102, 241, .15) !important;
                        color: #93c5fd !important
                    }

                    #ticket-editor .CodeMirror {
                        background: rgba(15, 23, 42, .85);
                        color: #e2e8f0;
                        border: 1px solid rgba(51, 65, 85, .7);
                        border-top: 0;
                        border-bottom-left-radius: .5rem;
                        border-bottom-right-radius: .5rem;
                        min-height: 220px;
                        margin: 0 !important
                    }

                    #ticket-editor .CodeMirror .CodeMirror-selected {
                        background: rgba(99, 102, 241, .35) !important
                    }

                    #ticket-editor .CodeMirror-focused .CodeMirror-selected {
                        background: rgba(99, 102, 241, .45) !important
                    }

                    #ticket-editor .CodeMirror ::selection {
                        background: rgba(99, 102, 241, .35) !important
                    }

                    #ticket-editor .CodeMirror ::-moz-selection {
                        background: rgba(99, 102, 241, .35) !important
                    }

                    #ticket-editor .editor-preview,
                    #ticket-editor .editor-preview-side {
                        background: rgba(15, 23, 42, 1) !important;
                        color: #e2e8f0 !important;
                        border: 1px solid rgba(51, 65, 85, .7) !important;
                        border-radius: .5rem !important;
                        padding: 1rem 1.25rem !important
                    }

                    #ticket-editor .editor-preview {
                        border-top-left-radius: 0 !important;
                        border-top-right-radius: 0 !important;
                        border-top: 0 !important
                    }

                    #ticket-editor .editor-preview h1,
                    #ticket-editor .editor-preview h2,
                    #ticket-editor .editor-preview h3,
                    #ticket-editor .editor-preview h4,
                    #ticket-editor .editor-preview h5,
                    #ticket-editor .editor-preview h6 {
                        color: #ffffff !important;
                        font-weight: 700 !important
                    }

                    #ticket-editor .editor-preview a {
                        color: #93c5fd !important;
                        text-decoration: underline
                    }

                    /* Keep toolbar icons readable in preview mode */
                    #ticket-editor .editor-toolbar.disabled-for-preview a:not(.no-disable),
                    #ticket-editor .editor-toolbar a.disabled {
                        opacity: 1 !important;
                        filter: none !important;
                        background: transparent !important;
                        color: #cbd5e1 !important;
                        pointer-events: none;
                    }
                </style>
            </div>
        </div>
    </div>
</x-app-layout>
