<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ $package->title }}
            </h2>
        </div>
    </x-slot>

    <div class="overflow-x-auto">
        <div class="min-w-[300px] max-w-md mx-auto rounded-lg shadow-md overflow-hidden border border-gray-200">
            <div class="px-4 py-2 bg-gray-100 border-t text-sm text-gray-500 flex justify-between items-center">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $package->title }}</h3>
                    <p class="px-2"><strong>Progress Done:</strong><span class="text-green-600 font-semibold">
                            {{ $package['progress_step'] }} </span></p>
                    <p class="px-2"><strong><a href="#">[Created By:
                                {{ $package->createdBy->title ?? ' ' }}]</strong></a></p>
                </div>
                <div class="text-right">
                    <p><strong>Status:</strong>
                        @if ($package->status === 'active')
                            <span class="text-green-600 font-semibold">Active</span>
                        @else
                            <span class="text-red-600 font-semibold">Inactive</span>
                        @endif
                    </p>
                    <p><strong>Completion Status:</strong>
                        @if ($package['completion_status'] === 'completed')
                            <span class="text-green-600 font-semibold">Complete</span>
                        @else
                            <span class="text-red-600 font-semibold">Incomplete</span>
                        @endif
                    </p>
                </div>
            </div>
            @php
                $imageUrl = $package->images->first()?->url ?? 'default_image_url.jpg';
            @endphp
            <div class="max-w-7xl mx-auto p-6 space-y-8">
                <p><strong>Image Title: </strong>{{ $imageUrl }}</p>
                <img id="image-preview" src="{{ asset('storage/images/packages') . '/' . $imageUrl }}"
                    alt="Image Preview {{ $imageUrl }}" class="rounded-md shadow-md" width="360">

                {{-- @include('backend.packages.pkg-details') --}}

                @if ($package->status === 'active' && $package->completion_status === 'completed')
                    <p class="text-red-500">This package is ACTIVE and COMPLETED.</p>
                @else
                    <p class="text-red-500">This package is INACTIVE and INCOMPLETE. To show full data, please complete
                        the full process of application for package. Thank You!</p>
                @endif

            </div>


            <div class="px-4 py-2 bg-gray-100 border-t text-sm text-gray-500 flex justify-between items-center">
                <div>
                    <span class="">Created on: {{ $package->created_at->format('d-M-Y H:i') }}</span>
                    <span class="px-4">Updated on: {{ $package->updated_at->format('d-M-Y H:i') }}</span>
                </div>
                <div>
                    <a href="{{ route('backend.packages.index') }}"
                        class="inline-block text-blue-600 hover:underline px-2">←
                        Back
                        to list</a>
                    <a href="{{ route('backend.packages.edit', $package->uuid) }}"
                        class="text-blue-600 hover:underline px-2">Edit</a>
                    <button class="text-blue-600 hover:underline px-2">Delete</button>
                </div>
            </div>
        </div>
    </div>


    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const confirmCheckbox = document.getElementById('confirm');
                const submitBtn = document.getElementById('submitBtn');

                confirmCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('bg-gray-600', 'cursor-not-allowed');
                        submitBtn.classList.add('bg-primary');
                    } else {
                        submitBtn.disabled = true;
                        submitBtn.classList.add('bg-gray-600', 'cursor-not-allowed');
                        submitBtn.classList.remove('bg-primary');
                    }
                });
            });
        </script>
    @endpush
</x-backend.layouts.master>
