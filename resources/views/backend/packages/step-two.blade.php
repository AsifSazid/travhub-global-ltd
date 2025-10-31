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
        <input type="text" name="no_of_pax" value="{{ old('no_of_pax', $package->no_of_pax ?? '') }}"
            class="mt-1 w-full border rounded px-3 py-2">
        @error('no_of_pax')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label>Start Date</label>
        <input type="date" name="start_date" value="{{ old('start_date', $package->start_date ?? '') }}"
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
