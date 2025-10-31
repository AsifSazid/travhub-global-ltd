<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="md:col-span-2">
        <label for="country" class="block text-sm font-medium">Country</label>
        <select id="country" name="country_id" class="mt-1 w-full border rounded px-3 py-2">
            <option value="">Select country</option>
            @foreach ($countries as $country)
                <option value="{{ $country->id }}"
                    {{ old('country_id', $package->country_id ?? '') == $country->id ? 'selected' : '' }}>
                    {{ $country->title }}
                </option>
            @endforeach
        </select>
        @error('country_id')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium">Activity Category <span class="text-red-500">*</span></label>
        <select name="activity_id" class="mt-1 w-full border rounded px-3 py-2">
            <option value="">Select Activity</option>
            @foreach ($activities as $activity)
                <option value="{{ $activity->id }}"
                    {{ old('activity_id', $package->activity_id ?? '') == $activity->id ? 'selected' : '' }}>
                    {{ $activity->title }}</option>
            @endforeach
        </select>
        @error('activity_category_id')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <div class="md:col-span-2 mt-4">
        <label class="block text-sm font-medium">Cities</label>
        <div id="cities-container" class="flex flex-wrap gap-2 border rounded p-2 min-h-[50px]">
            <!-- Cities checkboxes will be appended here -->
        </div>
        @error('cities')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <!-- Hidden field to store JSON -->
    <input type="hidden" name="cities" id="cities-input" value="{{ old('cities', $package->cities ?? '[]') }}">
</div>

@push('js')
    <script>
        // Package Destination Information
        document.addEventListener('DOMContentLoaded', function() {
            const countrySelect = document.getElementById('country');
            const citiesContainer = document.getElementById('cities-container');
            const citiesInput = document.getElementById('cities-input');

            // Parse old value
            let selectedCities = [];
            try {
                selectedCities = JSON.parse(citiesInput.value);
            } catch (e) {}

            // Load cities for a given country
            async function loadCities(countryId) {
                citiesContainer.innerHTML = '';
                if (!countryId) return;

                try {
                    const res = await fetch(`/api/countries/${countryId}/cities`);
                    const data = await res.json();


                    // data assumed as {id: title, ...}
                    for (const city of data) {
                        const checkbox = document.createElement('input');
                        checkbox.type = 'checkbox';
                        checkbox.value = city.id;
                        checkbox.className = 'accent-indigo-600';
                        checkbox.id = `city-${city.id}`;
                        if (selectedCities.includes(String(city.id))) checkbox.checked = true;

                        const label = document.createElement('label');
                        label.className = 'flex items-center space-x-2 border px-3 py-1 rounded cursor-pointer';
                        label.appendChild(checkbox);

                        const span = document.createElement('span');
                        span.textContent = city.title;
                        label.appendChild(span);

                        citiesContainer.appendChild(label);

                        checkbox.addEventListener('change', function() {
                            const cityId = this.value;
                            if (this.checked && !selectedCities.includes(cityId)) {
                                selectedCities.push(cityId);
                            } else if (!this.checked) {
                                selectedCities = selectedCities.filter(c => c !== cityId);
                            }
                            citiesInput.value = JSON.stringify(selectedCities);
                        });
                    }


                    // Update hidden field initially
                    citiesInput.value = JSON.stringify(selectedCities);
                } catch (err) {
                    console.error('Failed to load cities', err);
                }
            }

            // Load cities on country change
            countrySelect.addEventListener('change', function() {
                selectedCities = [];
                citiesInput.value = '[]';
                loadCities(this.value);
            });

            // Initial load if country already selected
            if (countrySelect.value) loadCities(countrySelect.value);
        });
    </script>
@endpush