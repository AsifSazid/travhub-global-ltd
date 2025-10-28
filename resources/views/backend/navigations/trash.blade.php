<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ __('Navigation Trash Lists') }}
            </h2>
        </div>
    </x-slot>

    <div class="overflow-x-auto">
        <table id="roleTable" class="w-full table-striped table-bordered text-sm">
            <thead class="bg-gray-100 text-gray-700 uppercase">
                <tr>
                    <th class="px-6 py-4">Sl No.</th>
                    <th class="px-6 py-4">Navigation Title</th>
                    <th class="px-6 py-4">Activity</th>
                    <th class="px-6 py-4">Deleted At</th>
                    <th class="px-6 py-4">Action</th>
                </tr>
            </thead>
            <tbody class="text-center">
                @forelse ($trashed as $trashed)
                    <tr>
                        <td class="px-6 py-4">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4">{{ $trashed->title }}</td>
                        <td class="px-6 py-4">{{ $trashed->is_active ? 'Active' : 'Inactive' }}</td>
                        <td class="px-6 py-4">{{ $trashed->deleted_at->diffForHumans() }}</td>
                        <td class="px-6 py-4">
                            <div class="flex justify-center space-x-2">
                                <form action="{{ route('navigations.restore', $trashed->uuid) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button class="text-yellow-500 hover:text-yellow-700 mx-1" title="Restore"><i
                                            class="fas fa-rotate"></i> </button>
                                </form>

                                <form action="{{ route('navigations.forceDelete', $trashed->uuid) }}" method="POST"
                                    class="inline ml-2"
                                    onsubmit="return confirm('Permanently delete this navigation?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-red-500 hover:text-red-700 mx-1"
                                        title="Delete Permanently"type="submit"><i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="py-4 text-center text-gray-500">No navigations found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{-- <div class="mt-4">
                            {{ $trashed->links() }}
                        </div> --}}

        <div class="mt-4 px-4 py-2 bg-gray-100 border-t text-sm text-gray-500 flex justify-between items-center">
            <a href="{{ route('navigations.index') }}" class="text-blue-500 hover:text-blue-700 mx-1"
                title="Navigation Lists"><i class="fas fa-trash-alt"></i> Navigation Lists</a>
        </div>
    </div>

    @push('js')
        <script></script>
    @endpush
</x-backend.layouts.master>
