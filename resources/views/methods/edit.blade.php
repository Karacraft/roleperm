<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Method') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <form action="{{ route('method.update',$method) }}" method="post">
                        @csrf
                        @method('PUT')
                        
                        <input type="hidden" name="method" value="{{ $method }}">
                        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                            <!-- Title -->
                            <div class="col-span-6 sm:col-span-4">
                                <x-jet-label for="title" value="{{ __('Title') }}" />
                                <x-jet-input name="title" id="title" type="text" class="mt-1 block w-full" autocomplete="title" value="{{ $method->title }}" />
                                <x-jet-input-error for="title" class="mt-2" />
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