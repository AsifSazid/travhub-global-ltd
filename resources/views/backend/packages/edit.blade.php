<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ __('Edit Package') }}
            </h2>
        </div>
    </x-slot>

    <div class="overflow-x-auto">

        {{-- Show Validation Errors --}}
        @if ($errors->any())
        <div class="mb-4 text-red-600">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('packages.update', $pkg->uuid) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text"
                    name="title"
                    id="title"
                    value="{{ old('title', $pkg->title) }}"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm
                              focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mt-6 flex justify-between">
                <a href="{{ route('packages.index') }}"
                    class="px-4 py-2 text-sm rounded-md border bg-gray-100 hover:bg-gray-200">
                    Back
                </a>

                <button type="submit"
                    class="flex items-center justify-center px-4 py-2 text-sm text-white rounded-md
                           bg-primary border border-gray-300 dark:bg-white dark:border-gray-200
                           hover:bg-primary-dark focus:outline-none focus:ring
                           focus:ring-primary-dark focus:ring-offset-1 focus:ring-offset-white
                           dark:focus:ring-offset-dark">
                    Update Package
                </button>
            </div>
        </form>
    </div>

    @push('js')
    @endpush
</x-backend.layouts.master>