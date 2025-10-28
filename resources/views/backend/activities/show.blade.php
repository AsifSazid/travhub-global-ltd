<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ $activity->title }} {{ __('Activity') }}
            </h2>
        </div>
    </x-slot>

    <div class="overflow-x-auto">
        <div class="min-w-[300px] max-w-md mx-auto rounded-lg shadow-md overflow-hidden border border-gray-200">
            <div class="px-4 py-2 bg-gray-100 border-t text-sm text-gray-500 flex justify-between items-center">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $activity->title }}</h3>
                    <p class="px-4"><strong><a href="#">[Created By: {{ $activity->createdBy->title ?? ' ' }}]</strong></a></p>
                </div>
                <p><strong>Status:</strong>
                    @if ($activity->status === 'active')
                        <span class="text-green-600 font-semibold">Active</span>
                    @else
                        <span class="text-red-600 font-semibold">Inactive</span>
                    @endif
                </p>
            </div>
            <div class="p-4">
                <p class="text-gray-600 mt-2 mb-4">
                    <label for="category_title" class="font-semibold">Category: </label>{{ $activity->title }}</a>
                </p>
            </div>
            <div class="p-6">
                <label for="" class="block text-sm font-medium text-gray-700">Activity Lists
                    [Total Activity: <strong>{{ $activity->hotels_count }}</strong>]</label>
                <table id="roleTable" class="w-full table-striped table-bordered text-sm mt-4">
                    <thead class="bg-gray-100 text-gray-700 uppercase">
                        <tr>
                            <th class="px-6 py-4">Sl No.</th>
                            <th class="px-6 py-4">Activity Name</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse ($activity->inclusions as $inclusion)
                            <tr>
                                <td class="py-2">{{ $loop->iteration }}</td>
                                <td><a href="{{ route('inclusions.show', ['inclusion' => $inclusion->uuid]) }}">{{ $inclusion->title }}</a></td>
                                <td>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $inclusion->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $inclusion->status == 'active' ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    No activities found for this inclusion.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-2 bg-gray-100 border-t text-sm text-gray-500 flex justify-between items-center">
                <div>
                    <span class="">Created on: {{ $activity->created_at->format('d-M-Y H:i') }}</span>
                    <span class="px-4">Updated on: {{ $activity->updated_at->format('d-M-Y H:i') }}</span>
                </div>
                <div>
                    <a href="{{ route('activities.index') }}" class="inline-block text-blue-600 hover:underline px-2">←
                        Back
                        to list</a>
                    <a href="{{ route('activities.edit', $activity->uuid) }}"
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
