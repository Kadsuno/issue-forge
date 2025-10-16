@if($ticket->statusHistory->isNotEmpty())
    <div class="card p-6 mt-6">
        <h3 class="text-lg font-semibold text-gray-200 mb-4">{{ __('Status History') }}</h3>
        <div class="space-y-3">
            @foreach($ticket->statusHistory as $history)
                <div class="flex items-start gap-4 py-3 border-b border-dark-700 last:border-0">
                    <div class="flex-shrink-0 w-24 text-sm text-gray-400">
                        {{ $history->created_at->format('M d, H:i') }}
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            @if($history->from_status)
                                <span class="badge badge-secondary text-xs">{{ str_replace('_', ' ', $history->from_status) }}</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            @endif
                            <span class="badge badge-primary text-xs">{{ str_replace('_', ' ', $history->to_status) }}</span>
                        </div>
                        <p class="text-sm text-gray-300">
                            {{ __('Changed by') }} <span class="font-medium">{{ $history->user->name }}</span>
                        </p>
                        @if($history->comment)
                            <p class="text-sm text-gray-400 mt-1">{{ $history->comment }}</p>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

