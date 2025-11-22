<div class="px-4 py-2 bg-gray-100 border-t text-sm text-gray-500 flex justify-between items-center">
    <div class="flex justify-between items-center">
        <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ $package->title }}</h3>
        <p class="px-2"><strong>Progress Done:</strong><span class="text-green-600 font-semibold">
                {{ $package['progress_step'] == 7 ? 'Done' : $package['progress_step'] }} </span></p>
        <p class="px-2"><strong><a href="#">[Created By:
                    {{ $package->createdBy->title ?? ' ' }}]</strong></a></p>
    </div>
    <div class="text-right">
        <p><strong>Status:</strong>
            @if ($package->status === 'active')
                <span class="text-green-600 font-semibold">Active</span>
            @else
                <span class="text-red-600 font-semibold">Inactive</span>
            @endif
        </p>
        <p><strong>Completion Status:</strong>
            @if ($package['completion_status'] === 'completed')
                <span class="text-green-600 font-semibold">Complete</span>
            @else
                <span class="text-red-600 font-semibold">Incomplete</span>
            @endif
        </p>
    </div>
</div>

@include('backend.packages.pkg-details')
