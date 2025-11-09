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
                    <p class="px-4"><strong><a href="#">[Created By:
                                {{ $package->createdBy->title ?? ' ' }}]</strong></a></p>
                </div>
                <p><strong>Status:</strong>
                    @if ($package->status === 'active')
                        <span class="text-green-600 font-semibold">Active</span>
                    @else
                        <span class="text-red-600 font-semibold">Inactive</span>
                    @endif
                </p>
            </div>
            <div class="p-4">
                <h4 class="text-lg font-semibold text-gray-800 mb-2">{{ __('Destination Information') }}</h4>
                <p class="text-gray-600 mt-2 mb-4">
                    <label for="country_code" class="font-semibold">Country: </label>
                    {{ $packDestinationInfo->country->title }}
                </p>
                <p class="text-gray-600 mt-2 mb-4">
                    <label for="country_code" class="font-semibold">Cities: </label> {{ $packDestinationInfo->cities }}
                </p>
            </div>
            <div class="max-w-7xl mx-auto p-6 space-y-8">

                <!-- Package Info -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold mb-4">Package Info</h2>
                    <table class="w-full text-left">
                        <tbody>
                            <tr>
                                <th class="pr-4">ID</th>
                                <td>{{ $package['id'] }}</td>
                            </tr>
                            <tr>
                                <th class="pr-4">UUID</th>
                                <td>{{ $package['uuid'] }}</td>
                            </tr>
                            <tr>
                                <th class="pr-4">Title</th>
                                <td>{{ $package['title'] }}</td>
                            </tr>
                            <tr>
                                <th class="pr-4">Status</th>
                                <td>{{ $package['status'] }}</td>
                            </tr>
                            <tr>
                                <th class="pr-4">Completion Status</th>
                                <td>{{ $package['completion_status'] }}</td>
                            </tr>
                            <tr>
                                <th class="pr-4">Progress Step</th>
                                <td>{{ $package['progress_step'] }}</td>
                            </tr>
                            <tr>
                                <th class="pr-4">Created At</th>
                                <td>{{ $package['created_at'] }}</td>
                            </tr>
                            <tr>
                                <th class="pr-4">Updated At</th>
                                <td>{{ $package['updated_at'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Destination Info -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold mb-4">Destination Info</h2>
                    <p><strong>Title:</strong> {{ $packDestinationInfo['title'] }}</p>
                    <p><strong>Country:</strong> {{ $packDestinationInfo['country_title'] }}</p>
                    <p><strong>Cities:</strong>
                        @php
                            $cities = json_decode($packDestinationInfo['cities'], true);
                            if (is_string($cities)) {
                                $cities = json_decode($cities, true);
                            }
                        @endphp
                        @foreach ($cities as $city)
                            {{ $city['title'] }}@if (!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </p>
                    <p><strong>Activities:</strong></p>
                    <ul class="list-disc list-inside">
                        @php
                            $activities = json_decode($packDestinationInfo['activities'], true);
                            if (is_string($activities)) {
                                $activities = json_decode($activities, true);
                            }
                        @endphp
                        @foreach ($activities as $act)
                            <li>{{ $act['title'] ?? '' }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Quotation Detail -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold mb-4">Quotation Detail</h2>
                    <p><strong>Duration:</strong> {{ $packQuatDetail['duration'] }} days</p>
                    <p><strong>Start Date:</strong> {{ $packQuatDetail['start_date'] }}</p>
                    <p><strong>End Date:</strong> {{ $packQuatDetail['end_date'] }}</p>
                    <p><strong>No of Pax:</strong></p>
                    <ul class="list-disc list-inside">
                        @php
                            $pax = json_decode($packQuatDetail['no_of_pax'], true);
                            if (is_string($pax)) {
                                $pax = json_decode($pax, true);
                            }
                        @endphp
                        @foreach ($pax as $p)
                            <li>{{ ucfirst($p['type']) }}: {{ $p['count'] }}</li>
                        @endforeach
                    </ul>
                </div>

                <!-- Accommodation Detail -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold mb-4">Accommodation</h2>
                    @php
                        $hotels = json_decode($packAccomoDetail['hotels'], true);
                    @endphp
                    <ul class="list-disc list-inside">
                        @foreach ($hotels as $hotel)
                            <li>{{ $hotel['title'] }} (City ID: {{ $hotel['city_id'] }}, Type: {{ $hotel['type'] }})
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Price Detail -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold mb-4">Price Details</h2>
                    <p><strong>Currency:</strong> {{ $packPrice['currency_title'] }}</p>
                    @php
                        $prices = json_decode($packPrice['pack_price'], true);
                        if (is_string($prices)) {
                            $prices = json_decode($prices, true);
                        }
                    @endphp
                    @if (is_array($prices))
                        <table class="w-full text-left border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border px-2 py-1">Type</th>
                                    <th class="border px-2 py-1">Adult</th>
                                    <th class="border px-2 py-1">Child</th>
                                    <th class="border px-2 py-1">Infant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($prices['format3']['activities'] as $actPrice)
                                    <tr>
                                        <td class="border px-2 py-1">{{ $actPrice['name'] ?? '' }}</td>
                                        <td class="border px-2 py-1">{{ $actPrice['adult'] ?? '' }}</td>
                                        <td class="border px-2 py-1">{{ $actPrice['child'] ?? '' }}</td>
                                        <td class="border px-2 py-1">{{ $actPrice['infant'] ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                    <p class="mt-2"><strong>Air Ticket Details:</strong></p>
                    {!! $packPrice['air_ticket_details'] !!}
                </div>
                
                <!-- Itineraries -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold mb-4">Itineraries</h2>
                    @foreach ($packItenaries as $itenary)
                        @if ($itenary)
                            <div class="border-b border-gray-200 mb-4 pb-4">
                                <h3 class="font-semibold">{{ $itenary['title'] ?? 'Untitled' }}</h3>
                                @if (!empty($itenary['description']))
                                    <p>{{ $itenary['description'] }}</p>
                                @endif

                                @if (!empty($itenary['activities']))
                                    @php
                                        $acts = json_decode($itenary['activities'], true);
                                        if (is_string($acts)) {
                                            $acts = json_decode($acts, true);
                                        }
                                    @endphp
                                    @if (is_array($acts))
                                        <ul class="list-disc list-inside">
                                            @foreach ($acts as $a)
                                                <li>{{ $a['title'] ?? '' }} @if (!empty($a['time']))
                                                        ({{ $a['time'] }})
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                @endif

                                @if (!empty($itenary['meal']))
                                    <p><strong>Meal:</strong> {{ ucfirst($itenary['meal']) }}</p>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>

                <!-- Inclusions -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold mb-4">Inclusions</h2>
                    @php
                        $inclusions = [];

                        // Check if second itineraries element exists
                        if (isset($packItenaries[1]) && !empty($packItenaries[1]['inclusions'])) {
                            $inclusions = json_decode($packItenaries[1]['inclusions'], true);

                            // Handle double-encoded JSON
                            if (is_string($inclusions)) {
                                $inclusions = json_decode($inclusions, true);
                            }

                            // Ensure it's an array
                            if (!is_array($inclusions)) {
                                $inclusions = [];
                            }
                        }
                    @endphp

                    @if (!empty($inclusions))
                        <ul class="list-disc list-inside">
                            @foreach ($inclusions as $dayInclusions)
                                @foreach ($dayInclusions as $inc)
                                    <li>{{ $inc['text'] ?? 'No text' }}</li>
                                @endforeach
                            @endforeach
                        </ul>
                    @else
                        <p>No inclusions found.</p>
                    @endif
                </div>

            </div>

            <div class="px-4 py-2 bg-gray-100 border-t text-sm text-gray-500 flex justify-between items-center">
                <div>
                    <span class="">Created on: {{ $package->created_at->format('d-M-Y H:i') }}</span>
                    <span class="px-4">Updated on: {{ $package->updated_at->format('d-M-Y H:i') }}</span>
                </div>
                <div>
                    <a href="{{ route('packages.index') }}" class="inline-block text-blue-600 hover:underline px-2">←
                        Back
                        to list</a>
                    <a href="{{ route('packages.edit', $package->uuid) }}"
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
