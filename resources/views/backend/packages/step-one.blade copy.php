<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    {{-- COUNTRY --}}
    <div class="md:col-span-2">
        <label for="country" class="block text-sm font-medium">
            Country <span class="text-red-500">*</span>
        </label>
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

    {{-- CITIES --}}
    <div class="md:col-span-2">
        <label class="block text-sm font-medium">Cities <span class="text-red-500">*</span></label>
        <div id="cityList"
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 rounded mt-1 py-2 min-h-[45px] border p-2 bg-white">
            <!-- JS renders cities here -->
        </div>
        @error('cities')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    {{-- ACTIVITIES --}}
    <div class="md:col-span-2">
        <label class="block text-sm font-medium">Activities <span class="text-red-500">*</span></label>
        <div id="activityList"
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3 mt-1 py-2 min-h-[45px] border p-2 bg-white rounded">
            <!-- JS renders activities here -->
        </div>
        @error('activities')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    {{-- Hidden JSON Inputs --}}
    <input type="hidden" id="cityInput" name="cities" value='{{ $packDesInfo->cities ?? '[]' }}'>
    <input type="hidden" id="activityInput" name="activities" value='{{ $packDesInfo->activities ?? '[]' }}'>
</div>

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // ==== DOM References ====
            const countrySelect = document.getElementById('country');
            const cityList = document.getElementById('cityList');
            const cityInput = document.getElementById('cityInput');
            const activityList = document.getElementById('activityList');
            const activityInput = document.getElementById('activityInput');

            // ==== Build Country → Cities Mapping ====
            window.COUNTRY_TO_CITIES = {};
            @foreach ($countries as $country)
                window.COUNTRY_TO_CITIES["{{ $country->id }}"] = [
                    @foreach ($country->cities as $city)
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

            // ==== Build Activity Data (Country + City level) ====
            window.ALL_ACTIVITIES = [];
            @foreach ($countries as $country)
                @foreach ($country->activities as $activity)
                    window.ALL_ACTIVITIES.push({
                        id: "{{ $activity->id }}",
                        title: {!! json_encode($activity->title) !!},
                        country_id: "{{ $country->id }}",
                        city_id: null
                    });
                @endforeach
                @foreach ($country->cities as $city)
                    @foreach ($city->activities as $activity)
                        window.ALL_ACTIVITIES.push({
                            id: "{{ $activity->id }}",
                            title: {!! json_encode($activity->title) !!},
                            country_id: "{{ $country->id }}",
                            city_id: "{{ $city->id }}"
                        });
                    @endforeach
                @endforeach
            @endforeach

            // ==== State ====
            let selectedCities = parseJSON(cityInput.value);
            let selectedActivities = parseJSON(activityInput.value);

            function parseJSON(str) {
                try {
                    return JSON.parse(str) || [];
                } catch {
                    return [];
                }
            }

            const isCityChecked = id => selectedCities.some(c => c.id == id);
            const isActivityChecked = title => selectedActivities.some(a => a.title == title);

            // ==== Render City Checkboxes ====
            function renderCities(cities) {
                cityList.innerHTML = '';
                cities.forEach(city => {
                    const wrapper = document.createElement('label');
                    wrapper.className =
                        'flex items-center space-x-2 border px-3 py-2 rounded bg-gray-50 hover:bg-gray-100';

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.value = city.id;
                    checkbox.checked = isCityChecked(city.id);

                    checkbox.addEventListener('change', () => {
                        if (checkbox.checked) {
                            selectedCities.push(city);
                        } else {
                            selectedCities = selectedCities.filter(c => c.id != city.id);
                        }
                        cityInput.value = JSON.stringify(selectedCities);
                        loadActivities();
                    });

                    const label = document.createElement('span');
                    label.className = 'pl-1';
                    label.textContent = city.title;

                    wrapper.appendChild(checkbox);
                    wrapper.appendChild(label);
                    cityList.appendChild(wrapper);
                });
                cityInput.value = JSON.stringify(selectedCities);
            }

            // ==== Load & Render Activities ====
            function loadActivities() {
                const selectedCountryId = countrySelect.value;
                const selectedCityIds = selectedCities.map(c => String(c.id));

                let relevantActivities = [];

                if (selectedCityIds.length > 0) {
                    // ✅ City selected → only city activities
                    relevantActivities = window.ALL_ACTIVITIES.filter(a =>
                        String(a.country_id) === String(selectedCountryId) &&
                        a.city_id && selectedCityIds.includes(String(a.city_id))
                    );
                } else {
                    // ✅ Only country selected → country-level activities
                    relevantActivities = window.ALL_ACTIVITIES.filter(a =>
                        String(a.country_id) === String(selectedCountryId) && a.city_id === null
                    );
                }

                // ✅ Remove duplicates by title
                const uniqueActivities = [];
                const seenTitles = new Set();
                relevantActivities.forEach(a => {
                    if (!seenTitles.has(a.title)) {
                        seenTitles.add(a.title);
                        uniqueActivities.push(a);
                    }
                });

                renderActivities(uniqueActivities);
            }

            // ==== Render Activity Checkboxes ====
            function renderActivities(activities) {
                activityList.innerHTML = '';
                if (activities.length === 0) {
                    activityList.innerHTML = `<p class="text-gray-500">No activities found</p>`;
                    return;
                }

                activities.forEach(activity => {
                    const wrapper = document.createElement('label');
                    wrapper.className =
                        'flex items-center space-x-2 border px-3 py-2 rounded bg-gray-50 hover:bg-gray-100';

                    const checkbox = document.createElement('input');
                    checkbox.type = 'checkbox';
                    checkbox.value = activity.id;
                    checkbox.checked = isActivityChecked(activity.title);

                    checkbox.addEventListener('change', () => {
                        if (checkbox.checked) {
                            if (!selectedActivities.some(a => a.title === activity.title)) {
                                selectedActivities.push(activity);
                            }
                        } else {
                            selectedActivities = selectedActivities.filter(a => a.title != activity
                                .title);
                        }
                        activityInput.value = JSON.stringify(selectedActivities);
                    });

                    const label = document.createElement('span');
                    label.className = 'pl-1';
                    label.textContent = activity.title;

                    wrapper.appendChild(checkbox);
                    wrapper.appendChild(label);
                    activityList.appendChild(wrapper);
                });

                activityInput.value = JSON.stringify(selectedActivities);
            }

            // ==== Country Change ====
            countrySelect.addEventListener('change', () => {
                selectedCities = [];
                selectedActivities = [];
                cityInput.value = '[]';
                activityInput.value = '[]';
                renderCities(window.COUNTRY_TO_CITIES[countrySelect.value] || []);
                loadActivities();
            });

            // ==== Initial Load ====
            if (countrySelect.value) {
                renderCities(window.COUNTRY_TO_CITIES[countrySelect.value] || []);
                loadActivities();
            }
        });
    </script>
@endpush
