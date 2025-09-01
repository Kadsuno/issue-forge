<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div
                    class="w-8 h-8 bg-gradient-to-br from-primary-500 to-accent-500 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 20h9"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Edit Comment</h2>
                    <p class="text-sm text-slate-400">Ticket {{ $ticket->number }} — {{ $ticket->title }}</p>
                </div>
            </div>
            <a href="{{ route('tickets.show', $ticket) }}" class="btn-ghost">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Ticket
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div id="comment-edit-form" class="card p-8 animate-fade-in-up">
                <form method="POST" action="{{ route('tickets.comments.update', $comment) }}" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <label class="block text-xs font-medium text-slate-500 uppercase tracking-wide">Comment</label>
                    <textarea id="comment-edit-body" name="body" rows="14" class="input w-full"
                        placeholder="Write a comment using Markdown..." required>{{ old('body', $comment->body) }}</textarea>
                    @error('body')
                        <p class="text-danger-400 text-sm">{{ $message }}</p>
                    @enderror

                    <div class="flex justify-end gap-2 pt-2">
                        <a href="{{ route('tickets.show', $ticket) }}" class="btn-ghost">Cancel</a>
                        <button type="submit" class="btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.SimpleMDE) {
                new SimpleMDE({
                    element: document.getElementById('comment-edit-body'),
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
        });
    </script>

    <style>
        /* Dark theme for the dedicated edit page editor */
        #comment-edit-form .editor-toolbar {
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

        #comment-edit-form .editor-toolbar a {
            color: #cbd5e1 !important;
        }

        #comment-edit-form .editor-toolbar a:hover,
        #comment-edit-form .editor-toolbar a.active {
            background: rgba(99, 102, 241, 0.15) !important;
            color: #93c5fd !important;
        }

        /* Prevent toolbar restyling in preview mode */
        #comment-edit-form .editor-toolbar.disabled-for-preview a:not(.no-disable),
        #comment-edit-form .editor-toolbar a.disabled {
            opacity: 1 !important;
            filter: none !important;
            background: transparent !important;
            color: #cbd5e1 !important;
            pointer-events: none;
        }

        #comment-edit-form .editor-toolbar i.separator {
            border-color: rgba(71, 85, 105, 0.7) !important;
        }

        #comment-edit-form .CodeMirror {
            background: rgba(15, 23, 42, 0.85);
            color: #e2e8f0;
            border: 1px solid rgba(51, 65, 85, 0.7);
            border-top: 0;
            border-bottom-left-radius: 0.5rem;
            border-bottom-right-radius: 0.5rem;
            min-height: 360px;
            margin: 0 !important;
        }

        #comment-edit-form .CodeMirror-gutters {
            background: transparent;
            border-right: 0;
        }

        #comment-edit-form .CodeMirror-focused {
            outline: none;
            box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2);
            border-color: rgba(99, 102, 241, 0.6);
        }

        #comment-edit-form .cm-s-paper .CodeMirror-cursor,
        #comment-edit-form .CodeMirror-cursor {
            border-left: 1px solid #93c5fd;
        }

        #comment-edit-form .CodeMirror-placeholder {
            color: #64748b;
        }

        /* Improve selection contrast in dark mode */
        #comment-edit-form .CodeMirror .CodeMirror-selected {
            background: rgba(99, 102, 241, 0.35) !important;
        }

        #comment-edit-form .CodeMirror-focused .CodeMirror-selected {
            background: rgba(99, 102, 241, 0.45) !important;
        }

        #comment-edit-form .CodeMirror ::selection {
            background: rgba(99, 102, 241, 0.35) !important;
        }

        #comment-edit-form .CodeMirror ::-moz-selection {
            background: rgba(99, 102, 241, 0.35) !important;
        }

        /* Preview pane */
        #comment-edit-form .editor-preview,
        #comment-edit-form .editor-preview-side {
            background: rgba(15, 23, 42, 0.92) !important;
            color: #e2e8f0 !important;
            border: 1px solid rgba(51, 65, 85, 0.7) !important;
            border-radius: 0.5rem !important;
            padding: 1rem 1.25rem !important;
        }

        #comment-edit-form .editor-preview {
            border-top-left-radius: 0 !important;
            border-top-right-radius: 0 !important;
            border-top: 0 !important;
        }

        #comment-edit-form .editor-preview-side {
            right: 0 !important;
            left: auto !important;
        }
    </style>
</x-app-layout>
