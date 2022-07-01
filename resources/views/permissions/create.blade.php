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
                            <!-- Title -->
                            <div class="col-span-6 sm:col-span-4">
                                <x-jet-label for="title" value="{{ __('Title') }}" />
                                <x-jet-input name="title" id="title" type="text" class="mt-1 block w-full" autocomplete="title" />
                                <x-jet-input-error for="title" class="mt-2" />
                            </div>

                            <!-- Model -->
                            <div class="col-span-6 sm:col-span-4">
                                <x-jet-label for="model" value="{{ __('Model') }}" />
                                <select name="model" id="model" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                    <option value="">--Select</option>
                                    @for ($i = 0; $i < count($models); $i++)
                                        <option value="{{ $models[$i] }}">{{ $models[$i] }}</option>
                                    @endfor
                                </select>
                                <x-jet-input-error for="model" class="mt-2" />
                            </div>

                            
                            <!-- Method -->
                            <div class="col-span-6 sm:col-span-4">
                                <x-jet-label for="method" value="{{ __('Method') }}" />
                                <select name="method" id="method" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm">
                                    <option value="">--Select</option>
                                    @foreach ($methods as $method)
                                        <option value="{{ $method->id }}">{{ $method->title }}</option>
                                    @endforeach
                                </select>
                                <x-jet-input-error for="method" class="mt-2" />
                            </div>

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
