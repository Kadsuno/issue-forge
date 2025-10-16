<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Create Custom Workflow State') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-6">
                <form method="POST" action="{{ route('admin.workflows.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            {{ __('Name') }} *
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="input @error('name') input-error @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="label" class="block text-sm font-medium text-gray-300 mb-2">
                            {{ __('Label') }} *
                        </label>
                        <input type="text" name="label" id="label" value="{{ old('label') }}" required class="input @error('label') input-error @enderror">
                        @error('label')
                            <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                            {{ __('Description') }}
                        </label>
                        <textarea name="description" id="description" rows="3" class="input @error('description') input-error @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="color" class="block text-sm font-medium text-gray-300 mb-2">
                            {{ __('Color') }} *
                        </label>
                        <select name="color" id="color" required class="input">
                            <option value="gray">Gray</option>
                            <option value="blue">Blue</option>
                            <option value="green">Green</option>
                            <option value="yellow">Yellow</option>
                            <option value="orange">Orange</option>
                            <option value="red">Red</option>
                            <option value="purple">Purple</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="icon" class="block text-sm font-medium text-gray-300 mb-2">
                            {{ __('Icon') }}
                        </label>
                        <input type="text" name="icon" id="icon" value="{{ old('icon') }}" placeholder="circle" class="input">
                    </div>

                    <div class="mb-4">
                        <label for="order" class="block text-sm font-medium text-gray-300 mb-2">
                            {{ __('Order') }}
                        </label>
                        <input type="number" name="order" id="order" value="{{ old('order', 10) }}" min="0" class="input">
                    </div>

                    <div class="mb-4">
                        <label for="project_id" class="block text-sm font-medium text-gray-300 mb-2">
                            {{ __('Project') }}
                        </label>
                        <select name="project_id" id="project_id" class="input">
                            <option value="">{{ __('Global (all projects)') }}</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}" {{ old('project_id', $projectId) == $project->id ? 'selected' : '' }}>
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_closed" value="1" {{ old('is_closed') ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-300">{{ __('This state marks tickets as closed/completed') }}</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.workflows.index') }}" class="btn-ghost">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn-primary">
                            {{ __('Create State') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

