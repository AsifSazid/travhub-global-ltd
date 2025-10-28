<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ __('Account\'s Information') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-xl">
        @include('account-info.partials.update-profile-information-form')
    </div>

    <div class="max-w-xl mt-4">
        @include('account-info.partials.update-password-form')
    </div>

    <div class="max-w-xl mt-4">
        @include('account-info.partials.delete-user-form')
    </div>
</x-backend.layouts.master>
