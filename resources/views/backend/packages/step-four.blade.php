<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
        <label>Price Options</label>
        <select id="formatSelect" class="mt-1 w-full border rounded px-3 py-2">
            <option value="">-- Select Format --</option>
            <option value="format1">Format 1 (Twin/Triple/Single)</option>
            <option value="format2">Format 2 (Adult/Child/Infant)</option>
            <option value="format3">Format 3 (Activities + Hotels)</option>
        </select>

    </div>

    <div class="col-span-2">
        <!-- ---------------- FORMAT ONE ---------------- -->
        <div id="formatOneTable" class="hidden pt-6 border-t">

            <h2 class="text-lg font-semibold mb-3">Format One</h2>

            <div id="formatOneWrapper">

                <div class="format1-box mb-6 p-4 border rounded-lg bg-gray-50">

                    <table class="w-full border border-gray-300 text-sm format1-table">
                        <thead class="bg-gray-200">
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

<div class="md:col-span-1">
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
            document.querySelectorAll('.quill-editor').forEach(editorDiv => {
                const targetTextareaId = editorDiv.dataset.targetTextarea;
                const hiddenTextarea = document.getElementById(targetTextareaId);

                // Initialize Quill
                const quill = new Quill(editorDiv, {
                    theme: 'snow',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            [{
                                'font': []
                            }],
                            [{
                                'align': []
                            }],
                            [{
                                'list': 'ordered'
                            }, {
                                'list': 'bullet'
                            }]
                        ]
                    }
                });

                // Set initial content from hidden textarea if available
                if (hiddenTextarea.value) {
                    quill.root.innerHTML = hiddenTextarea.value;
                }

                // On form submit: copy Quill content to hidden textarea
                const form = editorDiv.closest('form');
                if (form) {
                    form.addEventListener('submit', () => {
                        hiddenTextarea.value = quill.root.innerHTML;
                    });
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
            // ---------- Elements ----------
            const formatSelect = document.getElementById("formatSelect");
            const format1 = document.getElementById("formatOneTable");
            const format2 = document.getElementById("formatTwoTable");
            const format3 = document.getElementById("formatThreeTable");
            const priceFormatInput = document.getElementById("price_format");
            const formatDataInput = document.getElementById("format_data"); // ✅ fixed integration

            // ---------- Helper: Hide all formats ----------
            function hideAll() {
                [format1, format2, format3].forEach(el => el.classList.add("hidden"));
            }

            // ---------- Helper: Disable all inputs/selects ----------
            function disableAllInputs() {
                document.querySelectorAll(
                    '#formatOneTable input, #formatTwoTable input, #formatThreeTable input, #formatOneTable select, #formatTwoTable select, #formatThreeTable select'
                ).forEach(el => el.disabled = true);
            }

            // ---------- Handle Format Switch ----------
            formatSelect.addEventListener("change", () => {
                hideAll();
                disableAllInputs();
                priceFormatInput.value = formatSelect.value;

                switch (formatSelect.value) {
                    case "format1":
                        format1.classList.remove("hidden");
                        format1.querySelectorAll("input, select").forEach(el => el.disabled = false);
                        break;
                    case "format2":
                        format2.classList.remove("hidden");
                        format2.querySelectorAll("input, select").forEach(el => el.disabled = false);
                        break;
                    case "format3":
                        format3.classList.remove("hidden");
                        format3.querySelectorAll("input, select").forEach(el => el.disabled = false);
                        break;
                }
            });

            document.addEventListener('change', function(e) {
                if (e.target && e.target.classList.contains('hotel-select')) {
                    console.log(e.target);
                    const selectedOption = e.target.options[e.target.selectedIndex];
                    const cityTitle = selectedOption.getAttribute('data-city') || '';
                    const row = e.target.closest('tr');
                    const locationInput = row.querySelector('input[name="hotel_location[]"]');
                    if (locationInput) locationInput.value = cityTitle;
                }
            });

            // ---------- Helper: Clone block ----------
            function cloneBlock(wrapperId, boxClass, removeBtnClass) {
                const wrapper = document.getElementById(wrapperId);
                const original = wrapper.querySelector(`.${boxClass}`);
                const clone = original.cloneNode(true);

                clone.querySelectorAll("input, select").forEach(el => el.value = "");

                const oldRemove = clone.querySelector(`.${removeBtnClass}`);
                if (oldRemove) oldRemove.remove();

                const removeBtn = document.createElement("button");
                removeBtn.type = "button";
                removeBtn.className = `${removeBtnClass} bg-red-600 text-white px-2 py-1 text-xs rounded mt-2`;
                removeBtn.innerText = "Remove Option";
                removeBtn.onclick = () => clone.remove();

                clone.appendChild(removeBtn);
                wrapper.appendChild(clone);
            }

            // ---------- Format 1 ----------
            document.getElementById("addFormat1Block").onclick = () => {
                cloneBlock("formatOneWrapper", "format1-box", "remove-format1-block");
            };

            // ---------- Format 2 ----------
            document.getElementById("addFormat2Block").onclick = () => {
                cloneBlock("formatTwoWrapper", "format2-box", "remove-format2-block");
            };

            // ---------- Format 3: Add Activity ----------
            document.getElementById("addActivityRow").onclick = () => {
                const tbody = document.getElementById("activitiesBody");
                const row = tbody.querySelector(".activity-row");
                const clone = row.cloneNode(true);

                clone.querySelectorAll("input").forEach(i => i.value = "");
                clone.lastElementChild.innerHTML =
                    `<button type="button" onclick="this.closest('tr').remove()" class="bg-red-500 text-white px-2 py-1 rounded text-xs">x</button>`;

                tbody.appendChild(clone);
            };

            // ---------- Format 3: Add Hotel ----------
            document.getElementById("addHotelRow").onclick = () => {
                const tbody = document.getElementById("hotelsBody");
                const row = tbody.querySelector(".hotel-row");
                const clone = row.cloneNode(true);

                clone.querySelectorAll("input, select").forEach(e => e.value = "");
                clone.lastElementChild.innerHTML =
                    `<button type="button" onclick="this.closest('tr').remove()" class="bg-red-500 text-white px-2 py-1 rounded text-xs">x</button>`;

                tbody.appendChild(clone);
            };

            // ---------- Collect all price data before form submit ----------
            // document.querySelector('form').addEventListener('submit', function(e) {
            const submitButton = document.querySelector('button[type="submit"]');
            submitButton.addEventListener('click', function(e) {
                const selectedFormat = formatSelect.value;
                const data = {};

                // ===== FORMAT 1 =====
                if (selectedFormat === 'format1') {
                    data.format1 = Array.from(document.querySelectorAll('#formatOneWrapper .format1-box'))
                        .map(box => ({
                            title: box.querySelector('input[name="format1_title[]"]').value,
                            land_double: box.querySelector('input[name="land_double[]"]').value,
                            land_triple: box.querySelector('input[name="land_triple[]"]').value,
                            land_single: box.querySelector('input[name="land_single[]"]').value,
                            ticket_fare: box.querySelector('input[name="ticket_fare[]"]').value,
                            visa: box.querySelector('input[name="visa[]"]').value,
                            total_double: box.querySelector('input[name="total_double[]"]').value,
                            total_triple: box.querySelector('input[name="total_triple[]"]').value,
                            total_single: box.querySelector('input[name="total_single[]"]').value,
                        }));
                }

                // ===== FORMAT 2 =====
                if (selectedFormat === 'format2') {
                    data.format2 = Array.from(document.querySelectorAll('#formatTwoWrapper .format2-box'))
                        .map(box => ({
                            title: box.querySelector('input[name="format2_title[]"]').value,
                            land: {
                                adult: box.querySelector('input[name="f2_adult[]"]').value,
                                child_bed: box.querySelector('input[name="f2_child_bed[]"]').value,
                                child_no_bed: box.querySelector('input[name="f2_child_no_bed[]"]')
                                    .value,
                                infant: box.querySelector('input[name="f2_infant[]"]').value,
                            },
                            air_ticket: {
                                adult: box.querySelector('input[name="f2_air_adult[]"]').value,
                                child: box.querySelector('input[name="f2_air_child[]"]').value,
                                infant: box.querySelector('input[name="f2_air_infant[]"]').value,
                            },
                            visa: {
                                adult: box.querySelector('input[name="f2_visa_adult[]"]').value,
                                child: box.querySelector('input[name="f2_visa_child[]"]').value,
                                infant: box.querySelector('input[name="f2_visa_infant[]"]').value,
                            },
                            total: {
                                adult: box.querySelector('input[name="f2_total_adult[]"]').value,
                                child_bed: box.querySelector('input[name="f2_total_child_bed[]"]')
                                    .value,
                                child_no_bed: box.querySelector(
                                    'input[name="f2_total_child_no_bed[]"]').value,
                                infant: box.querySelector('input[name="f2_total_infant[]"]').value,
                            }
                        }));
                }

                // ===== FORMAT 3 =====
                if (selectedFormat === 'format3') {
                    data.format3 = {
                        activities: Array.from(document.querySelectorAll(
                            '#activitiesBody .activity-row')).map(row => ({
                            name: row.querySelector('input[name="act_name[]"]').value,
                            adult: row.querySelector('input[name="act_adult[]"]').value,
                            child: row.querySelector('input[name="act_child[]"]').value,
                            infant: row.querySelector('input[name="act_infant[]"]').value,
                        })),
                        hotels: Array.from(document.querySelectorAll('#hotelsBody .hotel-row')).map(
                            row => ({
                                location: row.querySelector('input[name="hotel_location[]"]')
                                    .value,
                                name: row.querySelector('select[name="hotel_name[]"]').value,
                                room: row.querySelector('input[name="hotel_room[]"]').value,
                                price: row.querySelector('input[name="hotel_price[]"]').value,
                                extra_bed: row.querySelector('input[name="hotel_extra[]"]')
                                    .value,
                            }))
                    };
                }

                // ✅ Convert and assign
                formatDataInput.value = JSON.stringify(data);
                // console.log("Submitting Data:", formatDataInput.value);
            });
        });
    </script>
@endpush
