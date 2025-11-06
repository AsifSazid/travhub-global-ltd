<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ __('Create Activity') }}
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

        <form action="{{ route('activities.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                <input type="text" name="title" id="title" required
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="mb-4">
                <label for="country_uuid" class="block text-sm font-medium text-gray-700">Country</label>
                <select name="country_uuid" id="country_uuid"
                    class="mt-1 block w-full px-2 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">

                    <option value="" selected disabled>{{ __('Select Any One') }}</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->uuid }}">{{ $country->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="city_uuid" class="block text-sm font-medium text-gray-700">City</label>
                <select name="city_uuid" id="city_uuid"
                    class="mt-1 block w-full px-2 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="" selected disabled>{{ __('Select Any One') }}</option>
                    @foreach ($cities as $city)
                        <option value="{{ $city->uuid }}" data-country-uuid="{{ $city->country_uuid }}">
                            {{ $city->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="currency_uuid"
                    class="block text-sm font-medium text-gray-700">{{ __('Price Currency') }}</label>
                <select name="currency_uuid" id="currency_uuid"
                    class="mt-1 block w-full px-2 py-2 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">

                    <option value="" selected disabled>{{ __('Select Any One') }}</option>
                    @foreach ($currencies as $currency)
                        <option value="{{ $currency->uuid }}">{{ $currency->title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- <div class="mb-4">
                <label for="city_uuid"
                    class="block text-sm font-medium text-gray-700">{{ __('Details/Description') }}</label>
                <textarea name="description" id="description" rows="4"
                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div> --}}

            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>

                <!-- Hidden textarea for form submission -->
                <textarea name="description" id="description" hidden>{{ old('description', $activity->description ?? '') }}</textarea>

                <!-- Quill editor container -->
                <div class="quill-editor border rounded p-2" data-target-textarea="description"
                    style="min-height: 200px;">
                    {!! old('description', $activity->description ?? '') !!}
                </div>
            </div>


            <!-- Prices block -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Prices</label>

                    <div class="mt-2 space-y-2" id="price-container">
                        <!-- Adult -->
                        <div class="flex items-center justify-between border rounded px-3 py-2">
                            <span>Adult</span>
                            <input id="adult-price" class="w-28 border rounded px-2 py-1 text-right price-input"
                                placeholder="0">
                        </div>

                        <!-- Child -->
                        <div class="flex items-center justify-between border rounded px-3 py-2">
                            <span>Child</span>
                            <input id="child-price" class="w-28 border rounded px-2 py-1 text-right price-input"
                                placeholder="0">
                        </div>

                        <!-- Infant -->
                        <div class="flex items-center justify-between border rounded px-3 py-2">
                            <span>Infant</span>
                            <input id="infant-price" class="w-28 border rounded px-2 py-1 text-right price-input"
                                placeholder="0">
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="mt-2 border-t pt-2 text-right text-sm font-medium text-gray-700">
                        Total Price: <span id="total-price">0.00</span>
                    </div>

                    <input type="hidden" id="price_json" name="price_json" value="[]">
                </div>
            </div>

            <div class="mt-6">
                <button type="submit"
                    class="flex items-center justify-center px-4 py-2 text-sm text-white rounded-md bg-primary border border-gray-300 dark:bg-white dark:border-gray-200 hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary-dark focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                    Create Activity
                </button>
            </div>

        </form>
    </div>

    @push('js')
        <script>
            document.addEventListener("DOMContentLoaded", () => {

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

                // for country-city dynamic select
                const countrySelect = document.getElementById('country_uuid');
                const citySelect = document.getElementById('city_uuid');

                countrySelect.addEventListener('change', function() {
                    const selectedCountry = this.value;

                    // Loop through all city options
                    for (const option of citySelect.options) {
                        if (!option.value) continue; // skip placeholder
                        if (option.dataset.countryUuid === selectedCountry) {
                            option.hidden = false;
                        } else {
                            option.hidden = true;
                        }
                    }

                    // Reset city selection
                    citySelect.value = '';
                });

                // for price inputs
                const totalEl = document.getElementById("total-price");
                const hiddenJson = document.getElementById("price_json");

                const priceTypes = [{
                        key: "adult",
                        label: "Adult"
                    },
                    {
                        key: "child",
                        label: "Child"
                    },
                    {
                        key: "infant",
                        label: "Infant"
                    },
                ];

                // Format number with commas (1,234.56)
                function formatPrice(value) {
                    if (value === "") return "";
                    const parts = value.split(".");
                    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                    return parts.join(".");
                }

                // Remove commas & keep only digits + dot
                function cleanInput(value) {
                    return value.replace(/[^0-9.]/g, "");
                }

                // Update total + hidden JSON
                function updateTotal() {
                    let total = 0;
                    const data = [];

                    priceTypes.forEach(p => {
                        const input = document.getElementById(`${p.key}-price`);
                        let raw = cleanInput(input.value);
                        if (raw === "") raw = "";

                        const val = parseFloat(raw) || 0;
                        input.value = formatPrice(raw);
                        data.push({
                            type: p.key,
                            price: val
                        });
                        total += val;
                    });

                    totalEl.textContent = total.toLocaleString(undefined, {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    });
                    hiddenJson.value = JSON.stringify(data);
                }

                // Handle input event for formatting
                document.querySelectorAll(".price-input").forEach(input => {
                    input.addEventListener("input", e => {
                        const cursorPos = input.selectionStart;
                        const raw = cleanInput(input.value);
                        const formatted = formatPrice(raw);
                        input.value = formatted;
                        try {
                            input.setSelectionRange(cursorPos, cursorPos);
                        } catch (_) {}
                        updateTotal();
                    });

                    // Prevent invalid key presses
                    input.addEventListener("keydown", e => {
                        const allowed = ["Backspace", "Tab", "ArrowLeft", "ArrowRight", "Delete",
                            "Home", "End"
                        ];
                        if (allowed.includes(e.key)) return;
                        if ((e.ctrlKey || e.metaKey) && ["a", "c", "v", "x"].includes(e.key
                                .toLowerCase())) return;
                        if (/^[0-9.]$/.test(e.key)) {
                            // prevent second dot
                            if (e.key === "." && input.value.includes(".")) e.preventDefault();
                            return;
                        }
                        e.preventDefault();
                    });

                    // On blur, if empty, set to 0.00
                    input.addEventListener("blur", () => {
                        if (input.value.trim() === "") {
                            input.value = "0";
                        }
                        updateTotal();
                    });
                });

                // Initial total
                updateTotal();
            });
        </script>
    @endpush
</x-backend.layouts.master>
