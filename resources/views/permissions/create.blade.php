<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Permission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <form action="{{ route('permission.store') }}" method="post">
                        @csrf
    
                        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                            {{-- <!-- Title -->
                            <div class="col-span-6 sm:col-span-4">
                                <x-jet-label for="title" value="{{ __('Title') }}" />
                                <x-jet-input name="title" id="title" type="text" class="mt-1 block w-full" autocomplete="title" />
                                <x-jet-input-error for="title" class="mt-2" />
                            </div> --}}

                            <!-- Model -->
                            <div class="col-span-6 sm:col-span-4">
                                <x-jet-label for="model" value="{{ __('Model') }}" />
                                <select name="model" id="model" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" required>
                                    <option value="">--Select</option>
                                    @for ($i = 0; $i < count($models); $i++)
                                        <option value="{{ $models[$i] }}">{{ $models[$i] }}</option>
                                    @endfor
                                </select>
                                <x-jet-input-error for="model" class="mt-2" />
                            </div>

                            
                            <!-- Method -->
                            <div class="col-span-6 sm:col-span-4">
                                <x-jet-label for="method" value="{{ __('Methods') }}" />
                                @foreach ($methods as $method)
                                <div class="flex flex-auto mt-4">
                                    <label for="method[]" class="flex items-center justify-between mr-2">
                                        <input type="checkbox" name="method[]" id="method" value="{{ $method->id }}" class="'rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50'">
                                        <x-jet-label for="method" value="{{ $method->title }}" />
                                    </label>
                                </div>
                                @endforeach
                            </div>
                            <x-jet-input-error for="method" class="mt-2" />

                            {{-- Submit Button --}}
                            <x-jet-button class="mt-4">
                                {{ __('Submit') }}
                            </x-jet-button>
        
                        </div>
                        
                    </form>

            </div>
        </div>
    </div>
</x-app-layout>
