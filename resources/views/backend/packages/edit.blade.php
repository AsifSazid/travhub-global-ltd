<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ __('Edit Package') }}
            </h2>
        </div>
    </x-slot>

    <div class="overflow-x-auto">

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('backend.packages.update', $package->uuid) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Title --}}
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" value="{{ $package->title }}"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            {{-- Description --}}
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" rows="4"
                    class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ $package->description }}</textarea>
            </div>

            {{-- Rating --}}
            <div class="mb-4">
                <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                <select name="rating" id="rating"
                    class="mt-1 p-2 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Select Rating</option>
                    <option value="1" {{ $package->rating == 1 ? 'selected' : '' }}>1 Star</option>
                    <option value="2" {{ $package->rating == 2 ? 'selected' : '' }}>2 Stars</option>
                    <option value="3" {{ $package->rating == 3 ? 'selected' : '' }}>3 Stars</option>
                    <option value="4" {{ $package->rating == 4 ? 'selected' : '' }}>4 Stars</option>
                    <option value="5" {{ $package->rating == 5 ? 'selected' : '' }}>5 Stars</option>
                </select>
            </div>


            {{-- Image Upload --}}
            <div class="mb-4">
                <label for="image" class="block text-sm font-medium text-gray-700">Package Image</label>
                <input type="file" name="image" id="image" onchange="previewImage(event)"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                @error('image')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Image Preview --}}
            <div class="mb-4 relative w-64">
                <img id="image-preview"
                    src="{{ $package->images->first() ? asset('storage/images/packages/' . $package->images->first()->url) : '#' }}"
                    class="{{ $package->images->first() ? '' : 'hidden' }} w-full h-auto rounded-md shadow-md">

                <button type="button" id="remove-image"
                    class="absolute top-0 right-0 mt-2 mr-2 bg-white text-red-500 rounded-full w-6 h-6 flex items-center justify-center text-sm {{ $package->images->first() ? '' : 'hidden' }}"
                    onclick="removeImage()">&times;</button>
            </div>

            {{-- Submit --}}
            <div class="mt-6 flex justify-between">
                <span></span>
                <button type="submit"
                    class="flex items-center justify-center px-4 py-2 text-sm text-white rounded-md bg-primary border border-gray-300 dark:bg-white dark:border-gray-200 hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary-dark focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                    Update Package
                </button>
            </div>
        </form>

    </div>

    {{-- JS --}}
    @push('js')
        <script>
            function previewImage(event) {
                const input = event.target;
                const preview = document.getElementById('image-preview');
                const removeBtn = document.getElementById('remove-image');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        removeBtn.classList.remove('hidden');
                    }
                    reader.readAsDataURL(input.files[0]);
                } else {
                    removeImage();
                }
            }

            function removeImage() {
                const input = document.getElementById('image');
                const preview = document.getElementById('image-preview');
                const removeBtn = document.getElementById('remove-image');

                input.value = "";
                preview.src = "#";
                preview.classList.add('hidden');
                removeBtn.classList.add('hidden');
            }
        </script>
    @endpush
</x-backend.layouts.master>
