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
        <label>Price Options (one per line, format: option|price) (Total PAX:
            {{ $packQuatDetails->no_of_pax }})</label>
        <textarea name="price_options" class="mt-1 w-full border rounded px-3 py-2">{{ old('price_options', $package->price_options ?? '') }}</textarea>
        @error('price_options')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>
</div>
