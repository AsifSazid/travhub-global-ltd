@php
    // dd('im from blade', $uuid, $step);
@endphp


<x-backend.layouts.master>
    <x-slot name="header">
        <div class="flex items-center justify-between px-4 py-4 border-b lg:py-6 dark:border-primary-darker">
            <h2 class="text-2xl font-semibold">
                {{ __('Create Package (Step-by-step)') }}
            </h2>
        </div>
    </x-slot>


    <div class="max-w-4xl mx-auto py-6">

        @if (session('success'))
            <div class="bg-green-200 text-green-800 px-4 py-2 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('packages.step', ['uuid' => $uuid, 'step' => $step]) }}" method="POST">
            @csrf

            {{-- Step 1: Destination --}}
            @if ($step == 1)
                @include('backend.packages.step-one')
            @endif

            {{-- Step 2: Quotation --}}
            @if ($step == 2)
                @include('backend.packages.step-two')
            @endif

            {{-- Step 3: Accommodation --}}
            @if ($step == 3)
                @include('backend.packages.step-three')
            @endif

            {{-- Step 4: Pricing --}}
            @if ($step == 4)
                @include('backend.packages.step-four')
            @endif

            {{-- Step 5: Itineraries & Inclusions --}}
            @if ($step == 5)
                @include('backend.packages.step-five')
            @endif

            {{-- Step 6: Confirm --}}
            @if ($step == 6)
                @include('backend.packages.step-six')
            @endif


            @if ($step == 7)
            @endif

            {{-- Navigation --}}
            <div class="mt-6 flex justify-between">
                @if ($step > 1)
                    {{-- <a href="{{ route('packages.step', ['uuid' => $uuid, 'step' => $step - 1]) }}"
                        class="px-4 py-2 border rounded hover:bg-gray-100">Back</a> --}}
                @else
                    <span></span>
                @endif

                <button type="submit"
                    class="flex items-center justify-center px-4 py-2 text-sm text-white rounded-md bg-primary border border-gray-300 dark:bg-white dark:border-gray-200 hover:bg-primary-dark focus:outline-none focus:ring focus:ring-primary-dark focus:ring-offset-1 focus:ring-offset-white dark:focus:ring-offset-dark">
                    {{ $step < 7 ? 'Save & Next' : 'Finish' }}
                </button>
            </div>
        </form>
    </div>

    @push('js')
    @endpush
</x-backend.layouts.master>
