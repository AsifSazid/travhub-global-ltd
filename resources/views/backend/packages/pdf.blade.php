@php
    use App\Models\City;
    use App\Models\Country;
@endphp
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Package Lists</title>
    <style>
        body {
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            text-align: center;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        th {
            background: #f2f2f2;
        }

        .text-center {
            text-align: center
        }

        .mt-4 {
            margin-top: 2rem;
        }

        h2 {
            margin: 10px 0px 0px 0px;
        }

        h3 {
            margin: 5px 0px;
        }
    </style>
</head>

<body>
    <table style="width: 100%; border: 2px solid #111; border-collapse: collapse; text-align: center;">
        <tr>
            <td style="width: 100%; background-color: #181f3b; color: #fff">
                <h1>{{ $package->title }}</h1>
            </td>
        </tr>
        <tr>
            <td style="width: 100%; background-color: #4abd81">
                <h2>{{ $packQuatDetail->duration }} Days {{ $packQuatDetail->duration - 1 }} Nights</h2>
            </td>
        </tr>
    </table>
    <div class="mt-4">
        <strong>Destinations:</strong>
        @php
            $cities = json_decode($packDestinationInfo['cities'], true);
            if (is_string($cities)) {
                $cities = json_decode($cities, true);
            }
        @endphp

        @foreach ($cities as $city)
            {{ $city['title'] }}
        @endforeach
    </div>
    <div>
        <strong>Travel Date:</strong>
        {{ format_ddmmyyyy($packQuatDetail['start_date']) }} -
        {{ format_ddmmyyyy($packQuatDetail['end_date']) }}
    </div>
    <div>
        <strong>No of Pax:</strong>
        @php
            $pax = json_decode($packQuatDetail['no_of_pax'], true);
            if (is_string($pax)) {
                $pax = json_decode($pax, true);
            }
            $totalPax = 0;
            foreach ($pax as $p) {
                $totalPax = $totalPax + $p['count'];
            }
        @endphp
        {{ $totalPax }}
    </div>
    <div>
        <strong>No of Rooms:</strong>
    </div>
    <div>
        <strong>Prepared For:</strong>
        Bangladesh Bank.
    </div>

    <h2><i>Hotel Information</i></h2>
    <hr style="margin: 0px">
    @if (!empty($hotelOptions) && !empty($allOptions))
        <table style="width: 100%; text-align: center; border-collapse: collapse;">
            <thead>
                <tr>
                    <th style="border: 1px solid #ddd; padding: 8px;">
                        <h3>Location</h3>
                    </th>
                    @foreach ($allOptions as $option)
                        <th style="border: 1px solid #ddd; padding: 8px;">
                            <h3>{{ $option }}</h3>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($hotelOptions as $city => $options)
                    <tr>
                        <td style="border: 1px solid #ddd; padding: 8px; font-weight: bold;">
                            {{ $city }}
                        </td>
                        @foreach ($allOptions as $option)
                            <td style="border: 1px solid #ddd; padding: 8px;">
                                {{ $options[$option] ?? '-' }}
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p>No hotel options available.</p>
    @endif
    <h3>Note:</h3>
    <ul>
        <li>Check-in time: 13:00 or 14:00 | Check-out time: 11:00 or 12:00</li>
        <li>Early check-in or late check-out time is subject to availability and may incur additional charges.</li>
        <li>No hotel room is booked yet. You are requested to revise the room availability before confirmation.</li>
    </ul>

    <!-- Quotation Detail -->
    <h2><i>Package Price</i></h2>
    <hr style="margin: 0px">

    @php
        $prices = $packPrice->pack_price ?? [];

        if (is_string($prices)) {
            $prices = json_decode($prices, true);
        }

    @endphp

    {{-- ---------- FORMAT 1 ---------- --}}
    @if (isset($prices['format1']) && is_array($prices['format1']))
        @foreach ($prices['format1'] as $item)
            <h3>{{ safe($item['title'] ?? '') }}</h3>
            <table>
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
                        <td class="border px-2 py-1">{{ safe($item['land_double'] ?? '') }}</td>
                        <td class="border px-2 py-1">{{ safe($item['land_triple'] ?? '') }}</td>
                        <td class="border px-2 py-1">{{ safe($item['land_single'] ?? '') }}</td>
                    </tr>
                    <tr>
                        <td class="border px-2 py-1">Air Ticket</td>
                        <td colspan="3" class="border px-2 py-1">{{ safe($item['ticket_fare'] ?? '') }}</td>
                    </tr>
                    <tr>
                        <td class="border px-2 py-1">Visa</td>
                        <td colspan="3" class="border px-2 py-1">{{ safe($item['visa'] ?? '') }}</td>
                    </tr>
                    <tr class="bg-gray-100 font-semibold">
                        <td class="border px-2 py-1">Total</td>
                        <td class="border px-2 py-1">{{ safe($item['total_double'] ?? '') }}</td>
                        <td class="border px-2 py-1">{{ safe($item['total_triple'] ?? '') }}</td>
                        <td class="border px-2 py-1">{{ safe($item['total_single'] ?? '') }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @endif

    {{-- ---------- FORMAT 2 ---------- --}}
    @if (isset($prices['format2']) && is_array($prices['format2']))
        @foreach ($prices['format2'] as $item)
            <h3>{{ safe($item['title'] ?? '') }}</h3>
            <table>
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
                        <td class="border px-2 py-1">{{ safe($item['land']['adult'] ?? '') }}</td>
                        <td class="border px-2 py-1">{{ safe($item['land']['child_bed'] ?? '') }}</td>
                        <td class="border px-2 py-1">{{ safe($item['land']['child_no_bed'] ?? '') }}</td>
                        <td class="border px-2 py-1">{{ safe($item['land']['infant'] ?? '') }}</td>
                    </tr>
                    <tr>
                        <td class="border px-2 py-1">Air Ticket</td>
                        <td class="border px-2 py-1">{{ safe($item['air_ticket']['adult'] ?? '') }}</td>
                        <td colspan="2" class="border px-2 py-1">
                            {{ safe($item['air_ticket']['child'] ?? '') }}</td>
                        <td class="border px-2 py-1">{{ safe($item['air_ticket']['infant'] ?? '') }}</td>
                    </tr>
                    <tr>
                        <td class="border px-2 py-1">Visa</td>
                        <td class="border px-2 py-1">{{ safe($item['visa']['adult'] ?? '') }}</td>
                        <td colspan="2" class="border px-2 py-1">{{ safe($item['visa']['child'] ?? '') }}
                        </td>
                        <td class="border px-2 py-1">{{ safe($item['visa']['infant'] ?? '') }}</td>
                    </tr>
                    <tr class="bg-gray-100 font-semibold">
                        <td class="border px-2 py-1">Total</td>
                        <td class="border px-2 py-1">{{ safe($item['total']['adult'] ?? '') }}</td>
                        <td class="border px-2 py-1">{{ safe($item['total']['child_bed'] ?? '') }}</td>
                        <td class="border px-2 py-1">{{ safe($item['total']['child_no_bed'] ?? '') }}</td>
                        <td class="border px-2 py-1">{{ safe($item['total']['infant'] ?? '') }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @endif

    {{-- ---------- FORMAT 3 ---------- --}}
    @if (isset($prices['format3']) && is_array($prices['format3']))
        <div class="space-y-6 mt-2">
            {{-- Activities --}}
            @if (!empty($prices['format3']['activities']))
                <h3>Activities</h3>
                <table>
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
                                <td class="border px-2 py-1">{{ safe($act['name'] ?? '') }}</td>
                                <td class="border px-2 py-1">{{ safe($act['adult'] ?? '') }}</td>
                                <td class="border px-2 py-1">{{ safe($act['child'] ?? '') }}</td>
                                <td class="border px-2 py-1">{{ safe($act['infant'] ?? '') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif

            {{-- Hotels --}}
            @if (!empty($prices['format3']['hotels']))
                <h3>Hotels</h3>
                <table>
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
                                <td class="border px-2 py-1">{{ safe($hotel['location'] ?? '') }}</td>
                                <td class="border px-2 py-1">{{ safe($hotel['name'] ?? '') }}</td>
                                <td class="border px-2 py-1">{{ safe($hotel['room'] ?? '') }}</td>
                                <td class="border px-2 py-1">{{ safe($hotel['price'] ?? '') }}</td>
                                <td class="border px-2 py-1">{{ safe($hotel['extra_bed'] ?? '') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    @endif
    <h3>Note:</h3>
    <ul>
        <li>Ticket is confirmed. Ticket price may vary.</li>
        <li>Though airticket price may change, Land Package price will remain same.</li>
    </ul>

    <!-- Inclusions -->
    <h2><i>Inclusions</i></h2>
    <hr style="margin: 0px">

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
        @foreach ($inclusions as $category)
            <!-- Category header -->
            <h3>{{ $category['title'] ?? 'No title' }}</h3>
            <!-- Sub-inclusions -->
            <ul>
                @if (isset($category['sub_title']) && is_array($category['sub_title']))
                    @foreach ($category['sub_title'] as $sub)
                        @if (isset($sub['selected']) && $sub['selected'] == '1')
                            <li>
                                {{ $sub['text'] ?? 'No text' }}
                            </li>
                        @endif
                    @endforeach
                @else
                    <li>No sub-inclusions added.</li>
                @endif
            </ul>
        @endforeach
    @else
        <div>No inclusions added.</div>
    @endif

    <div style="page-break-after: always"></div>

    <!-- Itineraries -->
    <h2>Itineraries</h2>
    <hr style="margin: 0px">
    @foreach ($packItenaries as $itenary)
        @if ($itenary)
            <h3>{{ $itenary['title'] ?? 'Untitled' }}</h3>

            @if (!empty($itenary['description']))
                <div>{{ $itenary['description'] }}</div>
            @endif

            @if (!empty($itenary['activities']))
                @php
                    $acts = json_decode($itenary['activities'], true);
                    if (is_string($acts)) {
                        $acts = json_decode($acts, true);
                    }
                @endphp
                @if (is_array($acts))
                    <ul>
                        @foreach ($acts as $a)
                            <li><strong>{{ $a['title'] ?? ' - ' }} @if (!empty($a['time']))
                                        ({{ $a['time'] }})
                                    @endif
                                </strong>
                                @if (!empty($a['data']))
                                    <ul>
                                        @foreach ($a['data'] as $key => $aData)
                                            @if ($key == 'city_id')
                                                {{-- @php
                                                    $cityModel = City::find($aData);
                                                    $aData = $cityModel ? $cityModel->title : ' - ';
                                                    $key = 'City';
                                                @endphp
                                                <li>
                                                    <strong>{{ $key }}:</strong>

                                                    {{ !empty($aData) ? $aData : ' - ' }}
                                                </li> --}}
                                            @elseif($key == 'country_id')
                                                {{-- @php
                                                    $countryModel = Country::find($aData);
                                                    $aData = $countryModel ? $countryModel->title : ' - ';
                                                    $key = 'Country';
                                                @endphp
                                                <li>
                                                    <strong>{{ $key }}:</strong>

                                                    {{ !empty($aData) ? $aData : ' - ' }}
                                                </li> --}}
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
                                @if (!empty($a['description']))
                                    <div>
                                        {!! $a['description'] !!}
                                    </div>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                @endif
            @endif

            @if (!empty($itenary['meals']))
                <div><strong>Meal:</strong> {{ ucfirst($itenary['meals']) }}</div>
            @endif
        @endif
    @endforeach


</body>

</html>
