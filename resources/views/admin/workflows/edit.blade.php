<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-200 leading-tight">
            {{ __('Edit Workflow State') }}: {{ $workflow->label }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="card p-6">
                <form method="POST" action="{{ route('admin.workflows.update', $workflow) }}">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                            {{ __('Name') }} *
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $workflow->name) }}" required class="input @error('name') input-error @enderror">
                        @error('name')
                            <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="label" class="block text-sm font-medium text-gray-300 mb-2">
                            {{ __('Label') }} *
                        </label>
                        <input type="text" name="label" id="label" value="{{ old('label', $workflow->label) }}" required class="input @error('label') input-error @enderror">
                        @error('label')
                            <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-300 mb-2">
                            {{ __('Description') }}
                        </label>
                        <textarea name="description" id="description" rows="3" class="input @error('description') input-error @enderror">{{ old('description', $workflow->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-danger-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="color" class="block text-sm font-medium text-gray-300 mb-2">
                            {{ __('Color') }} *
                        </label>
                        <select name="color" id="color" required class="input">
                            <option value="gray" {{ old('color', $workflow->color) == 'gray' ? 'selected' : '' }}>Gray</option>
                            <option value="blue" {{ old('color', $workflow->color) == 'blue' ? 'selected' : '' }}>Blue</option>
                            <option value="green" {{ old('color', $workflow->color) == 'green' ? 'selected' : '' }}>Green</option>
                            <option value="yellow" {{ old('color', $workflow->color) == 'yellow' ? 'selected' : '' }}>Yellow</option>
                            <option value="orange" {{ old('color', $workflow->color) == 'orange' ? 'selected' : '' }}>Orange</option>
                            <option value="red" {{ old('color', $workflow->color) == 'red' ? 'selected' : '' }}>Red</option>
                            <option value="purple" {{ old('color', $workflow->color) == 'purple' ? 'selected' : '' }}>Purple</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="icon" class="block text-sm font-medium text-gray-300 mb-2">
                            {{ __('Icon') }}
                        </label>
                        <input type="text" name="icon" id="icon" value="{{ old('icon', $workflow->icon) }}" placeholder="circle" class="input">
                    </div>

                    <div class="mb-4">
                        <label for="order" class="block text-sm font-medium text-gray-300 mb-2">
                            {{ __('Order') }}
                        </label>
                        <input type="number" name="order" id="order" value="{{ old('order', $workflow->order) }}" min="0" class="input">
                    </div>

                    <div class="mb-6">
                        <label class="flex items-center">
                            <input type="checkbox" name="is_closed" value="1" {{ old('is_closed', $workflow->is_closed) ? 'checked' : '' }} class="mr-2">
                            <span class="text-sm text-gray-300">{{ __('This state marks tickets as closed/completed') }}</span>
                        </label>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-300 mb-2">
                            {{ __('Role Permissions') }}
                        </label>
                        <p class="text-sm text-gray-400 mb-3">{{ __('Select which roles can set tickets to this state. Leave empty to allow all users.') }}</p>
                        @foreach($roles as $role)
                            <label class="flex items-center mb-2">
                                <input type="checkbox" name="roles[]" value="{{ $role->id }}" {{ in_array($role->id, $stateRoles) ? 'checked' : '' }} class="mr-2">
                                <span class="text-sm text-gray-300">{{ ucfirst($role->name) }}</span>
                            </label>
                        @endforeach
                    </div>

                    <div class="flex justify-end gap-2">
                        <a href="{{ route('admin.workflows.index', ['project_id' => $workflow->project_id]) }}" class="btn-ghost">
                            {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn-primary">
                            {{ __('Update State') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

