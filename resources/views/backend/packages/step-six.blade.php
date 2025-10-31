<div class="space-y-8">
    <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        <i class="fa-solid fa-box"></i> Package Inclusion
    </h2>
    <hr class="border-blue-400">

    @foreach ($activities as $activity)
        <div class="bg-white rounded-lg shadow p-6 border border-gray-100">
            <!-- Category Header -->
            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid {{ $activity->icon ?? 'fa-circle-info' }} text-blue-600"></i>
                <h3 class="text-lg font-semibold text-blue-700">{{ $activity->title }}</h3>
            </div>

            <!-- Inclusion Checkboxes -->
            <div class="grid md:grid-cols-3 sm:grid-cols-2 gap-3 mb-4" id="inclusion-list-{{ $activity->id }}">
                @foreach ($activity->inclusions as $inclusion)
                    <label class="flex items-center space-x-2 border rounded-md p-3 hover:bg-blue-50">
                        <input type="checkbox"
                            name="inclusions[{{ $activity->id }}][]"
                            value="{{ $inclusion->id }}"
                            class="text-blue-600 rounded">
                        <span class="text-gray-700">{{ $inclusion->title }}</span>
                    </label>
                @endforeach
            </div>

            <!-- Add New Inclusion -->
            <div class="flex gap-2">
                <input 
                    type="text"
                    id="new-inclusion-{{ $activity->id }}"
                    placeholder="Add new inclusion"
                    class="w-full border-gray-300 rounded-md focus:ring focus:ring-blue-200">
                
                <button 
                    type="button"
                    onclick="addInclusion({{ $activity->id }})"
                    class="bg-green-500 text-white rounded-md px-4 hover:bg-green-600 flex items-center justify-center">
                    <i class="fa-solid fa-plus"></i>
                </button>
            </div>
        </div>
    @endforeach
</div>

@push('js')
<script>
    function addInclusion(activityId) {
        const input = document.getElementById(`new-inclusion-${activityId}`);
        const list = document.getElementById(`inclusion-list-${activityId}`);

        const value = input.value.trim();
        if (!value) return;

        // Create label element
        const label = document.createElement('label');
        label.className = 'flex items-center space-x-2 border rounded-md p-3 hover:bg-blue-50';

        // Inner HTML for new inclusion
        label.innerHTML = `
            <input type="checkbox"
                   name="custom_inclusions[${activityId}][]"
                   value="${value}"
                   class="text-blue-600 rounded"
                   checked>
            <span class="text-gray-700">${value}</span>
        `;

        // Append and clear input
        list.appendChild(label);
        input.value = '';
    }
</script>
@endpush