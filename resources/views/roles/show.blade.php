<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Show Role') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

    
                <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                    <!-- Title -->
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="title" value="{{ __('Title') }}" />
                        <x-input value="{{ $role->title }}" type="text" class="mt-1 block w-full" autocomplete="title" disabled />
                    </div>

                    <!-- Description -->
                    <div class="col-span-6 sm:col-span-4">
                        <x-label for="description" value="{{ __('Description') }}" />
                        <x-input value="{{ $role->description }}" type="text" class="mt-1 block w-full" autocomplete="description" disabled />
                    </div>

                </div>
                        

            </div>
        </div>
    </div>
</x-app-layout>
