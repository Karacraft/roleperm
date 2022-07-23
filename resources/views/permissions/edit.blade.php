<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit permission') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <form action="{{ route('permission.update',$permission) }}" method="post">
                        @csrf
                        @method('PUT')
                        
                        <input type="hidden" name="permission" value="{{ $permission }}">
                        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                            <!-- Title -->
                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="title" value="{{ __('Title') }}" />
                                <x-input name="title" id="title" type="text" class="mt-1 block w-full" autocomplete="title" value="{{ $permission->title }}" />
                                <x-input-error for="title" class="mt-2" />
                                {{-- @if ($errors->has('title')) <p class="text-red-500 mt-2">{{ $errors->first('title') }}</p>@endif --}}
                            </div>

                            <!-- Description -->
                            <div class="col-span-6 sm:col-span-4">
                                <x-label for="description" value="{{ __('Description') }}" />
                                <x-input name="description" id="description" type="text" class="mt-1 block w-full" 
                                autocomplete="description" value="{{ $permission->description }}" />
                                <x-input-error for="description" class="mt-2" />
                                {{-- @if ($errors->has('description')) <p class="text-red-500 mt-2">{{ $errors->first('description') }}</p>@endif --}}
                            </div>
                            {{-- Submit Button --}}
                            <x-button class="mt-4">
                                {{ __('Submit') }}
                            </x-button>
        
                        </div>
                        
                    </form>

            </div>
        </div>
    </div>
</x-app-layout>
