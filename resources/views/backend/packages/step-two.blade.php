<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Duration -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Duration (days)</label>
        <input type="number" id="duration" name="duration" value="{{ old('duration', $packQuatInfo->duration ?? '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2"
            placeholder="e.g. 7" min="1">

        @error('duration')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- Start Date -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Start Date</label>
        <input type="date" id="start_date" name="start_date"
            value="{{ old('start_date', isset($packQuatInfo->start_date) ? date('Y-m-d', strtotime($packQuatInfo->start_date)) : '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:border-indigo-500 focus:ring-indigo-500 px-3 py-2">

        @error('start_date')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

    <!-- End Date -->
    <div>
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">End Date</label>
        <input type="date" id="end_date" name="end_date"
            value="{{ old('end_date', isset($packQuatInfo->end_date) ? date('Y-m-d', strtotime($packQuatInfo->end_date)) : '') }}"
            class="mt-1 block w-full rounded-lg border-gray-300 bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 cursor-not-allowed px-3 py-2"
            readonly>

        @error('end_date')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
        @enderror
    </div>

</div>



{{-- ========== PAX SECTION ========== --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-2">

    <div>
        <label class="block text-sm font-medium text-gray-700">Number of Pax</label>

        <div class="mt-2 space-y-2" id="pax-container">

            {{-- Adult --}}
            <div class="flex items-center justify-between border rounded px-3 py-2">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" id="adult-check" class="rounded text-indigo-600 focus:ring-indigo-500">
                    <span>Adult</span>
                </label>
                <input type="number" id="adult-count" class="w-20 border rounded px-2 py-1 text-center" min="0"
                    placeholder="0">
            </div>

            {{-- Child --}}
            <div class="flex items-center justify-between border rounded px-3 py-2">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" id="child-check" class="rounded text-indigo-600 focus:ring-indigo-500">
                    <span>Child</span>
                </label>
                <input type="number" id="child-count" class="w-20 border rounded px-2 py-1 text-center" min="0"
                    placeholder="0">
            </div>

            {{-- Infant --}}
            <div class="flex items-center justify-between border rounded px-3 py-2">
                <label class="flex items-center space-x-2">
                    <input type="checkbox" id="infant-check" class="rounded text-indigo-600 focus:ring-indigo-500">
                    <span>Infant</span>
                </label>
                <input type="number" id="infant-count" class="w-20 border rounded px-2 py-1 text-center" min="0"
                    placeholder="0">
            </div>

        </div>

        <!-- Total -->
        <div class="mt-2 border-t pt-2 text-right text-sm font-medium text-gray-700">
            Total Pax: <span id="total-pax">0</span>
        </div>

        {{-- ========== Hidden Input ========== --}}
        @php
            $paxOld = old('no_of_pax');

            if ($paxOld) {
                $paxOld = json_decode($paxOld, true) ?? [];
            } else {
                $paxOld = $packQuatInfo->no_of_pax ?? [];
            }
        @endphp

        <input type="hidden" name="no_of_pax" id="no_of_pax" value='@json($paxOld)'>

        @error('no_of_pax')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror

    </div>

</div>



{{-- ========== JS ========== --}}
@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const durationInput = document.getElementById('duration');
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');

            const today = new Date().toISOString().split('T')[0];
            startDateInput.setAttribute('min', today);

            function updateEndDate() {
                const duration = parseInt(durationInput.value);
                const startDate = startDateInput.value;

                if (startDate && duration > 0) {
                    const start = new Date(startDate);
                    const end = new Date(start);
                    end.setDate(start.getDate() + duration);
                    endDateInput.value = end.toISOString().split('T')[0];
                } else {
                    endDateInput.value = '';
                }
            }

            durationInput.addEventListener('input', updateEndDate);
            startDateInput.addEventListener('change', updateEndDate);
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const paxInput = document.getElementById('no_of_pax');
            const totalDisplay = document.getElementById('total-pax');

            const paxTypes = [{
                    key: 'adult',
                    label: 'Adult'
                },
                {
                    key: 'child',
                    label: 'Child'
                },
                {
                    key: 'infant',
                    label: 'Infant'
                }
            ];

            function updateJSON() {
                const data = [];
                let total = 0;

                paxTypes.forEach(pax => {
                    const checkbox = document.getElementById(`${pax.key}-check`);
                    const countInput = document.getElementById(`${pax.key}-count`);

                    if (checkbox.checked && countInput.value) {
                        const count = parseInt(countInput.value);
                        data.push({
                            type: pax.key,
                            count: count
                        });
                        total += count;
                    }
                });

                paxInput.value = JSON.stringify(data);
                totalDisplay.textContent = total;
            }

            // Preload Existing Data
            try {
                const oldData = JSON.parse(paxInput.value || '[]');
                oldData.forEach(item => {
                    const checkbox = document.getElementById(`${item.type}-check`);
                    const countInput = document.getElementById(`${item.type}-count`);
                    if (checkbox && countInput) {
                        checkbox.checked = true;
                        countInput.disabled = false;
                        countInput.value = item.count;
                    }
                });
                updateJSON();
            } catch (e) {
                console.warn("Invalid JSON:", e);
            }

            // Listeners
            paxTypes.forEach(pax => {
                const checkbox = document.getElementById(`${pax.key}-check`);
                const countInput = document.getElementById(`${pax.key}-count`);

                checkbox.addEventListener('change', function() {
                    countInput.disabled = !this.checked;
                    if (!this.checked) countInput.value = '';
                    updateJSON();
                });

                countInput.addEventListener('input', updateJSON);
            });

        });
    </script>
@endpush
