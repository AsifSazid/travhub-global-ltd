@php
    use App\Models\City; // এই লাইনটা Blade ফাইলের একদম শুরুর দিকে রাখো (PHP ট্যাগের ভেতরে)
    use App\Models\Country; // এই লাইনটা Blade ফাইলের একদম শুরুর দিকে রাখো (PHP ট্যাগের ভেতরে)
@endphp
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
            <div class="max-w-7xl mx-auto p-6 space-y-8">
                <img id="image-preview" src="{{ asset('storage/images/packages').'/'.$package->images->first()->url }}" alt="Image Preview"
                    class="rounded-md shadow-md" width="360">
                <!-- Package Info -->
                {{-- <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
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
                </div> --}}

                <!-- Destination Info -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Destination Info</h2>
                        <a href="{{ route('backend.packages.step', ['uuid' => $uuid, 'step' => '1']) }}"
                            class="text-blue-600 hover:underline text-sm">Edit</a>
                    </div>
                    @if ($packDestinationInfo === 'No Data Found')
                        <p class="text-gray-500">No destinations found.</p>
                    @else
                        {{-- <p><strong>Title:</strong> {{ $packDestinationInfo['title'] }}</p> --}}
                        <p><strong>Country:</strong> {{ $packDestinationInfo['country_title'] }}</p>
                        <p><strong>Cities:</strong></p>
                        <ul class="list-disc list-inside text-gray-700 px-4">
                            @php
                                $cities = json_decode($packDestinationInfo['cities'], true);
                                if (is_string($cities)) {
                                    $cities = json_decode($cities, true);
                                }
                            @endphp

                            @foreach ($cities as $city)
                                <li>
                                    {{ $city['title'] }}
                                </li>
                            @endforeach
                        </ul>
                        <p><strong>Activities:</strong></p>
                        <ul class="list-disc list-inside text-gray-700 px-4">
                            @php
                                $activities = json_decode($packDestinationInfo['activities'], true);
                                if (is_string($activities)) {
                                    $activities = json_decode($activities, true);
                                }
                            @endphp
                            @foreach ($activities as $act)
                                <li>{{ $act['title'] ?? ' - ' }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Quotation Detail -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Quotation Detail</h2>
                        <a href="{{ route('backend.packages.step', ['uuid' => $uuid, 'step' => '2']) }}"
                            class="text-blue-600 hover:underline text-sm">Edit</a>
                    </div>
                    @if ($packQuatDetail === 'No Data Found')
                        <p class="text-gray-500">No quotations found.</p>
                    @else
                        <p><strong>Duration:</strong> {{ $packQuatDetail['duration'] }} days
                            ({{ format_ddmmyyyy($packQuatDetail['start_date']) }} to
                            {{ format_ddmmyyyy($packQuatDetail['end_date']) }})</p>
                        <p><strong>Tour Started From:</strong> {{ format_MM_ddyyyy($packQuatDetail['start_date']) }}
                        </p>
                        <p><strong>Tour Ended In:</strong> {{ format_MM_ddyyyy($packQuatDetail['end_date']) }}</p>
                        <p><strong>No of Pax:</strong></p>
                        <ul class="list-disc list-inside text-gray-700 px-4">
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
                    @endif
                </div>

                <!-- Accommodation Detail -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Accommodation</h2>
                        <a href="{{ route('backend.packages.step', ['uuid' => $uuid, 'step' => '3']) }}"
                            class="text-blue-600 hover:underline text-sm">Edit</a>
                    </div>
                    @if ($packAccomoDetail === 'No Data Found')
                        <p class="text-gray-500">No accommodation details found.</p>
                    @else
                        @php
                            $hotels = json_decode($packAccomoDetail['hotels'], true);
                        @endphp
                        <ul class="list-disc list-inside text-gray-700 px-4">
                            @foreach ($hotels as $hotel)
                                @php
                                    $city = \App\Models\City::findOrFail($hotel['city_id']);
                                @endphp
                                <li>{{ $hotel['title'] }} (in {{ $city->title }})</li>
                            @endforeach
                        </ul>
                    @endif
                </div>

                <!-- Price Details -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Price Details</h2>
                        <a href="{{ route('backend.packages.step', ['uuid' => $uuid, 'step' => '4']) }}"
                            class="text-blue-600 hover:underline text-sm">Edit</a>
                    </div>

                    @if ($packPrice == 'No Data Found')
                        <p class="text-gray-500">No prices found.</p>
                    @else
                        <p><strong>Currency:</strong> {{ $packPrice['currency_title'] ?? 'N/A' }}</p>

                        @php
                            // Decode safely
                            $prices = json_decode($packPrice['pack_price'] ?? '[]', true);
                            if (is_string($prices)) {
                                $prices = json_decode($prices, true);
                            }

                            // Safe display helper
                            function safe($val)
                            {
                                return !empty($val) ? $val : ' - ';
                            }
                        @endphp

                        {{-- ---------- FORMAT 1 ---------- --}}
                        @if (isset($prices['format1']) && is_array($prices['format1']))
                            @foreach ($prices['format1'] as $item)
                                <div class="mb-6">
                                    <h3 class="font-medium mb-2">{{ safe($item['title'] ?? '') }}</h3>
                                    <table class="w-full text-left border border-gray-300 text-sm">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">Particular</th>
                                                <th class="border px-2 py-1">Twin/Double</th>
                                                <th class="border px-2 py-1">Triple</th>
                                                <th class="border px-2 py-1">Single</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="border px-2 py-1">Land Package</td>
                                                <td class="border px-2 py-1">{{ safe($item['land_double'] ?? '') }}
                                                </td>
                                                <td class="border px-2 py-1">{{ safe($item['land_triple'] ?? '') }}
                                                </td>
                                                <td class="border px-2 py-1">{{ safe($item['land_single'] ?? '') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border px-2 py-1">Air Ticket</td>
                                                <td colspan="3" class="border px-2 py-1">
                                                    {{ safe($item['ticket_fare'] ?? '') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="border px-2 py-1">Visa</td>
                                                <td colspan="3" class="border px-2 py-1">
                                                    {{ safe($item['visa'] ?? '') }}
                                                </td>
                                            </tr>
                                            <tr class="bg-gray-100 font-semibold">
                                                <td class="border px-2 py-1">Total</td>
                                                <td class="border px-2 py-1">{{ safe($item['total_double'] ?? '') }}
                                                </td>
                                                <td class="border px-2 py-1">{{ safe($item['total_triple'] ?? '') }}
                                                </td>
                                                <td class="border px-2 py-1">{{ safe($item['total_single'] ?? '') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        @endif

                        {{-- ---------- FORMAT 2 ---------- --}}
                        @if (isset($prices['format2']) && is_array($prices['format2']))
                            @foreach ($prices['format2'] as $item)
                                <div class="mb-6">
                                    <h3 class="font-medium mb-2">{{ safe($item['title'] ?? '') }}</h3>
                                    <table class="w-full text-left border border-gray-300 text-sm">
                                        <thead class="bg-gray-100">
                                            <tr>
                                                <th class="border px-2 py-1">Particular</th>
                                                <th class="border px-2 py-1">Adult</th>
                                                <th class="border px-2 py-1">Child (Bed)</th>
                                                <th class="border px-2 py-1">Child (No Bed)</th>
                                                <th class="border px-2 py-1">Infant</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="border px-2 py-1">Land Package</td>
                                                <td class="border px-2 py-1">{{ safe($item['land']['adult'] ?? '') }}
                                                </td>
                                                <td class="border px-2 py-1">
                                                    {{ safe($item['land']['child_bed'] ?? '') }}
                                                </td>
                                                <td class="border px-2 py-1">
                                                    {{ safe($item['land']['child_no_bed'] ?? '') }}</td>
                                                <td class="border px-2 py-1">{{ safe($item['land']['infant'] ?? '') }}
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="border px-2 py-1">Air Ticket</td>
                                                <td class="border px-2 py-1">
                                                    {{ safe($item['air_ticket']['adult'] ?? '') }}
                                                </td>
                                                <td colspan="2" class="border px-2 py-1">
                                                    {{ safe($item['air_ticket']['child'] ?? '') }}</td>
                                                <td class="border px-2 py-1">
                                                    {{ safe($item['air_ticket']['infant'] ?? '') }}</td>
                                            </tr>
                                            <tr>
                                                <td class="border px-2 py-1">Visa</td>
                                                <td class="border px-2 py-1">{{ safe($item['visa']['adult'] ?? '') }}
                                                </td>
                                                <td colspan="2" class="border px-2 py-1">
                                                    {{ safe($item['visa']['child'] ?? '') }}</td>
                                                <td class="border px-2 py-1">{{ safe($item['visa']['infant'] ?? '') }}
                                                </td>
                                            </tr>
                                            <tr class="bg-gray-100 font-semibold">
                                                <td class="border px-2 py-1">Total</td>
                                                <td class="border px-2 py-1">{{ safe($item['total']['adult'] ?? '') }}
                                                </td>
                                                <td class="border px-2 py-1">
                                                    {{ safe($item['total']['child_bed'] ?? '') }}
                                                </td>
                                                <td class="border px-2 py-1">
                                                    {{ safe($item['total']['child_no_bed'] ?? '') }}</td>
                                                <td class="border px-2 py-1">
                                                    {{ safe($item['total']['infant'] ?? '') }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endforeach
                        @endif

                        {{-- ---------- FORMAT 3 ---------- --}}
                        @if (isset($prices['format3']) && is_array($prices['format3']))
                            <div class="space-y-6 mt-2">
                                {{-- Activities --}}
                                @if (!empty($prices['format3']['activities']))
                                    <div>
                                        <h3 class="font-medium mb-2">Activities</h3>
                                        <table class="w-full text-left border border-gray-300 text-sm">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="border px-2 py-1">Activity</th>
                                                    <th class="border px-2 py-1">Adult</th>
                                                    <th class="border px-2 py-1">Child</th>
                                                    <th class="border px-2 py-1">Infant</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($prices['format3']['activities'] as $act)
                                                    <tr>
                                                        <td class="border px-2 py-1">{{ safe($act['name'] ?? '') }}
                                                        </td>
                                                        <td class="border px-2 py-1">{{ safe($act['adult'] ?? '') }}
                                                        </td>
                                                        <td class="border px-2 py-1">{{ safe($act['child'] ?? '') }}
                                                        </td>
                                                        <td class="border px-2 py-1">{{ safe($act['infant'] ?? '') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif

                                {{-- Hotels --}}
                                @if (!empty($prices['format3']['hotels']))
                                    <div>
                                        <h3 class="font-medium mb-2">Hotels</h3>
                                        <table class="w-full text-left border border-gray-300 text-sm">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="border px-2 py-1">Location</th>
                                                    <th class="border px-2 py-1">Hotel</th>
                                                    <th class="border px-2 py-1">Room</th>
                                                    <th class="border px-2 py-1">Price/Night</th>
                                                    <th class="border px-2 py-1">Extra Bed</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($prices['format3']['hotels'] as $hotel)
                                                    <tr>
                                                        <td class="border px-2 py-1">
                                                            {{ safe($hotel['location'] ?? '') }}
                                                        </td>
                                                        <td class="border px-2 py-1">{{ safe($hotel['name'] ?? '') }}
                                                        </td>
                                                        <td class="border px-2 py-1">{{ safe($hotel['room'] ?? '') }}
                                                        </td>
                                                        <td class="border px-2 py-1">{{ safe($hotel['price'] ?? '') }}
                                                        </td>
                                                        <td class="border px-2 py-1">
                                                            {{ safe($hotel['extra_bed'] ?? '') }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        @endif

                        {{-- ---------- AIR TICKET DETAILS ---------- --}}
                        <div class="mt-4">
                            <p class="font-semibold mb-1">Air Ticket Details:</p>
                            <div class="prose leading-relaxed text-gray-700">
                                {!! $packPrice['air_ticket_details'] ?? ' - ' !!}
                            </div>
                        </div>
                    @endif
                </div>


                <!-- Itineraries -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Itineraries</h2>
                        <a href="{{ route('backend.packages.step', ['uuid' => $uuid, 'step' => '5']) }}"
                            class="text-blue-600 hover:underline text-sm">Edit</a>
                    </div>
                    @forelse ($packItenaries as $itenary)
                        @if ($itenary)
                            <div class="border-b border-gray-200 mb-4 pb-4">
                                <h3>{{ $itenary['title'] ?? 'Untitled' }}</h3>

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
                                        <ul class="list-disc list-inside mt-2 px-4">
                                            @foreach ($acts as $a)
                                                <li class="mt-4">{{ $a['title'] ?? ' - ' }} @if (!empty($a['time']))
                                                        ({{ $a['time'] }})
                                                    @endif
                                                    @if (!empty($a['description']))
                                                        <div class="text-sm text-gray-600 ml-6 px-6">
                                                            {!! $a['description'] !!}
                                                        </div>
                                                    @endif
                                                    @if (!empty($a['data']))
                                                        <ul class="list-disc list-inside mt-2 px-6">
                                                            @foreach ($a['data'] as $key => $aData)
                                                                @if ($key == 'city_id')
                                                                    @php
                                                                        $cityModel = City::find($aData);
                                                                        $aData = $cityModel ? $cityModel->title : ' - ';
                                                                        $key = 'City';
                                                                    @endphp
                                                                    <li>
                                                                        <strong>{{ $key }}:</strong>

                                                                        {{ !empty($aData) ? $aData : ' - ' }}
                                                                    </li>
                                                                @elseif($key == 'country_id')
                                                                    @php
                                                                        $countryModel = Country::find($aData);
                                                                        $aData = $countryModel
                                                                            ? $countryModel->title
                                                                            : ' - ';
                                                                        $key = 'Country';
                                                                    @endphp
                                                                    <li>
                                                                        <strong>{{ $key }}:</strong>

                                                                        {{ !empty($aData) ? $aData : ' - ' }}
                                                                    </li>
                                                                @else
                                                                    <li>
                                                                        {{-- প্রথমে কী (key) দেখান --}}
                                                                        <strong>{{ $key }}:</strong>

                                                                        {{-- এরপর $aData empty কি না চেক করুন এবং ফাঁকা হলে ' - ' দেখান --}}
                                                                        {{ !empty($aData) ? $aData : ' - ' }}
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                @endif

                                @if (!empty($itenary['meals']))
                                    <p class="mt-6"><strong>Meal:</strong> {{ ucfirst($itenary['meals']) }}</p>
                                @endif
                            </div>
                        @endif
                    @empty
                        <p class="text-gray-500">No inclusions found.</p>
                    @endforelse

                </div>

                <!-- Inclusions -->
                <div class="bg-white rounded-xl shadow p-6 border border-gray-200">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">Inclusions</h2>
                        <a href="{{ route('backend.packages.step', ['uuid' => $uuid, 'step' => '5']) }}"
                            class="text-blue-600 hover:underline text-sm">Edit</a>
                    </div>

                    @php
                        $inclusions = [];

                        if ($packInclusion && !empty($packInclusion->inclusions)) {
                            $inclusions = json_decode($packInclusion->inclusions, true);

                            // Handle double-encoded JSON
                            if (is_string($inclusions)) {
                                $inclusions = json_decode($inclusions, true);
                            }

                            if (!is_array($inclusions)) {
                                $inclusions = [];
                            }
                        }
                    @endphp

                    @if (!empty($inclusions))
                        <div class="space-y-6">
                            @foreach ($inclusions as $category)
                                <div class="bg-white rounded-lg shadow p-4 border border-gray-100">
                                    <!-- Category header -->
                                    <div class="flex items-center gap-2 mb-3">
                                        <i
                                            class="fa-solid {{ $category['icons'] ?? 'fa-circle-info' }} text-blue-600"></i>
                                        <h3 class="text-lg font-semibold text-blue-700">
                                            {{ $category['title'] ?? 'No title' }}</h3>
                                    </div>

                                    <!-- Sub-inclusions -->
                                    <ul class="list-disc list-inside text-gray-700">
                                        @if (isset($category['sub_title']) && is_array($category['sub_title']))
                                            @foreach ($category['sub_title'] as $sub)
                                                @if (isset($sub['selected']) && $sub['selected'] == '1')
                                                    <li>
                                                        {{ $sub['text'] ?? 'No text' }}
                                                        @if (isset($sub['type']) && $sub['type'] === 'custom')
                                                            <span class="text-sm text-green-600">(Custom)</span>
                                                        @endif
                                                    </li>
                                                @endif
                                            @endforeach
                                        @else
                                            <li class="text-gray-500">No sub-inclusions found.</li>
                                        @endif
                                    </ul>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500">No inclusions found.</p>
                    @endif
                </div>

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
                    <a href="{{ route('backend.packages.index') }}" class="inline-block text-blue-600 hover:underline px-2">←
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
