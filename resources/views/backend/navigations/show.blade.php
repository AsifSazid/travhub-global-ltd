<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ __('Navigation of') }} {{ $navigation->title }}
            </h2>
        </div>
    </x-slot>

    <div class="overflow-x-auto">
        <div class="min-w-[300px] max-w-md mx-auto bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
            <div class="px-4 py-2 bg-gray-100 border-t text-sm text-gray-500 flex justify-between items-center">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2 flex items-center gap-2">
                        <span class="text-lg">{!! $navigation->nav_icon !!}</span>
                        <span>{{ $navigation->title }}</span>
                        <span>{{ $navigation->parent }}</span>
                    </h3>

                </div>
                <p><strong>Status:</strong>
                    @if ($navigation->status === 'active')
                        <span class="text-green-600 font-semibold">Active</span>
                    @else
                        <span class="text-red-600 font-semibold">Inactive</span>
                    @endif
                </p>
            </div>
            <div class="p-4">
                <div class="mb-4 text-gray-600">
                    <p><strong>URL:</strong>
                        {{ $navigation->url }}</p>
                    <p><strong>Route:</strong>
                        {{ $navigation->route }}</p>
                </div>
                @if ($navigation->image)
                    <div class="mt-6 mb-6 flex justify-center">
                        <img src="{{ asset('storage/' . $navigation->image->url) }}"
                            alt="Navigation Image {{ $navigation->title }}"
                            class="rounded-lg shadow-md w-1/2 max-w-md mx-auto">
                    </div>
                @endif
            </div>
            <div class="px-4 py-2 bg-gray-100 border-t text-sm text-gray-500 flex justify-between items-center">
                <div>
                    <span class="">Created on:
                        {{ $navigation->created_at->format('d-M-Y H:i') }}</span>
                    <span class="px-4">Updated on:
                        {{ $navigation->updated_at->format('d-M-Y H:i') }}</span>
                </div>
                <div>
                    <a href="{{ route('navigations.index') }}"
                        class="inline-block text-blue-600 hover:underline px-2">← Back to list</a>
                    <a href="{{ route('navigations.edit', $navigation->uuid) }}"
                        class="text-blue-600 hover:underline px-2">Edit</a>
                    <button class="text-blue-600 hover:underline px-2">Delete</button>
                </div>
            </div>
        </div>
    </div>

    @push('js')
        <script></script>
    @endpush
</x-backend.layouts.master>
