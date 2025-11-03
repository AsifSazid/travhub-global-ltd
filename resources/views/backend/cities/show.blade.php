<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ $city->title }} {{ __('City') }}
            </h2>
        </div>
    </x-slot>

    <div class="overflow-x-auto">
        <div class="min-w-[300px] max-w-md mx-auto rounded-lg shadow-md overflow-hidden border border-gray-200">
            <div class="px-4 py-2 bg-gray-100 border-t text-sm text-gray-500 flex justify-between items-center">
                <div class="flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $city->title }}</h3>
                    <p class="px-4"><strong><a href="#">[Created By: {{ $city->createdBy->title ?? ' ' }}]</strong></a></p>
                </div>
                <p><strong>Status:</strong>
                    @if ($city->status === 'active')
                        <span class="text-green-600 font-semibold">Active</span>
                    @else
                        <span class="text-red-600 font-semibold">Inactive</span>
                    @endif
                </p>
            </div>
            <div class="p-4">
                <p class="text-gray-600 mt-2 mb-4">
                    <label for="country_title" class="font-semibold">Country: </label> <a href="{{ route('countries.show', ['country' => $city->country->uuid]) }}">{{ $city->country->title }}</a>
                </p>
            </div>

            <div class="p-6">
                <label for="" class="block text-sm font-medium text-gray-700">Hotel Lists
                    [Total Hotel: <strong>{{ $city->hotels_count }}</strong>]</label>
                <table id="roleTable" class="w-full table-striped table-bordered text-sm mt-4">
                    <thead class="bg-gray-100 text-gray-700 uppercase">
                        <tr>
                            <th class="px-6 py-4">Sl No.</th>
                            <th class="px-6 py-4">Hotel Name</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse ($city->hotels as $hotel)
                            <tr>
                                <td class="py-2">{{ $loop->iteration }}</td>
                                <td><a href="{{ route('hotels.show', ['hotel' => $hotel->uuid]) }}">{{ $hotel->title }}</a></td>
                                <td>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $hotel->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $hotel->status == 'active' ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    No cities found for this hotel.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-6">
                <label for="" class="block text-sm font-medium text-gray-700">Activity Lists
                    [Total activites: <strong>{{ $activities->count() }}</strong>]</label>
                <table id="roleTable" class="w-full table-striped table-bordered text-sm mt-4">
                    <thead class="bg-gray-100 text-gray-700 uppercase">
                        <tr>
                            <th class="px-6 py-4">Sl No.</th>
                            <th class="px-6 py-4">Activites Title</th>
                            <th class="px-6 py-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse ($activities as $activity)
                            <tr>
                                <td class="py-2">{{ $loop->iteration }}</td>
                                <td><a href="{{ route('activities.show', ['activity' => $activity->uuid]) }}">{{ $activity->title }}</a></td>
                                <td>
                                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $activity->status == 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $activity->status == 'active' ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    No cities found for this hotel.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-4 py-2 bg-gray-100 border-t text-sm text-gray-500 flex justify-between items-center">
                <div>
                    <span class="">Created on: {{ $city->created_at->format('d-M-Y H:i') }}</span>
                    <span class="px-4">Updated on: {{ $city->updated_at->format('d-M-Y H:i') }}</span>
                </div>
                <div>
                    <a href="{{ route('cities.index') }}" class="inline-block text-blue-600 hover:underline px-2">←
                        Back
                        to list</a>
                    <a href="{{ route('cities.edit', $city->uuid) }}"
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
