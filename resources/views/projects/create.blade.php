<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Create New Project</h2>
                        <p class="text-sm text-slate-400">Set up a new project to organize your tickets</p>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('projects.index') }}" class="btn-ghost">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Projects
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-8 animate-fade-in-up">
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

                <form action="{{ route('projects.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Project Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-slate-300 mb-2">
                            Project Name <span class="text-danger-400">*</span>
                        </label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required
                            class="input w-full" placeholder="Enter project name...">
                        @error('name')
                            <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project Description (Rich Text Editor) -->
                    <div>
                        <label for="project-description" class="block text-sm font-medium text-slate-300 mb-2">
                            Description
                        </label>
                        <div id="project-editor">
                            <textarea id="project-description" name="description" rows="6" class="input w-full"
                                placeholder="Describe your project (optional)...">{{ old('description') }}</textarea>
                        </div>
                        @error('description')
                            <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Project Status -->
                    <div>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="w-4 h-4 bg-dark-700 border-slate-600 rounded text-primary-500 focus:ring-primary-500/20 focus:ring-2">
                            <span class="text-sm font-medium text-slate-300">
                                Project is active
                            </span>
                        </label>
                        <p class="mt-1 text-xs text-slate-500">Active projects are visible to all team members</p>
                    </div>

                    <!-- Advanced Settings -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-slate-300 mb-2">
                                Start Date
                            </label>
                            <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                                class="input w-full">
                        </div>
                        <div>
                            <label for="due_date" class="block text-sm font-medium text-slate-300 mb-2">
                                Due Date
                            </label>
                            <input type="date" id="due_date" name="due_date" value="{{ old('due_date') }}"
                                class="input w-full">
                        </div>

                        <div>
                            <label for="default_assignee_id" class="block text-sm font-medium text-slate-300 mb-2">
                                Default Assignee
                            </label>
                            <select id="default_assignee_id" name="default_assignee_id" class="input w-full">
                                <option value="">— None —</option>
                                @foreach (\App\Models\User::orderBy('name')->get() as $user)
                                    <option value="{{ $user->id }}" @selected(old('default_assignee_id') == $user->id)>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="visibility" class="block text-sm font-medium text-slate-300 mb-2">
                                Visibility
                            </label>
                            <select id="visibility" name="visibility" class="input w-full">
                                @php($vis = old('visibility', 'team'))
                                <option value="private" @selected($vis === 'private')>Private</option>
                                <option value="team" @selected($vis === 'team')>Team</option>
                                <option value="public" @selected($vis === 'public')>Public</option>
                            </select>
                        </div>

                        <div>
                            <label for="ticket_prefix" class="block text-sm font-medium text-slate-300 mb-2">
                                Ticket Prefix
                            </label>
                            <input type="text" id="ticket_prefix" name="ticket_prefix" maxlength="10"
                                value="{{ old('ticket_prefix') }}" class="input w-full" placeholder="e.g. WEB">
                        </div>

                        <div>
                            <label for="color" class="block text-sm font-medium text-slate-300 mb-2">
                                Project Color (HEX)
                            </label>
                            <input type="text" id="color" name="color"
                                value="{{ old('color', '#1e40af') }}" class="input w-full" placeholder="#1e40af">
                        </div>

                        <div>
                            <label for="priority" class="block text-sm font-medium text-slate-300 mb-2">
                                Default Priority
                            </label>
                            <select id="priority" name="priority" class="input w-full">
                                @php($p = old('priority', 'medium'))
                                <option value="low" @selected($p === 'low')>Low</option>
                                <option value="medium" @selected($p === 'medium')>Medium</option>
                                <option value="high" @selected($p === 'high')>High</option>
                            </select>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-700">
                        <a href="{{ route('projects.index') }}" class="btn-ghost">
                            Cancel
                        </a>
                        <button type="submit" class="btn-primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7"></path>
                            </svg>
                            Create Project
                        </button>
                    </div>
                </form>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        if (window.SimpleMDE) {
                            new SimpleMDE({
                                element: document.getElementById('project-description'),
                                spellChecker: false,
                                status: false,
                                autoDownloadFontAwesome: true,
                                placeholder: 'Describe your project (optional)...',
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
                    #project-editor .editor-toolbar {
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

                    #project-editor .editor-toolbar a {
                        color: #cbd5e1 !important
                    }

                    #project-editor .editor-toolbar a:hover,
                    #project-editor .editor-toolbar a.active {
                        background: rgba(99, 102, 241, .15) !important;
                        color: #93c5fd !important
                    }

                    #project-editor .CodeMirror {
                        background: rgba(15, 23, 42, .85);
                        color: #e2e8f0;
                        border: 1px solid rgba(51, 65, 85, .7);
                        border-top: 0;
                        border-bottom-left-radius: .5rem;
                        border-bottom-right-radius: .5rem;
                        min-height: 220px;
                        margin: 0 !important
                    }

                    /* Match ticket editor selection colors */
                    #project-editor .CodeMirror .CodeMirror-selected {
                        background: rgba(99, 102, 241, .35) !important
                    }

                    #project-editor .CodeMirror-focused .CodeMirror-selected {
                        background: rgba(99, 102, 241, .45) !important
                    }

                    #project-editor .CodeMirror ::selection {
                        background: rgba(99, 102, 241, .35) !important
                    }

                    #project-editor .CodeMirror ::-moz-selection {
                        background: rgba(99, 102, 241, .35) !important
                    }

                    #project-editor .editor-preview,
                    #project-editor .editor-preview-side {
                        background: rgba(15, 23, 42, 1) !important;
                        /* fully opaque to avoid ghosting */
                        color: #e2e8f0 !important;
                        border: 1px solid rgba(51, 65, 85, .7) !important;
                        border-radius: .5rem !important;
                        padding: 1rem 1.25rem !important
                    }

                    #project-editor .editor-preview {
                        border-top-left-radius: 0 !important;
                        border-top-right-radius: 0 !important;
                        border-top: 0 !important
                    }

                    #project-editor .editor-preview h1,
                    #project-editor .editor-preview h2,
                    #project-editor .editor-preview h3,
                    #project-editor .editor-preview h4,
                    #project-editor .editor-preview h5,
                    #project-editor .editor-preview h6 {
                        color: #ffffff !important;
                        font-weight: 700 !important
                    }

                    #project-editor .editor-preview a {
                        color: #93c5fd !important;
                        text-decoration: underline
                    }

                    /* Keep toolbar icons readable in preview mode */
                    #project-editor .editor-toolbar.disabled-for-preview a:not(.no-disable),
                    #project-editor .editor-toolbar a.disabled {
                        opacity: 1 !important;
                        filter: none !important;
                        background: transparent !important;
                        color: #cbd5e1 !important;
                        pointer-events: none;
                    }

                    #project-editor .CodeMirror-cursor {
                        background: #fff !important;
                        border: none !important;
                        width: 2px;
                        opacity: 1;
                    }
                </style>
            </div>

            <!-- Project Creation Tips -->
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
                        <h3 class="text-sm font-semibold text-white mb-2">Tips for creating projects</h3>
                        <ul class="text-xs text-slate-400 space-y-1">
                            <li>• Choose a descriptive name that clearly identifies the project</li>
                            <li>• Add a detailed description to help team members understand the project goals</li>
                            <li>• You can always edit project details later</li>
                            <li>• Active projects appear in dashboards and are available for ticket creation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
