<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div>
        <label>Currency</label>
        <select name="currency_id" class="mt-1 w-full border rounded px-3 py-2">
            <option value="">Select Currency</option>
            @foreach ($currencies as $currency)
                <option value="{{ $currency->id }}"
                    {{ old('currency_id', $pkgPrice->currency_id ?? '') == $currency->id ? 'selected' : '' }}>
                    {{ $currency->title }}</option>
            @endforeach
        </select>
        @error('currency_id')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2">
        <label>Overall Price (This price is show in the Frontend)</label>
        <input id="overall_price_id" name="overall_price" class="mt-1 w-full border rounded px-3 py-2"
            value="{{ $pkgPrice->overall_price ?? '' }}">
        @error('overall_price_id')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    @php
        $selectedKey = '';

        // 1. Check if the array exists and is actually an array
        if (isset($pkgPrice['pack_price']) && is_array($pkgPrice['pack_price'])) {
            // 2. Get the keys
            $keys = array_keys($pkgPrice['pack_price']);

            // 3. Check if the keys array is not empty
            if (!empty($keys)) {
                $selectedKey = $keys[0];
            }
        }
    @endphp

    <div class="md:col-span-2">
        <label>Price Options</label>
        <select id="formatSelect" class="mt-1 w-full border rounded px-3 py-2">
            <option value="">-- Select Format --</option>
            <option value="format1" {{ $selectedKey == 'format1' ? 'selected' : '' }}>Format 1 (Twin/Triple/Single)
            </option>
            <option value="format2" {{ $selectedKey == 'format2' ? 'selected' : '' }}>Format 2 (Adult/Child/Infant)
            </option>
            <option value="format3" {{ $selectedKey == 'format3' ? 'selected' : '' }}>Format 3 (Activities + Hotels)
            </option>
        </select>

    </div>

