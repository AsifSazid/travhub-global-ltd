<x-backend.layouts.master> 
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ __('Edit Activity') }}
            </h2>
        </div>
    </x-slot>

    <div class="overflow-x-auto">
        @if ($errors->any())
            <div class="mb-4 text-red-600">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('activities.update', $activity->uuid) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" value="{{ old('title', $activity->title) }}"
                    required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="country_uuid" class="block text-sm font-medium text-gray-700">Country</label>
                <select name="country_uuid" id="country_uuid" required
                    class="mt-1 block w-full px-2 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled>{{ __('Select Any One') }}</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->uuid }}"
                            {{ $activity->country_uuid == $country->uuid ? 'selected' : '' }}>
                            {{ $country->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="city_uuid" class="block text-sm font-medium text-gray-700">City</label>
                <select name="city_uuid" id="city_uuid"
                    class="mt-1 block w-full px-2 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled>{{ __('Select Any One') }}</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city->uuid }}" data-country-uuid="{{ $city->country_uuid }}"
                            {{ $activity->city_uuid == $city->uuid ? 'selected' : '' }}>
                            {{ $city->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="currency_uuid" class="block text-sm font-medium text-gray-700">Price Currency</label>
                <select name="currency_uuid" id="currency_uuid"
                    class="mt-1 block w-full px-2 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="" disabled>{{ __('Select Any One') }}</option>
                    @foreach ($currencies as $currency)
                        <option value="{{ $currency->uuid }}"
                            {{ $activity->currency_uuid == $currency->uuid ? 'selected' : '' }}>
                            {{ $currency->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Details / Description</label>
                <textarea name="description" id="description" rows="4"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">{{ old('description', $activity->description) }}</textarea>
            </div>

            <!-- Prices block -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Prices</label>

                    <div class="mt-2 space-y-2">
                        @php
                            $prices = json_decode($activity->price_json, true) ?? [];
                        @endphp

                        <div class="flex items-center justify-between border rounded px-3 py-2">
                            <span>Adult</span>
                            <input id="adult-price" class="w-28 border rounded px-2 py-1 text-right price-input"
                                value="{{ $prices['adult'] ?? '' }}" placeholder="0">
                        </div>

                        <div class="flex items-center justify-between border rounded px-3 py-2">
                            <span>Child</span>
                            <input id="child-price" class="w-28 border rounded px-2 py-1 text-right price-input"
                                value="{{ $prices['child'] ?? '' }}" placeholder="0">
                        </div>

                        <div class="flex items-center justify-between border rounded px-3 py-2">
                            <span>Infant</span>
                            <input id="infant-price" class="w-28 border rounded px-2 py-1 text-right price-input"
                                value="{{ $prices['infant'] ?? '' }}" placeholder="0">
                        </div>
                    </div>

                    <div class="mt-2 border-t pt-2 text-right text-sm font-medium text-gray-700">
                        Total Price: <span id="total-price">0.00</span>
                    </div>

                    <input type="hidden" id="price_json" name="price_json"
                        value="{{ $activity->price_json ?? '[]' }}">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit"
                    class="flex items-center justify-center px-4 py-2 text-sm text-white rounded-md bg-primary border border-gray-300 hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary-dark">
                    Update Activity
                </button>
            </div>
        </form>

    </div>

    @push('js')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                // Restore working city filter logic
                const countrySelect = document.getElementById('country_uuid');
                const citySelect = document.getElementById('city_uuid');

                countrySelect.addEventListener('change', function() {
                    const selectedCountry = this.value;

                    for (const option of citySelect.options) {
                        if (!option.value) continue;
                        option.hidden = option.dataset.countryUuid !== selectedCountry;
                    }

                    if (citySelect.selectedOptions.length && citySelect.selectedOptions[0].hidden) {
                        citySelect.value = '';
                    }
                });

                // Trigger on page load to filter cities based on current country
                if (countrySelect.value) {
                    countrySelect.dispatchEvent(new Event('change'));
                }

                // Pricing logic
                const priceTypes = ["adult", "child", "infant"];
                const totalEl = document.getElementById("total-price");
                const hiddenJson = document.getElementById("price_json");

                function formatPrice(value) {
                    if (!value) return "";
                    let parts = value.split(".");
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    return parts.join(".");
                }

                function cleanInput(value) {
                    return value.replace(/[^0-9.]/g, "");
                }

                function updateTotal() {
                    let total = 0;
                    const data = {};

                    priceTypes.forEach(type => {
                        const input = document.getElementById(`${type}-price`);
                        let raw = cleanInput(input.value);
                        let val = parseFloat(raw) || 0;
                        input.value = formatPrice(raw);
                        data[type] = val;
                        total += val;
                    });

                    totalEl.textContent = total.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });

                    hiddenJson.value = JSON.stringify(data);
                }

                document.querySelectorAll(".price-input").forEach(input => {
                    input.addEventListener("input", e => {
                        const cursor = input.selectionStart;
                        input.value = formatPrice(cleanInput(input.value));
                        input.setSelectionRange(cursor, cursor);
                        updateTotal();
                    });

                    input.addEventListener("keydown", e => {
                        const allowedKeys = ["Backspace", "Tab", "ArrowLeft", "ArrowRight", "Delete", "Home", "End"];
                        if (allowedKeys.includes(e.key)) return;
                        if ((e.ctrlKey || e.metaKey) && ["a","c","v","x"].includes(e.key.toLowerCase())) return;
                        if (/^[0-9.]$/.test(e.key)) {
                            if (e.key === "." && input.value.includes(".")) e.preventDefault();
                            return;
                        }
                        e.preventDefault();
                    });

                    input.addEventListener("blur", () => {
                        if (input.value.trim() === "") input.value = "0";
                        updateTotal();
                    });
                });

                updateTotal();
            });
        </script>
    @endpush
</x-backend.layouts.master>
