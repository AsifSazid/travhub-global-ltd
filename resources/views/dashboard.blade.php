<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <!-- Content -->
    {{ __("You're logged in!") }}
</x-backend.layouts.master>
