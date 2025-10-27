<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">{{ __('Editing Navigation') }}</h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
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

                        <form action="{{ route('admin.navigations.update', $navigation->id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="mb-4">
                                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                                <input type="text" name="title" id="title" value="{{ old('title', $navigation->title) }}" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="mb-4">
                                <label for="navigation_for" class="block text-sm font-medium text-gray-700">Navigation For</label>
                                <select name="navigation_for" id="navigation_for"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="" disabled {{ is_null($navigation->navigation_for) ? 'selected' : '' }}>Select Any One</option>
                                    <option value="" {{ $navigation->navigation_for == null ? 'selected' : '' }}>Main Website</option>
                                    @foreach ($wings as $wing)
                                        <option value="{{ $wing->id }}" {{ $navigation->navigation_for == $wing->id ? 'selected' : '' }}>
                                            {{ $wing->title }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4">
                                <label for="nav_icon" class="block text-sm font-medium text-gray-700">Navigation Icon</label>
                                <input type="text" name="nav_icon" id="nav_icon" value="{{ old('nav_icon', $navigation->nav_icon) }}" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="mb-4">
                                <label for="url" class="block text-sm font-medium text-gray-700">Navigation URL</label>
                                <input type="text" name="url" id="url" value="{{ old('url', $navigation->url) }}" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="mb-4">
                                <label for="route" class="block text-sm font-medium text-gray-700">Navigation Route</label>
                                <input type="text" name="route" id="route" value="{{ old('route', $navigation->route) }}" 
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="mb-4">
                                <label for="parent_id" class="block text-sm font-medium text-gray-700">Parent Navigation</label>
                                <select name="parent_id" id="parent_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="" {{ is_null($navigation->parent_id) ? 'selected' : '' }}>Select (If Any)</option>
                                    @foreach ($navigations as $parent)
                                        @if ($parent->id !== $navigation->id)
                                            <option value="{{ $parent->id }}" {{ $navigation->parent_id == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->title }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-4 flex items-center">
                                <input type="checkbox" name="is_active" id="is_active" value="1"
                                    {{ $navigation->is_active ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">Active</label>
                            </div>

                            <div class="mt-6">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border rounded-md font-semibold text-sm hover:bg-indigo-700 transition ease-in-out duration-150">
                                    Update Navigation
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script></script>
    @endpush
</x-backend.layouts.master>
