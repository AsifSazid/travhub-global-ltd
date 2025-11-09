<div class="space-y-8">
    @foreach ($inclusions as $index => $inclusion)
        <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
            <!-- Category Header -->
            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid {{ $inclusion['icons'] ?? 'fa-circle-info' }} text-blue-600"></i>
                <h3 class="text-lg font-semibold text-blue-700">{{ $inclusion['title'] }}</h3>
            </div>

            <!-- Inclusion Checkboxes -->
            <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-3 mb-4" id="inclusion-list-{{ $index }}">
                @foreach ($inclusion['sub_title'] as $number => $sub)
                    <label class="flex items-center space-x-2 border rounded-md p-3 hover:bg-blue-50">
                        <input type="checkbox" name="inclusions[{{ $index }}][{{ $loop->index }}][text]"
                            value="{{ $sub }}" class="text-blue-600 rounded" checked>
                        <input type="hidden" name="inclusions[{{ $index }}][{{ $loop->index }}][number]"
                            value="{{ $number }}">
                        <input type="hidden" name="inclusions[{{ $index }}][{{ $loop->index }}][type]"
                            value="system">
                        <span class="text-gray-700">{{ $sub }}</span>
                    </label>
                @endforeach
            </div>

            <!-- Add New Inclusion -->
            <div class="flex gap-2">
                <input type="text" id="new-inclusion-{{ $index }}" placeholder="Add new inclusion"
                    class="w-full border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                <button type="button" onclick="addInclusion({{ $index }})"
                    class="bg-green-500 text-white rounded-md px-4 hover:bg-green-600 flex items-center justify-center">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </div>
    @endforeach
</div>

@push('js')
    <script>
        function addInclusion(activityIndex) {
            const input = document.getElementById(`new-inclusion-${activityIndex}`);
            const list = document.getElementById(`inclusion-list-${activityIndex}`);

            const value = input.value.trim();
            if (!value) return;

            // Count existing items to generate sequential number
            const existingLabels = list.querySelectorAll('label');
            const nextIndex = existingLabels.length; // index for name
            const nextNumber = existingLabels.length + 1; // number shown

            // Create label element
            const label = document.createElement('label');
            label.className = 'flex items-center space-x-2 border rounded-md p-3 hover:bg-blue-50';

            // Inner HTML for new custom inclusion
            label.innerHTML = `
            <input type="checkbox"
                   name="inclusions[${activityIndex}][${nextIndex}][text]"
                   value="${value}"
                   class="text-blue-600 rounded"
                   checked>
            <input type="hidden"
                   name="inclusions[${activityIndex}][${nextIndex}][number]"
                   value="${nextNumber}">
            <input type="hidden"
                   name="inclusions[${activityIndex}][${nextIndex}][type]"
                   value="custom">
            <span class="text-gray-700">${value}</span>
        `;

            // Append and clear input
            list.appendChild(label);
            input.value = '';
        }
    </script>
@endpush
