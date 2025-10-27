<x-backend.layouts.master>
    <!-- Content header -->
    <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
        <h1 class="text-2xl font-semibold">Dashboard</h1>
        {{-- <a href="https://github.com/Kamona-WD/kwd-dashboard" target="_blank"
            class="px-4 py-2 text-sm text-white rounded-md bg-primary hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
            View on github
        </a> --}}
    </div>

    <!-- Content -->
    <div class="my-2">
        <div class="py-1 max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="py-6 bg-white dark:bg-primary dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 px-4 text-gray-900 dark:text-gray-800">
                    {{ __("You're logged in!") }}
                </div>
            </div>
        </div>
    </div>
</x-backend.layouts.master>
