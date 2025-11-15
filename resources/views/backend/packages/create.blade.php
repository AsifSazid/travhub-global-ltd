<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ __('Create Package') }}
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

        <form action="{{ route('backend.packages.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Title --}}
            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
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

            {{-- Image Preview with Close Button --}}
            <div class="mb-4 relative w-64">
                <img id="image-preview" src="#" alt="Image Preview"
                    class="hidden w-full h-auto rounded-md shadow-md">
                <button type="button" id="remove-image"
                    class="absolute top-0 right-0 mt-2 mr-2 bg-white text-red-500 rounded-full w-6 h-6 flex items-center justify-center text-sm hidden"
                    onclick="removeImage()">&times;</button>
            </div>

            {{-- Submit Button --}}
            <div class="mt-6 flex justify-between">
                <span></span>
                <button type="submit"
                    class="flex items-center justify-center px-4 py-2 text-sm text-white rounded-md bg-primary border border-gray-300 dark:bg-white dark:border-gray-200 hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary-dark focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                    Create Package
                </button>
            </div>
        </form>
    </div>

    {{-- JS for Image Preview + Remove --}}
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
                        preview.classList.remove('hidden'); // Show image
                        removeBtn.classList.remove('hidden'); // Show cross button
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
