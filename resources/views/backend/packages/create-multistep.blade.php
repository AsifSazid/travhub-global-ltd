<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ __('Create Package (Step-by-step)') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto py-6">

        @if (session('success'))
            <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('packages.step', $step) }}" method="POST">
            @csrf
            <input type="hidden" name="uuid" value="{{ $uuid }}">

            {{-- Step 1: Basic Info --}}
            @if ($step == 1)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium">Package Title <span
                                class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $package->title ?? '') }}"
                            class="mt-1 w-full border rounded px-3 py-2">
                        @error('title')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Status</label>
                        <select name="status" class="mt-1 w-full border rounded px-3 py-2">
                            <option value="active"
                                {{ old('status', $package->status ?? '') == 'active' ? 'selected' : '' }}>Active
                            </option>
                            <option value="inactive"
                                {{ old('status', $package->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactive
                            </option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Description</label>
                        <textarea name="description" class="mt-1 w-full border rounded px-3 py-2">{{ old('description', $package->description ?? '') }}</textarea>
                        @error('description')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endif

            {{-- Step 2: Destination --}}
            @if ($step == 2)
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
                        <label class="block text-sm font-medium">Activity Category <span
                                class="text-red-500">*</span></label>
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
                    <input type="hidden" name="cities" id="cities-input"
                        value="{{ old('cities', $package->cities ?? '[]') }}">
                </div>
            @endif

            {{-- Step 3: Quotation --}}
            @if ($step == 3)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label>Duration (days)</label>
                        <input type="number" name="duration" value="{{ old('duration', $package->duration ?? '') }}"
                            class="mt-1 w-full border rounded px-3 py-2">
                        @error('duration')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label>Number of Pax</label>
                        <input type="text" name="no_of_pax"
                            value="{{ old('no_of_pax', $package->no_of_pax ?? '') }}"
                            class="mt-1 w-full border rounded px-3 py-2">
                        @error('no_of_pax')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label>Start Date</label>
                        <input type="date" name="start_date"
                            value="{{ old('start_date', $package->start_date ?? '') }}"
                            class="mt-1 w-full border rounded px-3 py-2">
                        @error('start_date')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label>End Date</label>
                        <input type="date" name="end_date" value="{{ old('end_date', $package->end_date ?? '') }}"
                            class="mt-1 w-full border rounded px-3 py-2">
                        @error('end_date')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endif

            {{-- Step 4: Accommodation --}}
            @if ($step == 4)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label>Accommodation Cities</label>
                        <input type="text" name="accomo_cities"
                            value="{{ old('accomo_cities', $package->accomo_cities ?? '') }}"
                            class="mt-1 w-full border rounded px-3 py-2">
                        @error('accomo_cities')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label>Hotels</label>
                        <input type="text" name="hotels" value="{{ old('hotels', $package->hotels ?? '') }}"
                            class="mt-1 w-full border rounded px-3 py-2">
                        @error('hotels')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endif

            {{-- Step 5: Pricing --}}
            @if ($step == 5)
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label>Currency</label>
                        <select name="currency_id" class="mt-1 w-full border rounded px-3 py-2">
                            <option value="">Select Currency</option>
                            @foreach ($currencies as $currency)
                                <option value="{{ $currency->id }}"
                                    {{ old('currency_id', $package->currency_id ?? '') == $currency->id ? 'selected' : '' }}>
                                    {{ $currency->title }}</option>
                            @endforeach
                        </select>
                        @error('currency_id')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label>Air Ticket Details</label>
                        <textarea name="air_ticket_details" class="mt-1 w-full border rounded px-3 py-2">{{ old('air_ticket_details', $package->air_ticket_details ?? '') }}</textarea>
                        @error('air_ticket_details')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label>Price Options (one per line, format: option|price)</label>
                        <textarea name="price_options" class="mt-1 w-full border rounded px-3 py-2">{{ old('price_options', $package->price_options ?? '') }}</textarea>
                        @error('price_options')
                            <p class="text-red-500 text-sm">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            @endif

            {{-- Step 6: Itineraries & Inclusions --}}
            @if ($step == 6)
                <div class="grid grid-cols-1 gap-4">
                    <label>Itineraries (JSON array)</label>
                    <textarea name="itenaries" class="mt-1 w-full border rounded px-3 py-2">{{ old('itenaries', $package->itenaries ?? '') }}</textarea>
                    @error('itenaries')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror

                    <label>Inclusions (comma separated)</label>
                    <input type="text" name="inclusions"
                        value="{{ old('inclusions', $package->inclusions ?? '') }}"
                        class="mt-1 w-full border rounded px-3 py-2">
                    @error('inclusions')
                        <p class="text-red-500 text-sm">{{ $message }}</p>
                    @enderror
                </div>
            @endif

            {{-- Step 7: Confirm --}}
            @if ($step == 7)
                <div class="text-center py-10">
                    <h3 class="text-lg font-semibold mb-4">Confirm Package Details</h3>
                    <p>Click "Finish" to complete the package creation.</p>
                </div>
            @endif

            {{-- Navigation --}}
            <div class="mt-6 flex justify-between">
                @if ($step > 1)
                    <a href="{{ route('packages.create', ['uuid' => $uuid, 'step' => $step - 1]) }}"
                        class="px-4 py-2 border rounded hover:bg-gray-100">Back</a>
                @else
                    <span></span>
                @endif

                <button type="submit"
                    class="flex items-center justify-center px-4 py-2 text-sm text-white rounded-md bg-primary border border-gray-300 dark:bg-white dark:border-gray-200 hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary-dark focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                    {{ $step < 7 ? 'Save & Next' : 'Finish' }}
                </button>
            </div>
        </form>
    </div>

    @push('js')
        <script>
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
</x-backend.layouts.master>
