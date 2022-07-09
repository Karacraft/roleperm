<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Roles List
            <a href="{{ route('role.create') }}" class="pl-2 pr-2 text-sm text-green-500"><i class="fa fa-file"></i> Create</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
			
         	<!-- component -->
			 <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
							{{-- https://tailwind-elements.com/docs/standard/components/tables/ --}}
							<table class="min-w-full divide-y divide-gray-200 w-full">
								<thead>
									<tr>
										<th scope="col" width="50" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
											ID
										</th>
										<th scope="col" width="50" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
											Title
										</th>
										<th scope="col" width="50" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
											Slug
										</th>
										<th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
											Description
										</th>
										<th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
											Permissions
										</th>
										<th scope="col" width="200" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
											Actions
										</th>
									</tr>
								</thead>
								<tbody class="bg-white divide-y divide-gray-200">
									@foreach ($roles as $role)
									<tr class="border-b">
										<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
											{{ $role->id }}
										</td>
										<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
											{{ $role->title }}
										</td>
										<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
											{{ $role->slug }}
										</td>
										<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
											{{ $role->description }}
										</td>
										<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
											{{ count($role->permissions) }}
										</td>
										<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
											<a class="text-sm text-indigo-600 hover:text-gray-900" href="{{ route('role.show',$role) }}">
												{{ __('View') }}
											</a>
											<a class="text-sm text-green-600 hover:text-gray-900" href="{{ route('role.edit',$role) }}">
												{{ __('Edit') }}
											</a>
											<form class="inline-block" action="{{ route('role.destroy', $role) }}" method="POST" onsubmit="return confirm('Are you sure?');">
												<input type="hidden" name="_method" value="DELETE">
												<input type="hidden" name="_token" value="{{ csrf_token() }}">
												<input type="submit" class="text-red-600 hover:text-red-900 mb-2 mr-2" value="Delete">
											</form>
										</td>
									</tr>
									@endforeach
								</tbody>
							</table>
						</div>

						<div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
							<span class="text-xs xs:text-sm text-gray-900">
								{{-- https://laravel.com/docs/9.x/pagination#paginator-instance-methods --}}
								Showing 
								{{ $roles->firstItem() }}
								to 
								{{ $roles->lastItem() }}
								of 
								{{ $roles->total() }}
								Entries
							</span>
							<div class="inline-flex mt-2 xs:mt-0">
								<button
									class="text-sm text-indigo-50 transition duration-150 hover:bg-indigo-500 bg-indigo-600 font-semibold py-2 px-4 rounded-l">
									<a href="{{ $roles->previousPageUrl() }}">Prev</a>
								</button>
								&nbsp; &nbsp;
								<button
									class="text-sm text-indigo-50 transition duration-150 hover:bg-indigo-500 bg-indigo-600 font-semibold py-2 px-4 rounded-r">
									<a href="{{ $roles->nextPageUrl() }}">Next</a>
								</button>
							</div>
						</div>

						</div>
					</div>
				</div>
       
            </div>

        </div>
    </div>

 
</x-app-layout>