</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">

    <div class="col-span-2">
        <!-- ---------------- FORMAT ONE ---------------- -->
        <div id="formatOneTable" class="hidden pt-6 border-t">

            <h2 class="text-lg font-semibold mb-3">Format One</h2>

            <div id="formatOneWrapper">

                <div class="format1-box mb-6 p-4 border rounded-lg bg-gray-50">

                    <table class="w-full border border-gray-300 text-sm format1-table">
                        <thead class="bg-gray-200">
                            <tr>
                                <th colspan="5">
                                    @foreach ($cities as $city)
                                        <div class="mb-2">
                                            <strong>{{ $city['title'] }}</strong><br>
                                            @foreach (collect($hotels)->where('city_id', $city['id']) as $hotel)
                                                <label class="inline-flex items-center mr-2">
                                                    <input type="radio" name="format1_hotel_{{ $city['id'] }}"
                                                        value="{{ $hotel->id }}">
                                                    <span class="ml-1">{{ $hotel->title }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </th>
                            </tr>
                            <tr>
                                <th class="border p-2 w-1/4">Title</th>
                                <th colspan="3" class="border p-2">
                                    <input type="text" name="format1_title[]" class="w-full border rounded p-1"
                                        placeholder="Enter Title">
                                </th>
                            </tr>

                            <tr>
                                <th class="border p-2">Particulars</th>
                                <th class="border p-2">Twin/Double</th>
                                <th class="border p-2">Triple</th>
                                <th class="border p-2">Single</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="border p-2">Land Package</td>
                                <td class="border p-2"><input type="text" name="land_double[]"
                                        class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="land_triple[]"
                                        class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="land_single[]"
                                        class="w-full border rounded p-1"></td>
                            </tr>

                            <tr>
                                <td class="border p-2">Air Ticket</td>
                                <td colspan="3" class="border p-2"><input type="text" name="ticket_fare[]"
                                        class="w-full border rounded p-1"></td>
                            </tr>

                            <tr>
                                <td class="border p-2">Visa</td>
                                <td colspan="3" class="border p-2"><input type="text" name="visa[]"
                                        class="w-full border rounded p-1"></td>
                            </tr>

                            <tr class="bg-gray-100 font-semibold">
                                <td class="border p-2">Total</td>
                                <td class="border p-2"><input type="text" name="total_double[]"
                                        class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="total_triple[]"
                                        class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="total_single[]"
                                        class="w-full border rounded p-1"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

            <button type="button" id="addFormat1Block"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Add More Option (Format 1)
            </button>

        </div>

        <!-- ---------------- FORMAT TWO ---------------- -->

        <div id="formatTwoTable" class="hidden pt-6 border-t">
            <h2 class="text-lg font-semibold mb-3">Format Two</h2>

            <div id="formatTwoWrapper">
                <div class="format2-box mb-6 p-4 border rounded-lg bg-gray-50">

                    <table class="w-full border border-gray-300 text-sm format2-table">
                        <thead class="bg-gray-200">
                            <tr>
                                <th colspan="5">
                                    @foreach ($cities as $city)
                                        <div class="mb-2">
                                            <strong>{{ $city['title'] }}</strong><br>
                                            @foreach (collect($hotels)->where('city_id', $city['id']) as $hotel)
                                                <label class="inline-flex items-center mr-2">
                                                    <input type="radio" name="format2_hotel_{{ $city['id'] }}"
                                                        value="{{ $hotel->id }}">
                                                    <span class="ml-1">{{ $hotel->title }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </th>
                            </tr>
                            <tr>
                                <th class="border p-2 w-1/4">Title</th>
                                <th colspan="4" class="border p-2">
                                    <input type="text" name="format2_title[]" class="w-full border rounded p-1"
                                        placeholder="Enter Title">
                                </th>
                            </tr>

                            <tr>
                                <th class="border p-2">Particulars</th>
                                <th class="border p-2">Adult</th>
                                <th class="border p-2">Child With Bed</th>
                                <th class="border p-2">Child No Bed</th>
                                <th class="border p-2">Infant</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td class="border p-2">Land Package</td>
                                <td class="border p-2"><input type="text" name="f2_adult[]"
                                        class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="f2_child_bed[]"
                                        class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="f2_child_no_bed[]"
                                        class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="f2_infant[]"
                                        class="w-full border rounded p-1"></td>
                            </tr>

                            <tr>
                                <td class="border p-2">Air Ticket</td>
                                <td class="border p-2"><input type="text" name="f2_air_adult[]"
                                        class="w-full border rounded p-1"></td>
                                <td colspan="2" class="border p-2"><input type="text" name="f2_air_child[]"
                                        class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="f2_air_infant[]"
                                        class="w-full border rounded p-1"></td>
                            </tr>

                            <tr>
                                <td class="border p-2">Visa</td>
                                <td class="border p-2"><input type="text" name="f2_visa_adult[]"
                                        class="w-full border rounded p-1"></td>
                                <td colspan="2" class="border p-2"><input type="text" name="f2_visa_child[]"
                                        class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="f2_visa_infant[]"
                                        class="w-full border rounded p-1"></td>
                            </tr>

                            <tr class="bg-gray-100 font-semibold">
                                <td class="border p-2">Total</td>
                                <td class="border p-2"><input type="text" name="f2_total_adult[]"
                                        class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="f2_total_child_bed[]"
                                        class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="f2_total_child_no_bed[]"
                                        class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="f2_total_infant[]"
                                        class="w-full border rounded p-1"></td>
                            </tr>
                        </tbody>
                    </table>

                </div>
            </div>

            <button type="button" id="addFormat2Block"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                + Add More Option (Format 2)
            </button>
        </div>

        <!-- ---------------- FORMAT THREE ---------------- -->

        <div id="formatThreeTable" class="hidden pt-6 border-t">
            <h2 class="text-lg font-semibold">Format Three</h2>

            <!-- Activities -->
            <h3 class="font-medium mt-4 mb-2">Activities</h3>
            <table class="w-full border border-gray-300 text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border p-2">Activity</th>
                        <th class="border p-2">Adult</th>
                        <th class="border p-2">Child</th>
                        <th class="border p-2">Infant</th>
                        <th class="border p-2">Action</th>
                    </tr>
                </thead>
                <tbody id="activitiesBody">
                    <tr class="activity-row">
                        <td class="border p-2"><input name="act_name[]" class="w-full border rounded p-1"></td>
                        <td class="border p-2"><input name="act_adult[]" class="w-full border rounded p-1"></td>
                        <td class="border p-2"><input name="act_child[]" class="w-full border rounded p-1"></td>
                        <td class="border p-2"><input name="act_infant[]" class="w-full border rounded p-1"></td>
                        <td class="border p-2 text-center">
                            <button type="button" id="addActivityRow"
                                class="bg-primary text-white px-2 py-1 rounded text-xs rounded"> + </button>
                        </td>
                    </tr>
                </tbody>
            </table>


            <!-- Hotels -->
            <h3 class="font-medium mt-6 mb-2">Hotels</h3>
            <table class="w-full border border-gray-300 text-sm">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border p-2">Location</th>
                        <th class="border p-2">Hotel</th>
                        <th class="border p-2">Room</th>
                        <th class="border p-2">Price/Night</th>
                        <th class="border p-2">Extra Bed</th>
                        <th class="border p-2">Action</th>
                    </tr>
                </thead>
                <tbody id="hotelsBody">
                    <tr class="hotel-row">
                        <td class="border p-2"><input name="hotel_location[]" class="w-full border rounded p-1"
                                readonly></td>
                        <td class="border p-2">
                            <select name="hotel_name[]" class="hotel-select w-full border rounded p-1">
                                <option value="">--Select--</option>
                                @forelse($hotels as $hotel)
                                    <option value="{{ $hotel->id }}" data-city="{{ $hotel->city_title }}">
                                        {{ $hotel->title }}</option>
                                @empty
                                    <option value="">No Hotels Available</option>
                                @endforelse
                            </select>
                        </td>
                        <td class="border p-2"><input name="hotel_room[]" class="w-full border rounded p-1"></td>
                        <td class="border p-2"><input name="hotel_price[]" class="w-full border rounded p-1"></td>
                        <td class="border p-2"><input name="hotel_extra[]" class="w-full border rounded p-1"></td>
                        <td class="border p-2 text-center">
                            <button type="button" id="addHotelRow"
                                class="bg-primary text-white px-2 py-1 rounded text-xs rounded"> + </button>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

    <input type="hidden" name="price_format" id="price_format">
    <input type="hidden" name="format_data" id="format_data" value='@json($formatData ?? [])'>

</div>

<div class="md:col-span-1 mt-4">
    <label for="air_ticket_details">Air Ticket Details</label>

    <!-- Hidden textarea for form submission -->
    <textarea name="air_ticket_details" id="air_ticket_details" hidden>{{ old('air_ticket_details', $pkgPrice->air_ticket_details ?? '') }}</textarea>

    <!-- Quill editor container -->
    <div class="quill-editor border rounded p-2" data-target-textarea="air_ticket_details"
        style="min-height: 200px;">
        {!! old('air_ticket_details', $pkgPrice->air_ticket_details ?? '') !!}
    </div>
    @error('air_ticket_details')
        <p class="text-red-500 text-sm">{{ $message }}</p>
    @enderror
</div>

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ---------- Quill Editor ----------
            document.querySelectorAll('.quill-editor').forEach(editorDiv => {
                const targetTextareaId = editorDiv.dataset.targetTextarea;
                const hiddenTextarea = document.getElementById(targetTextareaId);

                const quill = new Quill(editorDiv, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            [{'font': []}],
                            [{'align': []}],
                            [{'list': 'ordered'}, {'list': 'bullet'}]
                        ]
                    }
                });

                if (hiddenTextarea.value) {
                    quill.root.innerHTML = hiddenTextarea.value;
                }

                const form = editorDiv.closest('form');
                if (form) {
                    form.addEventListener('submit', () => {
                        hiddenTextarea.value = quill.root.innerHTML;
                    });
                }
            });

            // ---------- Elements ----------
            const formatSelect = document.getElementById("formatSelect");
            const format1 = document.getElementById("formatOneTable");
            const format2 = document.getElementById("formatTwoTable");
            const format3 = document.getElementById("formatThreeTable");
            const priceFormatInput = document.getElementById("price_format");
            const formatDataInput = document.getElementById("format_data");

            function hideAll() {
                [format1, format2, format3].forEach(el => el.classList.add("hidden"));
            }

            function disableAllInputs() {
                document.querySelectorAll(
                    '#formatOneTable input, #formatTwoTable input, #formatThreeTable input, #formatOneTable select, #formatTwoTable select, #formatThreeTable select'
                ).forEach(el => el.disabled = true);
            }

            function showFormat(format) {
                hideAll();
                disableAllInputs();
                priceFormatInput.value = format;

                if (format === "format1") {
                    format1.classList.remove("hidden");
                    format1.querySelectorAll("input, select").forEach(el => el.disabled = false);
                } else if (format === "format2") {
                    format2.classList.remove("hidden");
                    format2.querySelectorAll("input, select").forEach(el => el.disabled = false);
                } else if (format === "format3") {
                    format3.classList.remove("hidden");
                    format3.querySelectorAll("input, select").forEach(el => el.disabled = false);
                }
            }

            formatSelect.addEventListener("change", () => {
                showFormat(formatSelect.value);
            });

            // ---------- Prefill formats from formatData ----------
            const formatData = JSON.parse(formatDataInput.value || '{}');

            // Format 1 Prefill
            if (formatData.format1 && formatData.format1.length > 0) {
                showFormat("format1");
                const wrapper = document.getElementById("formatOneWrapper");
                wrapper.innerHTML = "";
                formatData.format1.forEach((item, boxIndex) => {
                    const box = document.createElement("div");
                    box.className = "format1-box mb-6 p-4 border rounded-lg bg-gray-50";

                    // Hotel selection HTML build
                    let hotelSelectionHTML = '';

                    @foreach ($cities as $city)
                        {
                            const cityId = {{ $city['id'] }};
                            const selectedHotelId = item.hotels ? item.hotels['city_'+cityId] : '';

                            hotelSelectionHTML += `
                                <div class="mb-2">
                                    <strong>{{ $city['title'] }}</strong><br>
                                    @foreach (collect($hotels)->where('city_id', $city['id']) as $hotel)
                                        <label class="inline-flex items-center mr-2">
                                            <input type="radio"
                                                name="format1_box_${boxIndex}_city_${cityId}"
                                                value="{{ $hotel->id }}"
                                                ${selectedHotelId == {{$hotel->id}} ? 'checked' : ''}>
                                            <span class="ml-1">{{ $hotel->title }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            `;
                        }
                    @endforeach

                    box.innerHTML = `
                        <table class="w-full border border-gray-300 text-sm format1-table">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th colspan="5">
                                        ${hotelSelectionHTML}
                                    </th>
                                </tr>
                                <tr>
                                    <th class="border p-2 w-1/4">Title</th>
                                    <th colspan="3" class="border p-2">
                                        <input type="text" name="format1_title[]" class="w-full border rounded p-1" value="${item.title || ''}">
                                    </th>
                                </tr>
                                <tr>
                                    <th class="border p-2">Particulars</th>
                                    <th class="border p-2">Twin/Double</th>
                                    <th class="border p-2">Triple</th>
                                    <th class="border p-2">Single</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border p-2">Land Package</td>
                                    <td class="border p-2"><input type="text" name="land_double[]" value="${item.land_double || ''}" class="w-full border rounded p-1"></td>
                                    <td class="border p-2"><input type="text" name="land_triple[]" value="${item.land_triple || ''}" class="w-full border rounded p-1"></td>
                                    <td class="border p-2"><input type="text" name="land_single[]" value="${item.land_single || ''}" class="w-full border rounded p-1"></td>
                                </tr>
                                <tr>
                                    <td class="border p-2">Air Ticket</td>
                                    <td colspan="3" class="border p-2"><input type="text" name="ticket_fare[]" value="${item.ticket_fare || ''}" class="w-full border rounded p-1"></td>
                                </tr>
                                <tr>
                                    <td class="border p-2">Visa</td>
                                    <td colspan="3" class="border p-2"><input type="text" name="visa[]" value="${item.visa || ''}" class="w-full border rounded p-1"></td>
                                </tr>
                                <tr class="bg-gray-100 font-semibold">
                                    <td class="border p-2">Total</td>
                                    <td class="border p-2"><input type="text" name="total_double[]" value="${item.total_double || ''}" class="w-full border rounded p-1"></td>
                                    <td class="border p-2"><input type="text" name="total_triple[]" value="${item.total_triple || ''}" class="w-full border rounded p-1"></td>
                                    <td class="border p-2"><input type="text" name="total_single[]" value="${item.total_single || ''}" class="w-full border rounded p-1"></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="remove-block mt-4 bg-red-500 text-white px-2 py-1 rounded text-xs">Remove</button>
                    `;

                    // Remove button functionality
                    box.querySelector('.remove-block').addEventListener('click', () => box.remove());
                    wrapper.appendChild(box);
                });
            }

            // Format 2 Prefill
            if (formatData.format2 && formatData.format2.length > 0) {
                showFormat("format2");
                const wrapper = document.getElementById("formatTwoWrapper");
                wrapper.innerHTML = "";
                formatData.format2.forEach((item, boxIndex) => {
                    const box = document.createElement("div");
                    box.className = "format2-box mb-6 p-4 border rounded-lg bg-gray-50";

                    // Hotel selection HTML build
                    let hotelSelectionHTML = '';

                    @foreach ($cities as $city)
                        {
                            const cityId = {{ $city['id'] }};
                            const selectedHotelId = item.hotels ? item.hotels['city_'+cityId] : '';

                            hotelSelectionHTML += `
                                <div class="mb-2">
                                    <strong>{{ $city['title'] }}</strong><br>
                                    @foreach (collect($hotels)->where('city_id', $city['id']) as $hotel)
                                        <label class="inline-flex items-center mr-2">
                                            <input type="radio"
                                                name="format2_box_${boxIndex}_city_${cityId}"
                                                value="{{ $hotel->id }}"
                                                ${selectedHotelId == {{$hotel->id}} ? 'checked' : ''}>
                                            <span class="ml-1">{{ $hotel->title }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            `;
                        }
                    @endforeach

                    box.innerHTML = `
                        <table class="w-full border border-gray-300 text-sm format2-table">
                            <thead class="bg-gray-200">
                                <tr>
                                    <th colspan="5">
                                        ${hotelSelectionHTML}
                                    </th>
                                </tr>
                                <tr>
                                    <th class="border p-2 w-1/4">Title</th>
                                    <th colspan="4" class="border p-2">
                                        <input type="text" name="format2_title[]" class="w-full border rounded p-1" value="${item.title || ''}">
                                    </th>
                                </tr>
                                <tr>
                                    <th class="border p-2">Particulars</th>
                                    <th class="border p-2">Adult</th>
                                    <th class="border p-2">Child With Bed</th>
                                    <th class="border p-2">Child No Bed</th>
                                    <th class="border p-2">Infant</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="border p-2">Land Package</td>
                                    <td class="border p-2"><input type="text" name="f2_adult[]" value="${item.land?.adult || ''}" class="w-full border rounded p-1"></td>
                                    <td class="border p-2"><input type="text" name="f2_child_bed[]" value="${item.land?.child_bed || ''}" class="w-full border rounded p-1"></td>
                                    <td class="border p-2"><input type="text" name="f2_child_no_bed[]" value="${item.land?.child_no_bed || ''}" class="w-full border rounded p-1"></td>
                                    <td class="border p-2"><input type="text" name="f2_infant[]" value="${item.land?.infant || ''}" class="w-full border rounded p-1"></td>
                                </tr>
                                <tr>
                                    <td class="border p-2">Air Ticket</td>
                                    <td class="border p-2"><input type="text" name="f2_air_adult[]" value="${item.air_ticket?.adult || ''}" class="w-full border rounded p-1"></td>
                                    <td colspan="2" class="border p-2"><input type="text" name="f2_air_child[]" value="${item.air_ticket?.child || ''}" class="w-full border rounded p-1"></td>
                                    <td class="border p-2"><input type="text" name="f2_air_infant[]" value="${item.air_ticket?.infant || ''}" class="w-full border rounded p-1"></td>
                                </tr>
                                <tr>
                                    <td class="border p-2">Visa</td>
                                    <td class="border p-2"><input type="text" name="f2_visa_adult[]" value="${item.visa?.adult || ''}" class="w-full border rounded p-1"></td>
                                    <td colspan="2" class="border p-2"><input type="text" name="f2_visa_child[]" value="${item.visa?.child || ''}" class="w-full border rounded p-1"></td>
                                    <td class="border p-2"><input type="text" name="f2_visa_infant[]" value="${item.visa?.infant || ''}" class="w-full border rounded p-1"></td>
                                </tr>
                                <tr class="bg-gray-100 font-semibold">
                                    <td class="border p-2">Total</td>
                                    <td class="border p-2"><input type="text" name="f2_total_adult[]" value="${item.total?.adult || ''}" class="w-full border rounded p-1"></td>
                                    <td class="border p-2"><input type="text" name="f2_total_child_bed[]" value="${item.total?.child_bed || ''}" class="w-full border rounded p-1"></td>
                                    <td class="border p-2"><input type="text" name="f2_total_child_no_bed[]" value="${item.total?.child_no_bed || ''}" class="w-full border rounded p-1"></td>
                                    <td class="border p-2"><input type="text" name="f2_total_infant[]" value="${item.total?.infant || ''}" class="w-full border rounded p-1"></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="remove-block mt-4 bg-red-500 text-white px-2 py-1 rounded text-xs">Remove</button>
                    `;

                    // Remove button functionality
                    box.querySelector('.remove-block').addEventListener('click', () => box.remove());
                    wrapper.appendChild(box);
                });
            }

            // Format 3 Prefill
            if (formatData.format3) {
                showFormat("format3");
                // Prefill Activities
                const activitiesBody = document.getElementById("activitiesBody");
                activitiesBody.innerHTML = "";
                if (formatData.format3.activities) {
                    formatData.format3.activities.forEach(act => {
                        const tr = document.createElement("tr");
                        tr.className = "activity-row";
                        tr.innerHTML = `
                            <td class="border p-2"><input name="act_name[]" class="w-full border rounded p-1" value="${act.name || ''}"></td>
                            <td class="border p-2"><input name="act_adult[]" class="w-full border rounded p-1" value="${act.adult || ''}"></td>
                            <td class="border p-2"><input name="act_child[]" class="w-full border rounded p-1" value="${act.child || ''}"></td>
                            <td class="border p-2"><input name="act_infant[]" class="w-full border rounded p-1" value="${act.infant || ''}"></td>
                            <td class="border p-2 text-center"><button type="button" onclick="this.closest('tr').remove()" class="bg-red-500 text-white px-2 py-1 rounded text-xs">x</button></td>
                        `;
                        activitiesBody.appendChild(tr);
                    });
                }

                // Prefill Hotels
                const hotelsBody = document.getElementById("hotelsBody");
                hotelsBody.innerHTML = "";
                if (formatData.format3.hotels) {
                    formatData.format3.hotels.forEach(hotel => {
                        const tr = document.createElement("tr");
                        tr.className = "hotel-row";
                        tr.innerHTML = `
                            <td class="border p-2"><input name="hotel_location[]" class="w-full border rounded p-1" value="${hotel.location || ''}" readonly></td>
                            <td class="border p-2">
                                <select name="hotel_name[]" class="hotel-select w-full border rounded p-1">
                                    <option value="${hotel.name || ''}">${hotel.name || '--Select--'}</option>
                                </select>
                            </td>
                            <td class="border p-2"><input name="hotel_room[]" class="w-full border rounded p-1" value="${hotel.room || ''}"></td>
                            <td class="border p-2"><input name="hotel_price[]" class="w-full border rounded p-1" value="${hotel.price || ''}"></td>
                            <td class="border p-2"><input name="hotel_extra[]" class="w-full border rounded p-1" value="${hotel.extra_bed || ''}"></td>
                            <td class="border p-2 text-center"><button type="button" onclick="this.closest('tr').remove()" class="bg-red-500 text-white px-2 py-1 rounded text-xs">x</button></td>
                        `;
                        hotelsBody.appendChild(tr);
                    });
                }
            }

            // ---------- Collect price data before form submit ----------
            const submitButton = document.querySelector('button[type="submit"]');
            if (submitButton) {
                submitButton.addEventListener('click', function(e) {
                    const selectedFormat = formatSelect.value;
                    const data = {};

                    if (selectedFormat === 'format1') {
                        data.format1 = Array.from(document.querySelectorAll('#formatOneWrapper .format1-box'))
                            .map((box, boxIndex) => {
                                const hotelSelections = {};

                                // Collect all selected hotels for this box
                                const allRadios = box.querySelectorAll('input[type="radio"]');

                                allRadios.forEach(radio => {
                                    if (radio.checked) {
                                        const name = radio.name;
                                        if (name.includes('city_')) {
                                            const match = name.match(/city_(\d+)/);
                                            if (match && match[1]) {
                                                hotelSelections[match[1]] = radio.value;
                                            }
                                        }
                                    }
                                });

                                return {
                                    title: box.querySelector('input[name="format1_title[]"]')?.value || '',
                                    land_double: box.querySelector('input[name="land_double[]"]')?.value || '',
                                    land_triple: box.querySelector('input[name="land_triple[]"]')?.value || '',
                                    land_single: box.querySelector('input[name="land_single[]"]')?.value || '',
                                    ticket_fare: box.querySelector('input[name="ticket_fare[]"]')?.value || '',
                                    visa: box.querySelector('input[name="visa[]"]')?.value || '',
                                    total_double: box.querySelector('input[name="total_double[]"]')?.value || '',
                                    total_triple: box.querySelector('input[name="total_triple[]"]')?.value || '',
                                    total_single: box.querySelector('input[name="total_single[]"]')?.value || '',
                                    hotels: hotelSelections
                                };
                            });
                    }

                    if (selectedFormat === 'format2') {
                        data.format2 = Array.from(document.querySelectorAll('#formatTwoWrapper .format2-box'))
                            .map((box, boxIndex) => {
                                const hotelSelections = {};

                                // Collect all selected hotels for this box
                                const allRadios = box.querySelectorAll('input[type="radio"]');

                                allRadios.forEach(radio => {
                                    if (radio.checked) {
                                        const name = radio.name;
                                        if (name.includes('city_')) {
                                            const match = name.match(/city_(\d+)/);
                                            if (match && match[1]) {
                                                hotelSelections[match[1]] = radio.value;
                                            }
                                        }
                                    }
                                });

                                return {
                                    title: box.querySelector('input[name="format2_title[]"]')?.value || '',
                                    land: {
                                        adult: box.querySelector('input[name="f2_adult[]"]')?.value || '',
                                        child_bed: box.querySelector('input[name="f2_child_bed[]"]')?.value || '',
                                        child_no_bed: box.querySelector('input[name="f2_child_no_bed[]"]')?.value || '',
                                        infant: box.querySelector('input[name="f2_infant[]"]')?.value || '',
                                    },
                                    air_ticket: {
                                        adult: box.querySelector('input[name="f2_air_adult[]"]')?.value || '',
                                        child: box.querySelector('input[name="f2_air_child[]"]')?.value || '',
                                        infant: box.querySelector('input[name="f2_air_infant[]"]')?.value || '',
                                    },
                                    visa: {
                                        adult: box.querySelector('input[name="f2_visa_adult[]"]')?.value || '',
                                        child: box.querySelector('input[name="f2_visa_child[]"]')?.value || '',
                                        infant: box.querySelector('input[name="f2_visa_infant[]"]')?.value || '',
                                    },
                                    total: {
                                        adult: box.querySelector('input[name="f2_total_adult[]"]')?.value || '',
                                        child_bed: box.querySelector('input[name="f2_total_child_bed[]"]')?.value || '',
                                        child_no_bed: box.querySelector('input[name="f2_total_child_no_bed[]"]')?.value || '',
                                        infant: box.querySelector('input[name="f2_total_infant[]"]')?.value || '',
                                    },
                                    hotels: hotelSelections
                                };
                            });
                    }

                    if (selectedFormat === 'format3') {
                        data.format3 = {
                            activities: Array.from(document.querySelectorAll('#activitiesBody .activity-row'))
                                .map(row => ({
                                    name: row.querySelector('input[name="act_name[]"]')?.value || '',
                                    adult: row.querySelector('input[name="act_adult[]"]')?.value || '',
                                    child: row.querySelector('input[name="act_child[]"]')?.value || '',
                                    infant: row.querySelector('input[name="act_infant[]"]')?.value || '',
                                })),
                            hotels: Array.from(document.querySelectorAll('#hotelsBody .hotel-row'))
                                .map(row => ({
                                    location: row.querySelector('input[name="hotel_location[]"]')?.value || '',
                                    name: row.querySelector('select[name="hotel_name[]"]')?.value || '',
                                    room: row.querySelector('input[name="hotel_room[]"]')?.value || '',
                                    price: row.querySelector('input[name="hotel_price[]"]')?.value || '',
                                    extra_bed: row.querySelector('input[name="hotel_extra[]"]')?.value || '',
                                }))
                        };
                    }

                    formatDataInput.value = JSON.stringify(data);
                });
            }

            // ---------- Format 1 ----------
            const addFormat1Btn = document.getElementById('addFormat1Block');
            const format1Wrapper = document.getElementById('formatOneWrapper');

            function createFormat1Block(data = {}, boxIndex) {
                const box = document.createElement('div');
                box.className = "format1-box mb-6 p-4 border rounded-lg bg-gray-50 relative";

                // Hotel selection HTML build
                let hotelSelectionHTML = '';

                @foreach ($cities as $city)
                    {
                        const cityId = {{ $city['id'] }};
                        const selectedHotelId = data.hotels && data.hotels[cityId] ? data.hotels[cityId] : '';

                        hotelSelectionHTML += `
                            <div class="mb-2">
                                <strong>{{ $city['title'] }}</strong><br>
                                @foreach (collect($hotels)->where('city_id', $city['id']) as $hotel)
                                    <label class="inline-flex items-center mr-2">
                                        <input type="radio"
                                            name="format1_box_${boxIndex}_city_${cityId}"
                                            value="{{ $hotel->id }}"
                                            ${selectedHotelId == {{$hotel->id}} ? 'checked' : ''}>
                                        <span class="ml-1">{{ $hotel->title }}</span>
                                    </label>
                                @endforeach
                            </div>
                        `;
                    }
                @endforeach

                box.innerHTML = `
                    <table class="w-full border border-gray-300 text-sm format1-table">
                        <thead class="bg-gray-200">
                            <tr>
                                <th colspan="5">
                                    ${hotelSelectionHTML}
                                </th>
                            </tr>
                            <tr>
                                <th class="border p-2 w-1/4">Title</th>
                                <th colspan="3" class="border p-2">
                                    <input type="text" name="format1_title[]" class="w-full border rounded p-1" value="${data.title || ''}">
                                </th>
                            </tr>
                            <tr>
                                <th class="border p-2">Particulars</th>
                                <th class="border p-2">Twin/Double</th>
                                <th class="border p-2">Triple</th>
                                <th class="border p-2">Single</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border p-2">Land Package</td>
                                <td class="border p-2"><input type="text" name="land_double[]" value="${data.land_double || ''}" class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="land_triple[]" value="${data.land_triple || ''}" class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="land_single[]" value="${data.land_single || ''}" class="w-full border rounded p-1"></td>
                            </tr>
                            <tr>
                                <td class="border p-2">Air Ticket</td>
                                <td colspan="3" class="border p-2"><input type="text" name="ticket_fare[]" value="${data.ticket_fare || ''}" class="w-full border rounded p-1"></td>
                            </tr>
                            <tr>
                                <td class="border p-2">Visa</td>
                                <td colspan="3" class="border p-2"><input type="text" name="visa[]" value="${data.visa || ''}" class="w-full border rounded p-1"></td>
                            </tr>
                            <tr class="bg-gray-100 font-semibold">
                                <td class="border p-2">Total</td>
                                <td class="border p-2"><input type="text" name="total_double[]" value="${data.total_double || ''}" class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="total_triple[]" value="${data.total_triple || ''}" class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="total_single[]" value="${data.total_single || ''}" class="w-full border rounded p-1"></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="remove-block mt-4 bg-red-500 text-white px-2 py-1 rounded text-xs">Remove</button>
                `;

                box.querySelector('.remove-block').addEventListener('click', () => box.remove());
                return box;
            }

            let format1Index = formatData.format1 ? formatData.format1.length : 0;

            if (addFormat1Btn) {
                addFormat1Btn.addEventListener('click', () => {
                    format1Index++;
                    format1Wrapper.appendChild(createFormat1Block({}, format1Index));
                });
            }

            // ---------- Format 2 ----------
            const addFormat2Btn = document.getElementById('addFormat2Block');
            const format2Wrapper = document.getElementById('formatTwoWrapper');

            function createFormat2Block(data = {}, boxIndex) {
                const box = document.createElement('div');
                box.className = "format2-box mb-6 p-4 border rounded-lg bg-gray-50 relative";

                // Hotel selection HTML build
                let hotelSelectionHTML = '';

                @foreach ($cities as $city)
                    {
                        const cityId = {{ $city['id'] }};
                        const selectedHotelId = data.hotels && data.hotels[cityId] ? data.hotels[cityId] : '';

                        hotelSelectionHTML += `
                            <div class="mb-2">
                                <strong>{{ $city['title'] }}</strong><br>
                                @foreach (collect($hotels)->where('city_id', $city['id']) as $hotel)
                                    <label class="inline-flex items-center mr-2">
                                        <input type="radio"
                                            name="format2_box_${boxIndex}_city_${cityId}"
                                            value="{{ $hotel->id }}"
                                            ${selectedHotelId == {{$hotel->id}} ? 'checked' : ''}>
                                        <span class="ml-1">{{ $hotel->title }}</span>
                                    </label>
                                @endforeach
                            </div>
                        `;
                    }
                @endforeach

                box.innerHTML = `
                    <table class="w-full border border-gray-300 text-sm format2-table">
                        <thead class="bg-gray-200">
                            <tr>
                                <th colspan="5">
                                    ${hotelSelectionHTML}
                                </th>
                            </tr>
                            <tr>
                                <th class="border p-2 w-1/4">Title</th>
                                <th colspan="4" class="border p-2">
                                    <input type="text" name="format2_title[]" class="w-full border rounded p-1" value="${data.title || ''}">
                                </th>
                            </tr>
                            <tr>
                                <th class="border p-2">Particulars</th>
                                <th class="border p-2">Adult</th>
                                <th class="border p-2">Child With Bed</th>
                                <th class="border p-2">Child No Bed</th>
                                <th class="border p-2">Infant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border p-2">Land Package</td>
                                <td class="border p-2"><input type="text" name="f2_adult[]" value="${data.land?.adult || ''}" class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="f2_child_bed[]" value="${data.land?.child_bed || ''}" class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="f2_child_no_bed[]" value="${data.land?.child_no_bed || ''}" class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="f2_infant[]" value="${data.land?.infant || ''}" class="w-full border rounded p-1"></td>
                            </tr>
                            <tr>
                                <td class="border p-2">Air Ticket</td>
                                <td class="border p-2"><input type="text" name="f2_air_adult[]" value="${data.air_ticket?.adult || ''}" class="w-full border rounded p-1"></td>
                                <td class="border p-2" colspan="2"><input type="text" name="f2_air_child[]" value="${data.air_ticket?.child || ''}" class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="f2_air_infant[]" value="${data.air_ticket?.infant || ''}" class="w-full border rounded p-1"></td>
                            </tr>
                            <tr>
                                <td class="border p-2">Visa</td>
                                <td class="border p-2"><input type="text" name="f2_visa_adult[]" value="${data.visa?.adult || ''}" class="w-full border rounded p-1"></td>
                                <td class="border p-2" colspan="2"><input type="text" name="f2_visa_child[]" value="${data.visa?.child || ''}" class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="f2_visa_infant[]" value="${data.visa?.infant || ''}" class="w-full border rounded p-1"></td>
                            </tr>
                            <tr class="bg-gray-100 font-semibold">
                                <td class="border p-2">Total</td>
                                <td class="border p-2"><input type="text" name="f2_total_adult[]" value="${data.total?.adult || ''}" class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="f2_total_child_bed[]" value="${data.total?.child_bed || ''}" class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="f2_total_child_no_bed[]" value="${data.total?.child_no_bed || ''}" class="w-full border rounded p-1"></td>
                                <td class="border p-2"><input type="text" name="f2_total_infant[]" value="${data.total?.infant || ''}" class="w-full border rounded p-1"></td>
                            </tr>
                        </tbody>
                    </table>
                    <button type="button" class="remove-block mt-4 bg-red-500 text-white px-2 py-1 rounded text-xs">Remove</button>
                `;

                box.querySelector('.remove-block').addEventListener('click', () => box.remove());
                return box;
            }

            let format2Index = formatData.format2 ? formatData.format2.length : 0;

            if (addFormat2Btn) {
                addFormat2Btn.addEventListener('click', () => {
                    format2Index++;
                    format2Wrapper.appendChild(createFormat2Block({}, format2Index));
                });
            }

            // ---------- Activities ----------
            const addActivityBtn = document.getElementById('addActivityRow');
            const activitiesBody = document.getElementById('activitiesBody');

            if (addActivityBtn) {
                addActivityBtn.addEventListener('click', () => {
                    const tr = document.createElement('tr');
                    tr.className = 'activity-row';
                    tr.innerHTML = `
                        <td class="border p-2"><input name="act_name[]" class="w-full border rounded p-1"></td>
                        <td class="border p-2"><input name="act_adult[]" class="w-full border rounded p-1"></td>
                        <td class="border p-2"><input name="act_child[]" class="w-full border rounded p-1"></td>
                        <td class="border p-2"><input name="act_infant[]" class="w-full border rounded p-1"></td>
                        <td class="border p-2 text-center"><button type="button" class="remove-row bg-red-500 text-white px-2 py-1 rounded text-xs">x</button></td>
                    `;
                    tr.querySelector('.remove-row').addEventListener('click', () => tr.remove());
                    activitiesBody.appendChild(tr);
                });
            }

            // ---------- Hotels ----------
            const addHotelBtn = document.getElementById('addHotelRow');
            const hotelsBody = document.getElementById('hotelsBody');

            if (addHotelBtn) {
                addHotelBtn.addEventListener('click', () => {
                    const tr = document.createElement('tr');
                    tr.className = 'hotel-row';
                    tr.innerHTML = `
                        <td class="border p-2"><input name="hotel_location[]" class="w-full border rounded p-1" readonly></td>
                        <td class="border p-2">
                            <select name="hotel_name[]" class="hotel-select w-full border rounded p-1">
                                <option value="">--Select--</option>
                            </select>
                        </td>
                        <td class="border p-2"><input name="hotel_room[]" class="w-full border rounded p-1"></td>
                        <td class="border p-2"><input name="hotel_price[]" class="w-full border rounded p-1"></td>
                        <td class="border p-2"><input name="hotel_extra[]" class="w-full border rounded p-1"></td>
                        <td class="border p-2 text-center"><button type="button" class="remove-row bg-red-500 text-white px-2 py-1 rounded text-xs">x</button></td>
                    `;
                    tr.querySelector('.remove-row').addEventListener('click', () => tr.remove());
                    hotelsBody.appendChild(tr);
                });
            }
        });
    </script>
@endpush
