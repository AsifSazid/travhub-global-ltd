<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ $inclusion->title }} {{ __('Inclusion') }}
            </h2>
        </div>
    </x-slot>

    <div class="overflow-x-auto">
        <div class="min-w-[300px] max-w-md mx-auto rounded-lg shadow-md overflow-hidden border border-gray-200">
            <div class="px-4 py-2 bg-gray-100 border-t text-sm text-gray-500 flex justify-between items-center">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $inclusion->title }}</h3>
                    <p class="px-4"><strong><a href="#">[Created By: {{ $inclusion->createdBy->title ?? ' ' }}]</strong></a></p>
                </div>
                <p><strong>Status:</strong>
                    @if ($inclusion->status === 'active')
                        <span class="text-green-600 font-semibold">Active</span>
                    @else
                        <span class="text-red-600 font-semibold">Inactive</span>
                    @endif
                </p>
            </div>
            <div class="p-4">
                <p class="text-gray-600 mt-2 mb-4">
                    <label for="country_code" class="font-semibold">Inclusion Code: </label> {{ $inclusion->country_code }}
                </p>
            </div>
            <div class="p-6">
                <label for="" class="block text-sm font-medium text-gray-700">City Lists
                    [Total City: <strong>{{ $inclusion->cities_count }}</strong>]</label>
                <table id="roleTable" class="w-full table-striped table-bordered text-sm mt-4">
                    <thead class="bg-gray-100 text-gray-700 uppercase">
                        <tr>
                            <th class="px-6 py-4">Sl No.</th>
                            <th class="px-6 py-4">City Name</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse ($inclusion->cities as $city)
                            <tr>
                                <td class="py-2">{{ $loop->iteration }}</td>
                                <td><a href="{{ route('cities.show', ['city' => $city->uuid]) }}">{{ $city->title }}</a></td>
                                <td>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $city->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $city->status == 'active' ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-2">
                                    No cities found for this inclusion.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="px-4 py-2 bg-gray-100 border-t text-sm text-gray-500 flex justify-between items-center">
                <div>
                    <span class="">Created on: {{ $inclusion->created_at->format('d-M-Y H:i') }}</span>
                    <span class="px-4">Updated on: {{ $inclusion->updated_at->format('d-M-Y H:i') }}</span>
                </div>
                <div>
                    <a href="{{ route('inclusions.index') }}" class="inline-block text-blue-600 hover:underline px-2">←
                        Back
                        to list</a>
                    <a href="{{ route('inclusions.edit', $inclusion->uuid) }}"
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
