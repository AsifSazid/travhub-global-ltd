<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ __('Profile Details') }}
            </h2>
        </div>
    </x-slot>


    {{-- Profile Start --}}
    <div class="max-w-4xl mx-auto space-y-6">

        {{-- Main Profile --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 flex items-center justify-between">
            <div class="flex items-center space-x-6">
                {{-- Profile Image --}}
                <div class="w-24 h-24">
                    <img src="https://via.placeholder.com/150" alt="Profile Photo"
                        class="w-full h-full rounded-full object-cover border-2 border-gray-200 dark:border-gray-700">
                </div>

                {{-- Name & Title --}}
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200">{{ $profile->title }}</h3>
                    <p class="text-gray-500 dark:text-gray-400">Team Manager | Arizona, United States</p>
                </div>
            </div>

            {{-- Social Icons + Edit Button --}}
            <div class="flex items-center space-x-3">
                <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fab fa-facebook-f"></i>
                </button>
                <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fab fa-x-twitter"></i>
                </button>
                <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fab fa-linkedin-in"></i>
                </button>
                <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700">
                    <i class="fab fa-instagram"></i>
                </button>
                <button
                    class="flex items-center space-x-1 px-3 py-1 border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Edit</span>
                </button>
            </div>
        </div>

        {{-- Personal Information --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 relative">

            <div class="flex justify-between items-start mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Personal Information</h3>
                <button
                    class="flex items-center space-x-1 px-3 py-1 border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Edit</span>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">First Name</p>
                    <p class="text-gray-800 dark:text-gray-200 font-medium">Chowdhury</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Last Name</p>
                    <p class="text-gray-800 dark:text-gray-200 font-medium">Musharof</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Email Address</p>
                    <p class="text-gray-800 dark:text-gray-200 font-medium">randomuser@pimjo.com</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Phone</p>
                    <p class="text-gray-800 dark:text-gray-200 font-medium">+09 363 398 46</p>
                </div>
                <div class="md:col-span-2">
                    <p class="text-gray-500 dark:text-gray-400 text-sm">Bio</p>
                    <p class="text-gray-800 dark:text-gray-200 font-medium">Team Manager</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 relative">

            <div class="flex justify-between items-start mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Professional Information</h3>
                <button
                    class="flex items-center space-x-1 px-3 py-1 border rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>Edit</span>
                </button>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">First Name</p>
                    <p class="text-gray-800 dark:text-gray-200 font-medium">Chowdhury</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Profile End --}}

    @push('js')
    @endpush
</x-backend.layouts.master>
