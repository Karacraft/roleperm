<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Role') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                {{-- Update Role --}}
                <form action="{{ route('role.update',$role) }}" method="post">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="role" value="{{ $role }}">
                    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                        <!-- Title -->
                        <div class="col-span-6 sm:col-span-4">
                            <x-label for="title" value="{{ __('Title') }}" />
                            <x-input name="title" id="title" type="text" class="mt-1 block w-full" autocomplete="title" value="{{ $role->title }}" />
                            <x-input-error for="title" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="col-span-6 sm:col-span-4">
                            <x-label for="description" value="{{ __('Description') }}" />
                            <x-input name="description" id="description" type="text" class="mt-1 block w-full" autocomplete="description" value="{{ $role->description }}" />
                            <x-input-error for="description" class="mt-2" />
                        </div>
                        {{-- Submit Button --}}
                        <x-button class="mt-4" name="updateRole">
                            {{ __('Update') }}
                        </x-button>
    
                    </div>
                </form>
                {{-- Role Permissions --}}
                <hr>
                <form action="{{ route('role.update',$role) }}" method="post">
                    @csrf
                    @method('PUT')
                    
                    <input type="hidden" name="role" value="{{ $role }}">
                    <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                        <span class="uppercase font-bold m-4">Access Rights</span>
                        
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                            
                            {{-- https://tailwind-elements.com/docs/standard/components/tables/ --}}
                            <table class="min-w-full divide-y divide-gray-200 w-full">
                                {{-- <thead>
                                    <tr>
                                        <th scope="col" width="50" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Model
                                        </th>
                                        @foreach ($methods as $method)
                                        <th scope="col" width="50" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            {{ $method->title }}
                                        </th>
                                        @endforeach
                                    </tr>
                                </thead> --}}
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @for ($i = 0 ; $i < count($models); $i++)
                                    <tr class="border-b">
                                        <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900 font-bold">
                                            {{ $models[$i] }}
                                        </td>
                                        @foreach ($permissions->where('model',$models[$i])->sortBy('id',SORT_NATURAL) as $item)
                                            <td class="px-2 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if ($rolePermissions->where('id',$item->id)->first())
                                                    {{-- {{ $item->id }} --}}
                                                    <input type="checkbox" name="permissions[]" id="" value="{{ $item->id }}" checked>
                                                @else 
                                                    <input type="checkbox" name="permissions[]" id="" value="{{ $item->id }}">
                                                @endif
                                                <label for="">{{ $item->method }}</label>
                                            </td>
                                        @endforeach
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        
                        {{-- Submit Button --}}
                        <x-button class="mt-4" name="updatePermissions">
                            {{ __('Update Permissions') }}
                        </x-button>

                    </div>
    
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
