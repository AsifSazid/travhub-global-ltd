<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">{{ __('Packages') }}</h2>
            <a href="{{ route('packages.create') }}" class="text-green-500 hover:text-green-700 mx-1" title="Create">
                <i class="fas fa-plus"></i> Create New
            </a>
        </div>
    </x-slot>

    <div class="overflow-x-auto">
        <!-- Search -->
        <input id="searchInput"
            class="placeholder:italic placeholder:text-slate-400 block bg-white w-full border border-slate-300 rounded-md py-2 pl-4 pr-3 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-sm"
            placeholder="Search for anything..." type="text" name="search">

        <!-- Table -->
        <table id="roleTable" class="w-full table-striped table-bordered text-sm mt-4">
            <thead class="bg-gray-100 text-gray-700 uppercase">
                <tr>
                    <th class="px-6 py-4">Sl No.</th>
                    <th class="px-6 py-4">Package Name</th>
                    <th class="px-6 py-4">Created By</th>
                    <th class="px-6 py-4">Action</th>
                </tr>
            </thead>
            <tbody id="roleTableBody" class="text-center">
                <!-- JS will inject rows here -->
            </tbody>
        </table>

        <!-- Pagination + Actions -->
        <div class="mt-4 px-6">
            <div id="paginationLinks" class="flex justify-center mb-4"></div>

            <div class="px-4 py-2 bg-gray-100 border-t text-sm text-gray-500 flex justify-between items-center">
                <a href="{{ route('packages.trash') }}" class="text-red-500 hover:text-red-700 mx-1"
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
                const tableBody = document.getElementById("roleTableBody");
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                const fetchRoles = async (search = '', page = 1) => {
                    try {
                        const response = await fetch(
                            `{{ route('packages.getData') }}?search=${search}&page=${page}`, {
                                credentials: 'same-origin'
                            });
                        const result = await response.json();
                        renderTable(result.data);
                        renderPagination(result, search);
                    } catch (error) {
                        console.error("Error fetching packages:", error);
                    }
                };

                const renderTable = (packages) => {
                    tableBody.innerHTML = "";
                    if (!packages || packages.length === 0) {
                        tableBody.innerHTML =
                            `<tr><td colspan="5" class="py-4 text-center text-gray-500">No packages found</td></tr>`;
                        return;
                    }

                    packages.forEach((package, index) => {
                        const row = `
                                <tr>
                                    <td class="px-6 py-4">${index + 1}</td>
                                    <td class="px-6 py-4">${package.title}</td>
                                    <td class="px-6 py-4">${package.user?.title ?? 'N/A'}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex justify-center">
                                            <a href="/packages/${package.uuid}" class="px-1 text-blue-500 hover:text-blue-700" title="View"><i class="fas fa-eye"></i></a>
                                            <a href="/packages/${package.uuid}/edit" class="px-1 text-yellow-500 hover:text-yellow-700" title="Edit"><i class="fas fa-edit"></i></a>
                                            <form action="/packages/${package.uuid}" method="POST" onsubmit="return confirm('Move to trash?')">
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
                        btn.addEventListener("click", () => fetchRoles(search, i));
                        paginationDiv.appendChild(btn);
                    }
                };

                // Initial load
                fetchRoles();

                // Search input listener
                input.addEventListener("input", (e) => {
                    const searchTerm = e.target.value.trim();
                    fetchRoles(searchTerm, 1);
                });

                // PDF download
                document.getElementById("downloadPdfBtn").addEventListener("click", () => {
                    const search = document.getElementById("searchInput").value.trim();
                    let url = `{{ route('packages.download.pdf') }}`;
                    if (search) {
                        url += `?search=${encodeURIComponent(search)}`;
                    }
                    window.open(url, "_blank");
                });
            });
        </script>
    @endpush
</x-backend.layouts.master>
