<div class="bg-white rounded-xl shadow p-6 space-y-4">
    <h2 class="text-xl font-semibold flex items-center gap-2 border-b pb-2">
        <i class="fa-solid fa-hotel text-blue-500"></i>
        Accommodation Details
    </h2>

    @foreach ($cities as $city)
        <div class="border rounded-lg overflow-hidden">
            {{-- Checkbox trick to toggle accordion --}}
            <input type="checkbox" id="city_{{ $city->id }}" class="peer hidden">

            {{-- Header --}}
            <label for="city_{{ $city->id }}"
                class="flex justify-between items-center px-4 py-3 bg-gray-100 cursor-pointer hover:bg-gray-200">
                <span class="font-medium">{{ $city->title }}</span>
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5 text-gray-600 transition-transform duration-200 peer-checked:rotate-180"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </label>

            {{-- Body --}}
            <div
                class="max-h-0 overflow-hidden transition-all duration-300 peer-checked:max-h-screen bg-white border-t">
                <div class="p-4 space-y-3">
                    <p class="font-semibold text-sm">Hotel Options in {{ $city->title }}</p>

                    @php
                        $hotels = \App\Models\Hotel::where('city_id', $city->id)->get();
                    @endphp

                    {{-- List hotels --}}
                    @forelse($hotels as $index => $hotel)
                        <div class="flex items-center space-x-2">
                            <input type="radio" id="hotel_{{ $city->id }}_{{ $hotel->id }}"
                                name="hotels[{{ $city->id }}]" value="{{ $hotel->id }}"
                                class="text-blue-600 focus:ring-blue-500" />
                            <label for="hotel_{{ $city->id }}_{{ $hotel->id }}" class="text-sm">
                                Option {{ $index + 1 }}: {{ $hotel->title }}
                            </label>
                        </div>
                    @empty
                        <p class="text-gray-500 text-sm">No hotels available for this city.</p>
                    @endforelse

                    {{-- Add new hotel option --}}
                    {{-- <div class="flex items-center mt-4">
                                        <input type="text" name="new_hotel[{{ $city->id }}]"
                                            placeholder="Add new hotel option"
                                            class="flex-1 border rounded px-3 py-2 text-sm">
                                        <button type="button" class="ml-2 bg-green-500 text-white px-3 py-2 rounded">
                                            +
                                        </button>
                                    </div> --}}
                </div>
            </div>
        </div>
    @endforeach
</div>
