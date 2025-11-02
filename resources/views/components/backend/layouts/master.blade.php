<!DOCTYPE html>
<html lang="en">

<head>
    <x-backend.layouts.partials.meta />
    <x-backend.layouts.partials.title :title={{ __('TravHub Global Limited') }} />
    <x-backend.layouts.partials.favicon />
    <x-backend.layouts.libs.style />
</head>

<body>
    <div x-data="setup()" x-init="$refs.loading.classList.add('hidden');
    setColors(color);"
        :class="{ 'dark': isDark }">

        <div class="flex h-screen antialiased text-gray-900 bg-gray-100 dark:bg-dark dark:text-light">
            <!-- Loading screen -->
            <div x-ref="loading"
                class="fixed inset-0 z-50 flex items-center justify-center text-2xl font-semibold text-white bg-primary-darker">
                Loading.....
            </div>

            <!-- Sidebar -->
            <x-backend.layouts.partials.aside />


            <div class="flex-1 h-full overflow-x-hidden overflow-y-auto">
                <!-- Navbar -->
                <x-backend.layouts.partials.navbar />


                <!-- Main content -->
                <main>
                    <!-- Content header -->
                    {{ $header }}

                    <!-- Content -->
                    <div class="py-1 max-w-7xl mx-auto sm:px-2 lg:px-2">
                        <div
                            class="bg-white dark:bg-primary dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 px-4 text-gray-900 dark:text-gray-800">
                                {{ $slot }}
                            </div>
                        </div>
                    </div>

                </main>

                <!-- Main footer -->
                <x-backend.layouts.partials.footer />
            </div>


        </div>
    </div>

    <x-backend.layouts.libs.js />

    @stack('js')
</body>

</html>
