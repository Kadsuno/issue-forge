@props(['attachments', 'canDelete' => false])

@if($attachments->count() > 0)
    <div class="space-y-3">
        <h3 class="text-sm font-semibold text-dark-100 mb-3">Attachments ({{ $attachments->count() }})</h3>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
            @foreach($attachments as $attachment)
                <div class="card p-4 group hover-lift">
                    <div class="flex items-start gap-3">
                        <!-- File Icon/Preview -->
                        <div class="flex-shrink-0">
                            @if($attachment->isImage())
                                <img
                                    src="{{ $attachment->url }}"
                                    alt="{{ $attachment->file_name }}"
                                    class="w-16 h-16 object-cover rounded"
                                >
                            @else
                                <div class="w-16 h-16 bg-dark-700 rounded flex items-center justify-center">
                                    @php
                                        $iconClass = match($attachment->file_icon) {
                                            'file-pdf' => 'text-danger-400',
                                            'file-word' => 'text-primary-400',
                                            'file-excel' => 'text-success-400',
                                            'file-archive' => 'text-warning-400',
                                            default => 'text-dark-300',
                                        };
                                    @endphp
                                    <svg class="w-8 h-8 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- File Info -->
                        <div class="flex-1 min-w-0">
                            <a
                                href="{{ route('attachments.download', $attachment) }}"
                                class="text-sm font-medium text-primary-400 hover:text-primary-300 truncate block"
                                title="{{ $attachment->file_name }}"
                            >
                                {{ $attachment->file_name }}
                            </a>
                            <p class="text-xs text-dark-400 mt-1">
                                {{ $attachment->human_file_size }}
                            </p>
                            @if($attachment->uploadedBy)
                                <p class="text-xs text-dark-400 mt-1">
                                    By {{ $attachment->uploadedBy->name }}
                                </p>
                            @endif
                            <p class="text-xs text-dark-500 mt-1">
                                {{ $attachment->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <!-- Download Button -->
                            <a
                                href="{{ route('attachments.download', $attachment) }}"
                                class="p-1.5 bg-primary-500/10 hover:bg-primary-500/20 text-primary-400 rounded transition-colors"
                                title="Download"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>

                            <!-- Delete Button -->
                            @can('delete', $attachment)
                                <form
                                    action="{{ route('attachments.destroy', $attachment) }}"
                                    method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this attachment?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        type="submit"
                                        class="p-1.5 bg-danger-500/10 hover:bg-danger-500/20 text-danger-400 rounded transition-colors"
                                        title="Delete"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            @endcan
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@else
    <p class="text-sm text-dark-400 italic">No attachments yet.</p>
@endif

