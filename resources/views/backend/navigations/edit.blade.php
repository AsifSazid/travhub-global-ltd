<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">{{ __('Editing Navigation') }}</h2>
        </div>
    </x-slot>

    <div class="overflow-x-auto">
        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('navigations.update', $navigation->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $navigation->title) }}"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="nav_icon" class="block text-sm font-medium text-gray-700">Navigation
                    Icon</label>
                <input type="text" name="nav_icon" id="nav_icon"
                    value="{{ old('nav_icon', $navigation->nav_icon) }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="url" class="block text-sm font-medium text-gray-700">Navigation
                    URL</label>
                <input type="text" name="url" id="url" value="{{ old('url', $navigation->url) }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="route" class="block text-sm font-medium text-gray-700">Navigation
                    Route</label>
                <input type="text" name="route" id="route" value="{{ old('route', $navigation->route) }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent
                    Navigation</label>
                <select name="parent_id" id="parent_id"
                    class="mt-1 px-3 py-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="" {{ is_null($navigation->parent_id) ? 'selected' : '' }}>
                        Select (If Any)</option>
                    @foreach ($navigations as $parent)
                        @if ($parent->id !== $navigation->id)
                            <option value="{{ $parent->id }}"
                                {{ $navigation->parent_id == $parent->id ? 'selected' : '' }}>
                                {{ $parent->title }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>

            <div class="mb-4 flex items-center gap-2">
                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                <div id="statusToggle"
                    class="relative w-10 h-5 rounded-full bg-gray-300 cursor-pointer transition-colors">
                    <div id="toggleKnob"
                        class="absolute top-0.5 left-0.5 w-5 h-5 bg-white rounded-full shadow-md transition-all">
                    </div>
                </div>

                <span id="statusText" class="text-xs font-medium text-gray-700">
                    {{ $navigation->status === 'active' ? 'Active' : 'Inactive' }}
                </span>

                <input type="hidden" name="status" id="status"
                    value="{{ $navigation->status === 'active' ? '1' : '0' }}">
            </div>

            <div class="mt-6">
                <button type="submit"
                    class="flex items-center justify-center px-4 py-2 text-sm text-white rounded-md bg-primary border border-gray-300 dark:bg-white dark:border-gray-200 hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary-dark focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                    Update Navigation
                </button>
            </div>
        </form>
    </div>

    @push('js')
        <script>
            const toggle = document.getElementById('statusToggle');
            const knob = document.getElementById('toggleKnob');
            const statusInput = document.getElementById('status');
            const statusText = document.getElementById('statusText');

            // Initialize position
            if (statusInput.value === '1') {
                knob.style.transform = 'translateX(24px)';
                toggle.style.backgroundColor = '#22c55e'; // Green
            } else {
                knob.style.transform = 'translateX(0px)';
                toggle.style.backgroundColor = '#6b7280'; // Gray
            }

            // Click event
            toggle.addEventListener('click', () => {
                if (statusInput.value === '1') {
                    // Switch to inactive
                    statusInput.value = '0';
                    statusText.textContent = 'Inactive';
                    knob.style.transform = 'translateX(0px)';
                    toggle.style.backgroundColor = '#6b7280';
                } else {
                    // Switch to active
                    statusInput.value = '1';
                    statusText.textContent = 'Active';
                    knob.style.transform = 'translateX(24px)';
                    toggle.style.backgroundColor = '#22c55e';
                }
            });
        </script>
    @endpush
</x-backend.layouts.master>
