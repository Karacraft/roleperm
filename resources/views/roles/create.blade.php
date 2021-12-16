<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Creat Role
        </h2>
    </x-slot>

    <div>
        @if(count($errors)>0)
            @foreach ($errors->all() as $error)
                <div class="text-sm text-red-700">{{ $error }}</div>
            @endforeach
        @endif
    </div>
    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('role.store') }}" method="POST">
                    @csrf
                    <div class="p-4 bg-white border-b border-gray-200">
                        Role & Permissions
                    </div>
                    <div class="pl-6 pr-6 pt-2 pb-2 bg-white border-b">
                    
                        <div class="flex-row w-full">
                
                            <div class="flex-col pt-1 pb-1">
                                <label for="" class="inline-block w-48 h-8">Role Title</label>
                                <input type="text" value="{{ old('title') }}" name="title" class="w-48 h-8">
                                @if ($errors->has('title')) <p class="error-text">{{ $errors->first('title') }}</p> @endif

                                <label for="" class="inline-block w-48 h-8">Description</label>
                                <input type="text" name="description" value="{{ old('description') }}" class="w-48 h-8">
                                @if ($errors->has('description')) <p class="error-text">{{ $errors->first('description') }}</p> @endif
                            </div>
                            <hr>
  
                            {{-- Dyanmic Table for Items --}}   
                            <div class="flex py-4">
                                
                            </div>
                            
                        </div>
                 
                        <button id="submit" type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Save Role</button>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-slot name="script">
    </x-slot>

</x-app-layout>