<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">{{ __('Navigation Lists') }}</h2>
            <a href="{{ route('navigations.create') }}" class="text-green-500 hover:text-green-700 mx-1" title="Create"><i
                    class="fas fa-plus"></i> Create New</a>
        </div>
    </x-slot>

    <div class="overflow-x-auto">
        <input id="searchInput"
            class="placeholder:italic placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-9 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm"
            placeholder="Search for anything..." type="text" name="search">
        <!-- Table -->
        <table id="announcementTable" class="w-full table-striped table-bordered text-sm mt-4">
            <thead class="bg-gray-100 text-gray-700 uppercase">
                <tr>
                    <th class="px-6 py-4">Sl No.</th>
                    <th class="px-6 py-4">Navigation Title</th>
                    <th class="px-6 py-4">Parent Navigation</th>
                    <th class="px-6 py-4">URL</th>
                    <th class="px-6 py-4">Route</th>
                    <th class="px-6 py-4">Created By</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4">Action</th>
                </tr>
            </thead>
            <tbody id="announcementTableBody" class="text-center">
                <!-- JS will inject rows here -->
            </tbody>
        </table>

        <!-- Pagination + Actions -->
        <div class="mt-4 px-6">
            <div id="paginationLinks" class="flex justify-center mb-4"></div>

            <div class="px-4 py-2 bg-gray-100 border-t text-sm text-gray-500 flex justify-between items-center">
                <a href="{{ route('navigations.trash') }}" class="text-red-500 hover:text-red-700 mx-1"
                    title="Trash Lists">
                    <i class="fas fa-trash-alt"></i> Trash Lists
                </a>
                <a id="downloadPdfBtn" class="text-blue-500 hover:text-blue-700 mx-1 cursor-pointer"
                    title="Download as PDF">
                    <i class="fa-solid fa-download"></i>
                </a>
            </div>
        </div>

    </div>


    @push('js')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const input = document.getElementById("searchInput");
                const tableBody = document.getElementById("announcementTableBody");
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                const formatDateTime = (dateStr) => {
                    const date = new Date(dateStr);
                    return date.toLocaleString('en-GB', {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false,
                        timeZone: 'UTC'
                    }).replace(',', ' ||');
                };

                const fetchAnnouncements = async (search = '', page = 1) => {
                    try {
                        const response = await fetch(
                            `{{ route('navigations.getData') }}?search=${search}&page=${page}`);
                        const result = await response.json();
                        renderTable(result.data);
                        renderPagination(result, search);
                    } catch (error) {
                        console.error("Error fetching navigations:", error);
                    }
                };

                const renderTable = (navigations) => {
                    tableBody.innerHTML = "";
                    if (navigations.length === 0) {
                        tableBody.innerHTML =
                            `<tr><td colspan="7" class="py-4 text-center text-gray-500">No navigations found</td></tr>`;
                        return;
                    }

                    navigations.forEach((navigation, index) => {
                        const row = `
                        <tr>
                            <td class="px-6 py-4">${index + 1}</td>
                            <td class="px-6 py-4">${navigation.title}</td>
                            <td class="px-6 py-4">${navigation.parent_id ? navigation.parent_id : '-' }</td>
                            <td class="px-6 py-4">${navigation.url }</td>
                            <td class="px-6 py-4">${navigation.route ?? 'N/A'}</td>
                            <td class="px-6 py-4">${navigation.user?.name ?? 'N/A'}</td>
                            <td class="px-6 py-4">${navigation.status === 'active' ? 'Active' : 'Inactive'}</td>
                            <td class="px-6 py-4">
                                <div class="flex justify-center">
                                    <a href="/navigations/${navigation.uuid}" class="px-1 text-blue-500 hover:text-blue-700" title="View"><i class="fas fa-eye"></i></a>
                                    <a href="/navigations/${navigation.uuid}/edit" class="px-1 text-yellow-500 hover:text-yellow-700" title="Edit"><i class="fas fa-edit"></i></a>
                                    <form action="/navigations/${navigation.uuid}" method="POST" onsubmit="return confirm('Move to trash?')">
                                        <input type="hidden" name="_token" value="${csrfToken}">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="px-1 text-red-500 hover:text-red-700" title="Destroy">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    `;
                        tableBody.insertAdjacentHTML('beforeend', row);
                    });
                };

                const renderPagination = (data, search) => {
                    const paginationDiv = document.getElementById("paginationLinks");
                    paginationDiv.innerHTML = "";

                    if (data.last_page <= 1) return;

                    for (let i = 1; i <= data.last_page; i++) {
                        const btn = document.createElement("button");
                        btn.innerText = i;
                        btn.className =
                            `mx-1 px-3 py-1 rounded ${i === data.current_page ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'}`;
                        btn.addEventListener("click", () => fetchAnnouncements(search, i));
                        paginationDiv.appendChild(btn);
                    }
                };

                // Initial load
                fetchAnnouncements();

                // Search input listener
                input.addEventListener("input", (e) => {
                    const searchTerm = e.target.value.trim();
                    fetchAnnouncements(searchTerm, 1); // reset to page 1 on new search
                });

                document.getElementById("downloadPdfBtn").addEventListener("click", () => {
                    const search = document.getElementById("searchInput").value.trim();
                    let url = `{{ route('navigations.download.pdf') }}`;
                    if (search) {
                        url += `?search=${encodeURIComponent(search)}`;
                    }
                    window.open(url, "_blank");
                });
            });
        </script>
    @endpush
</x-backend.layouts.master>
