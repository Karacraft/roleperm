<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Permissions List
            <a href="{{ route('permission.create') }}" class="pl-2 pr-2 text-sm text-green-500"><i class="fa fa-file"></i> Create</a>
        </h2>
    </x-slot>

	<div  class="grid grid-cols-1 py-2">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
			{{-- <div class="mx-auto pull-right"> --}}
			<form action="{{ route('permission.index') }}" method="GET" role="search">
                <x-jet-input id="search" class="block mt-1 w-full" type="search" name="search" :value="old('search')" autofocus placeholder="Search"/>
			</form>
			{{-- </div> --}}
		</div>


		<div class="py-2">
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
													<th scope="col" width="50" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
														Method
													</th>
													<th scope="col" width="50" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
														Model
													</th>
													<th scope="col" width="200" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
														Actions
													</th>
												</tr>
											</thead>
											<tbody class="bg-white divide-y divide-gray-200">
												@foreach ($permissions as $permission)
												<tr class="border-b">
													<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
														{{ $permission->id }}
													</td>
													<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
														{{ $permission->title }}
													</td>
													<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
														{{ $permission->slug }}
													</td>
												
													<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
														{{ $permission->method }}
													</td>
												
													<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
														{{ $permission->model }}
													</td>
												
													<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
														<a class="text-sm text-indigo-600 hover:text-gray-900" href="{{ route('permission.show',$permission) }}">
															{{ __('View') }}
														</a>
														<a class="text-sm text-green-600 hover:text-gray-900" href="{{ route('permission.edit',$permission) }}">
															{{ __('Edit') }}
														</a>
														<form class="inline-block" action="{{ route('permission.destroy', $permission) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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
											{{ $permissions->firstItem() }}
											to 
											{{ $permissions->lastItem() }}
											of 
											{{ $permissions->total() }}
											Entries
										</span>
										<div class="inline-flex mt-2 xs:mt-0">
											<button
												class="text-sm text-indigo-50 transition duration-150 hover:bg-indigo-500 bg-indigo-600 font-semibold py-2 px-4 rounded-l">
												<a href="{{ $permissions->previousPageUrl() }}">Prev</a>
											</button>
											&nbsp; &nbsp;
											<button
												class="text-sm text-indigo-50 transition duration-150 hover:bg-indigo-500 bg-indigo-600 font-semibold py-2 px-4 rounded-r">
												<a href="{{ $permissions->nextPageUrl() }}">Next</a>
											</button>
										</div>
									</div>

								</div>
							</div>
						</div>
					</div>

				</div>

			</div>
		</div>

	</div>

 
</x-app-layout>
