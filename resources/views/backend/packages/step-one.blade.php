{{-- Blade snippet: include inside your form --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <label for="country" class="block text-sm font-medium">Country <span class="text-red-500">*</span></label>
        <select id="country" name="country_id" class="mt-1 w-full border rounded px-3 py-2">
            <option value="">Select country</option>
            @foreach ($countries as $country)
                <option value="{{ $country->id }}"
                    {{ old('country_id', $packDesInfo->country_id ?? '') == $country->id ? 'selected' : '' }}>
                    {{ $country->title }}
                </option>
            @endforeach
        </select>
        @error('country_id')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Activity <span class="text-red-500">*</span></label>
        <select name="activity_id" class="mt-1 w-full border rounded px-3 py-2">
            <option value="">Select Activity</option>
            @foreach ($activities as $activity)
                <option value="{{ $activity->id }}"
                    {{ old('activity_id', $packDesInfo->activity_id ?? '') == $activity->id ? 'selected' : '' }}>
                    {{ $activity->title }}</option>
            @endforeach
        </select>
        @error('activity_id')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2 mt-4">
        <label class="block text-sm font-medium">Cities <span class="text-red-500">*</span></label>

        <!-- cities container: grid layout -->
        <div id="cities-container"
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 rounded mt-1 py-2 min-h-[45px] border p-2 bg-white">
            <!-- JS will render cities here -->
        </div>

        @error('cities')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    {{-- Hidden JSON field: put raw DB JSON or [] --}}
    <input type="hidden" id="cities-input" name="cities" value='{{ $packDesInfo->cities ?? '[]' }}'>
</div>

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const countrySelect = document.getElementById('country');
            const citiesContainer = document.getElementById('cities-container');
            const citiesInput = document.getElementById('cities-input');

            // Build JS map for countries + cities (injected from backend)
            window.COUNTRY_CITIES = {};
            @foreach ($countries as $c)
                window.COUNTRY_CITIES["{{ $c->id }}"] = [
                    @foreach ($c->cities as $city)
                        {
                            id: "{{ $city->id }}",
                            title: {!! json_encode($city->title) !!}
                        }
                        @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                ];
            @endforeach

            // ✅ Parse selectedCities from DB (handle both single & double encoded)
            let selectedCities = [];
            try {
                selectedCities = JSON.parse(citiesInput.value);
                if (typeof selectedCities === "string") {
                    selectedCities = JSON.parse(selectedCities);
                }
            } catch (e) {
                selectedCities = [];
            }

            if (!Array.isArray(selectedCities)) selectedCities = [];
            selectedCities = selectedCities.map(c => ({
                id: String(c.id),
                title: c.title
            }));

            function isCitySelected(id) {
                return selectedCities.some(c => String(c.id) === String(id));
            }

            function renderMessage(text, color = 'gray') {
                citiesContainer.innerHTML =
                    `<p class="text-${color}-500 text-sm col-span-3 text-center">${text}</p>`;
            }

            function renderCityList(list) {
                citiesContainer.innerHTML = '';
                if (!Array.isArray(list) || list.length === 0) {
                    renderMessage('No cities found for this country.', 'gray');
                    return;
                }

                list.forEach(city => {
                    const label = document.createElement('label');
                    label.className =
                        'flex items-center space-x-2 border px-3 py-2 rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 shadow-sm transition';

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.value = String(city.id);
                    checkbox.className = 'accent-indigo-600';
                    checkbox.id = `city-${city.id}`;

                    // ✅ Preselect if it exists in selectedCities
                    if (isCitySelected(city.id)) {
                        checkbox.checked = true;
                    }

                    const span = document.createElement('span');
                    span.textContent = city.title;

                    label.appendChild(checkbox);
                    label.appendChild(span);
                    citiesContainer.appendChild(label);

                    checkbox.addEventListener('change', function() {
                        const cid = String(this.value);
                        const ctitle = span.textContent;

                        if (this.checked) {
                            if (!isCitySelected(cid)) selectedCities.push({
                                id: cid,
                                title: ctitle
                            });
                        } else {
                            selectedCities = selectedCities.filter(c => String(c.id) !== cid);
                        }

                        citiesInput.value = JSON.stringify(selectedCities);
                    });
                });

                citiesInput.value = JSON.stringify(selectedCities);
            }

            function loadCitiesForCountry(countryId) {
                if (!countryId) {
                    renderMessage('Select a country to load cities...', 'gray');
                    return;
                }
                const list = window.COUNTRY_CITIES[String(countryId)] || [];
                renderCityList(list);
            }

            countrySelect.addEventListener('change', function() {
                selectedCities = [];
                citiesInput.value = '[]';
                loadCitiesForCountry(this.value);
            });

            // ✅ Initial load (preselect)
            if (countrySelect.value) {
                loadCitiesForCountry(countrySelect.value);
            } else {
                renderMessage('Select a country to load cities...', 'gray');
            }

            console.debug("✅ Parsed selectedCities:", selectedCities);
        });
    </script>
@endpush
