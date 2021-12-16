<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Role => {{ $role->title }}
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
                <form action="{{ route('role.update', $role->id ) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="p-4 bg-white border-b border-gray-200">
                        Role & Permissions # {{ $role->title }}
                        <input type="hidden" name="id" value="{{ $role->id }}">
                        </div>
                    <div class="pl-6 pr-6 pt-2 pb-2 bg-white border-b">
                    
                        <div class="flex-row w-full">
                
                            <div class="flex-col pt-1 pb-1">
                                <label for="" class="inline-block w-48 h-8">Role Title</label>
                                <input type="text" value="{{ $role->title }}" name="title" class="w-48 h-8 border-0">
                                @if ($errors->has('title')) <p class="error-text">{{ $errors->first('title') }}</p> @endif

                                <label for="" class="inline-block w-48 h-8">Description</label>
                                <input type="text" name="description" value="{{ $role->description }}" class="w-48 h-8">
                                @if ($errors->has('description')) <p class="error-text">{{ $errors->first('description') }}</p> @endif
                            </div>

                            
                            
                            <hr>
       
                            <div>
                                <span class="w-full h-6 text-sm cursor-pointer text-gray-500 border-l pl-2" onclick="checkBoxSelection(true)">Select All</span>
                                <span class="w-full h-6 text-sm cursor-pointer text-gray-500 border-l pl-2" onclick="checkBoxSelection(false)">Select None</span>
                            </div>

                            <div class="ml-2">
                                <span class="w-full h-6 text-sm text-blue-500">By Permissions</span>
    
                                <span class="w-full h-6 text-sm cursor-pointer text-gray-500 border-l pl-2" onclick="permissionSelection('create',true)">Create</span>
                                <span class="w-full h-6 text-sm cursor-pointer text-gray-500 border-l pl-2" onclick="permissionSelection('edit',true)">Edit</span>
                                <span class="w-full h-6 text-sm cursor-pointer text-gray-500 border-l pl-2" onclick="permissionSelection('update',true)">Update</span>
                                <span class="w-full h-6 text-sm cursor-pointer text-gray-500 border-l pl-2" onclick="permissionSelection('view',true)">View</span>
                                <span class="w-full h-6 text-sm cursor-pointer text-gray-500 border-l pl-2" onclick="permissionSelection('delete',true)">Delete</span>
                                <span class="w-full h-6 text-sm cursor-pointer text-gray-500 border-l pl-2" onclick="permissionSelection('list',true)">List</span>
                                <span class="w-full h-6 text-sm cursor-pointer text-gray-500 border-l pl-2" onclick="permissionSelection('approve',true)">Approve</span>
                                <span class="w-full h-6 text-sm cursor-pointer text-gray-500 border-l pl-2" onclick="permissionSelection('reject',true)">Reject</span>
                                <span class="w-full h-6 text-sm cursor-pointer text-gray-500 border-l pl-2" onclick="permissionSelection('cancel',true)">Cancel</span>
                                <span class="w-full h-6 text-sm cursor-pointer text-gray-500 border-l pl-2" onclick="permissionSelection('hold',true)">Hold</span>
                                <span class="w-full h-6 text-sm cursor-pointer text-gray-500 border-l pl-2" onclick="permissionSelection('tile',true)">Tile</span>
                            </div>

                            <div class="ml-2">
                                <span class="w-full h-6 text-sm text-blue-500">By Model</span>
                                @foreach ($models as $model)
                                    <span class="w-full h-6 text-sm cursor-pointer text-gray-500 border-l pl-2" onclick="modelSelection('{{ $model->model }}',true)">{{ $model->model }}</span>
                                @endforeach
                            </div>

                  
                        </div>

                        <div class="mt-4 mx-4 mb-2">
           
                            <table id="dataTable">
                                <thead>
                                    <tr>
                                        <th>Model</th>
                                        <th>Permissions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($models as $model)
                                    <tr>
                                        <td>{{ $model->model }}</td>
                                        <td>
                                        @foreach ($permissions as $perm)
                                            @if ($perm->model == $model->model)
                                                <input type="checkbox" name="permissions[]" id="permissions[]" value="{{$perm->id}}" data-method="{{ $perm->method }}" data-model="{{ $perm->model }}"
                                                {{-- Show Role Selected Permissions --}}
                                                @foreach ($role->permissions->all() as $i)
                                                    @if ($i->id == $perm->id)
                                                        checked
                                                    @endif
                                                @endforeach
                                                >
                                                <label for="permissions[]" class="mr-2 ml-2">{{ $perm->method }}</label>
                                                @endif
                                        @endforeach
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                 
                        <button id="submit" type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">Update Role</button>

                    </div>
                </form>
            </div>
        </div>
    </div>


    <x-slot name="script">
        <script>
            var table
            document.addEventListener('DOMContentLoaded',()=>{
                table = new Tabulator('#dataTable',{
                    columns:[ //set column definitions for imported table data
                        {title:"Model", sorter:"number"},
                        {title:"Permissions", formatter:"html"},
                    ],
                });
            })
            
            function checkBoxSelection(toggle)
            {
                var allInputs = document.querySelectorAll("input");
                for (var i = 0; i < allInputs.length ; i++)
                {
                    if (allInputs[i].type === "checkbox")
                    {
                        allInputs[i].checked = toggle;
                    }
                }
            }
            
            function permissionSelection(permission, toggle)
            {
                var allInputs = document.querySelectorAll("input");
                for (var i = 0; i < allInputs.length ; i++)
                {
                    if (allInputs[i].type === "checkbox" && allInputs[i].dataset.method == permission)
                    {
                        allInputs[i].checked = toggle;
                    }
                }
            }
            
            function modelSelection(model, toggle)
            {
                console.log(model);
                var allInputs = document.querySelectorAll("input");
                for (var i = 0; i < allInputs.length ; i++)
                {
                    if (allInputs[i].type === "checkbox" && allInputs[i].dataset.model == model)
                    {
                        allInputs[i].checked = toggle;
                    }
                }
            }
        </script>
    </x-slot>

</x-app-layout>