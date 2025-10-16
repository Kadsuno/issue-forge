<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-200 leading-tight">
                {{ __('Workflow States') }}
            </h2>
            <a href="{{ route('admin.workflows.create', ['project_id' => $projectId]) }}" class="btn-primary">
                {{ __('Create Custom State') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 p-4 bg-success-500 text-white rounded-lg">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 p-4 bg-danger-500 text-white rounded-lg">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Project Filter -->
            <div class="card mb-6 p-4">
                <form method="GET" action="{{ route('admin.workflows.index') }}">
                    <label for="project_id" class="block text-sm font-medium text-gray-300 mb-2">
                        {{ __('Filter by Project') }}
                    </label>
                    <div class="flex gap-2">
                        <select name="project_id" id="project_id" class="input flex-1">
                            <option value="">{{ __('Global States') }}</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ $projectId == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                        <button type="submit" class="btn-secondary">
                            {{ __('Filter') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- States List -->
            <div class="card p-6">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-dark-600">
                            <th class="text-left py-3 px-4">{{ __('Label') }}</th>
                            <th class="text-left py-3 px-4">{{ __('Slug') }}</th>
                            <th class="text-left py-3 px-4">{{ __('Color') }}</th>
                            <th class="text-left py-3 px-4">{{ __('Type') }}</th>
                            <th class="text-left py-3 px-4">{{ __('Closed?') }}</th>
                            <th class="text-right py-3 px-4">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($states as $state)
                            <tr class="border-b border-dark-700">
                                <td class="py-3 px-4">{{ $state->label }}</td>
                                <td class="py-3 px-4">
                                    <code class="text-sm bg-dark-700 px-2 py-1 rounded">{{ $state->slug }}</code>
                                </td>
                                <td class="py-3 px-4">
                                    <span class="badge px-3 py-1 rounded-full text-sm" style="background-color: {{ $state->color }}">
                                        {{ $state->color }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">
                                    @if($state->is_predefined)
                                        <span class="badge-secondary">{{ __('Predefined') }}</span>
                                    @else
                                        <span class="badge-primary">{{ __('Custom') }}</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4">
                                    {{ $state->is_closed ? __('Yes') : __('No') }}
                                </td>
                                <td class="py-3 px-4 text-right">
                                    @if(!$state->is_predefined)
                                        <a href="{{ route('admin.workflows.edit', $state) }}" class="text-primary-400 hover:text-primary-300 mr-3">
                                            {{ __('Edit') }}
                                        </a>
                                        <form action="{{ route('admin.workflows.destroy', $state) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-danger-400 hover:text-danger-300" onclick="return confirm('{{ __('Are you sure?') }}')">
                                                {{ __('Delete') }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-500">{{ __('System state') }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-8 text-center text-gray-400">
                                    {{ __('No workflow states found.') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

