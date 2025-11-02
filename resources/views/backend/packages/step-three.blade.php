<div class="bg-white rounded-xl border shadow-md p-6 space-y-6">

    {{-- Tabs --}}
    <div id="cityTabs" class="flex gap-2 overflow-x-auto border-b pb-2">
        @foreach ($cities as $city)
            <button data-tab="city_{{ $city->id }}" type="button"
                class="tab-btn whitespace-nowrap px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition">
                {{ $city->title }}
            </button>
        @endforeach

        <button id="openCityModal" type="button"
            class="flex items-center gap-1 bg-blue-600 text-white text-sm px-3 py-2 rounded-lg hover:bg-blue-700 transition">
            <i class="fa-solid fa-plus"></i> Add City
        </button>
    </div>

    {{-- Content --}}
    <div id="tabContentWrapper">
        @foreach ($cities as $city)
            @php $hotels = \App\Models\Hotel::where('city_id', $city->id)->get(); @endphp

            <div id="city_{{ $city->id }}" class="tab-content hidden">

                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-semibold text-gray-700">
                        Hotels in {{ $city->title }}
                    </h4>
                </div>

                <div id="hotel-list-{{ $city->id }}" class="hotel-list space-y-2">
                    @forelse($hotels as $hotel)
                        <label class="flex items-center gap-2 text-sm bg-gray-50 border rounded-lg px-3 py-2">
                            <input type="radio" name="hotels[{{ $city->id }}]" value="{{ $hotel->id }}">
                            <span>{{ $hotel->title }}</span>
                        </label>
                    @empty
                        <p class="text-gray-500 text-sm italic">No hotels found</p>
                    @endforelse
                </div>

                <div class="flex gap-2 mt-4">
                    <input type="text" id="new-hotel-{{ $city->id }}" placeholder="Add new hotel"
                        class="w-full border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                    <button type="button" onclick="addHotel({{ $city->id }})"
                        class="bg-green-500 text-white rounded-md px-4 hover:bg-green-600 flex items-center justify-center">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>

            </div>
        @endforeach
    </div>
</div>

<!-- City Modal -->
<div id="cityModal" class="fixed inset-0 flex items-center justify-center bg-gray-900/60 hidden z-[9999]">
    <div class="bg-white rounded-xl shadow-2xl border border-gray-300 max-w-md p-6 relative animate-fadeIn">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Add City</h3>
        <input id="country_id" type="hidden" value="{{ $cities->first()?->country_id }}">
        <input id="cityName" type="text"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
            placeholder="City Name">

        <div class="flex justify-end gap-3 mt-6">
            <button type="button" id="closeCityModal"
                class="px-4 py-2 rounded-lg border border-gray-300 text-gray-600 hover:bg-gray-100 transition">Cancel</button>
            <button type="button" id="saveCityBtn"
                class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                Save
            </button>
        </div>
    </div>
</div>

<!-- CSRF Meta -->
<meta name="csrf-token" content="{{ csrf_token() }}">

@push('css')
    <style>
        @keyframes fadeIn {
            0% {
                opacity: 0;
                transform: translateY(-10px);
            }

            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.2s ease-out forwards;
        }
    </style>
@endpush

@push('js')
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const cityTabs = document.getElementById("cityTabs");
            const tabContentWrapper = document.getElementById("tabContentWrapper");
            const cityModal = document.getElementById("cityModal");
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            // ---- INIT TABS ----
            function initTabs() {
                const tabButtons = document.querySelectorAll(".tab-btn");
                const tabs = document.querySelectorAll(".tab-content");

                if (tabButtons.length && !document.querySelector(".tab-btn.active")) {
                    tabButtons.forEach(btn => btn.classList.remove("active", "border-blue-500", "text-blue-600"));
                    tabs.forEach(tab => tab.classList.add("hidden"));

                    tabButtons[0].classList.add("active", "border-blue-500", "text-blue-600");
                    tabs[0].classList.remove("hidden");
                }

                tabButtons.forEach(button => {
                    button.onclick = () => {
                        const id = button.dataset.tab;
                        tabButtons.forEach(b => b.classList.remove("active", "border-blue-500",
                            "text-blue-600"));
                        tabs.forEach(t => t.classList.add("hidden"));

                        button.classList.add("active", "border-blue-500", "text-blue-600");
                        document.getElementById(id).classList.remove("hidden");
                    };
                });
            }

            initTabs();

            // ---- MODAL OPEN/CLOSE ----
            document.getElementById("openCityModal").onclick = () => cityModal.classList.remove("hidden");
            document.getElementById("closeCityModal").onclick = () => cityModal.classList.add("hidden");

            // ---- SAVE CITY ----
            document.getElementById("saveCityBtn").onclick = async () => {
                const cityName = document.getElementById("cityName").value.trim();
                if (!cityName) {
                    alert("Please enter a city name!");
                    return;
                }

                const data = new FormData();
                data.append("title", cityName);
                data.append("country_id", document.getElementById("country_id").value);
                data.append("_token", csrfToken);
                data.append("package_uuid", "{{ $uuid }}");


                try {
                    const res = await fetch("{{ route('api.cities.store') }}", {
                        method: "POST",
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: data,
                        credentials: 'same-origin'
                    });

                    if (!res.ok) {
                        const errData = await res.json();
                        alert(errData.message || 'Error saving city');
                        return;
                    }

                    const city = await res.json();

                    // --- Add tab button ---
                    const newBtn = document.createElement("button");
                    newBtn.type = "button";
                    newBtn.dataset.tab = "city_" + city.id;
                    newBtn.className =
                        "tab-btn whitespace-nowrap px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 text-gray-600 hover:bg-blue-50 hover:text-blue-600 transition";
                    newBtn.textContent = city.title;
                    cityTabs.insertBefore(newBtn, document.getElementById("openCityModal"));

                    // --- Add content div ---
                    const div = document.createElement("div");
                    div.id = "city_" + city.id;
                    div.className = "tab-content hidden";
                    div.innerHTML = `
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-sm font-semibold text-gray-700">Hotels in ${city.title}</h4>
                </div>
                <div id="hotel-list-${city.id}" class="hotel-list space-y-2">
                    <p class="text-gray-500 text-sm italic">No hotels yet.</p>
                </div>
                <div class="flex gap-2 mt-4">
                    <input type="text" id="new-hotel-${city.id}" placeholder="Add new hotel"
                        class="w-full border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                    <button type="button" onclick="addHotel(${city.id})"
                        class="bg-green-500 text-white rounded-md px-4 hover:bg-green-600 flex items-center justify-center">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>
            `;
                    tabContentWrapper.appendChild(div);

                    initTabs(); // re-init tabs
                    cityModal.classList.add("hidden");
                    document.getElementById("cityName").value = "";

                } catch (err) {
                    console.error(err);
                    alert("Something went wrong!");
                }
            };

        });

        // ---- ADD HOTEL ----
        function addHotel(cityId) {
            const input = document.getElementById(`new-hotel-${cityId}`);
            const value = input.value.trim();
            if (!value) return;

            const list = document.getElementById(`hotel-list-${cityId}`);

            const label = document.createElement('label');
            label.className = 'flex items-center space-x-2 border rounded-md p-3 hover:bg-blue-50';
            label.innerHTML = `
        <input type="checkbox" name="custom_hotels[${cityId}][]" value="${value}" class="text-blue-600 rounded" checked>
        <span class="text-gray-700">${value}</span>
    `;
            list.appendChild(label);
            input.value = '';
        }
    </script>
@endpush
