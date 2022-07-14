<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Methods List
            <a href="{{ route('method.create') }}" class="pl-2 pr-2 text-sm text-green-500"><i class="fa fa-file"></i> Create</a>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
			
			{{-- Search  --}}
			<div class="p-6">
				<form action="{{ route('method.index') }}" method="GET" role="search">
					<x-jet-input id="search" class="block mt-1 w-full" type="search" name="search" :value="old('search')" autofocus placeholder="Search Database..."/>
				</form>
			</div>
         	<!-- component -->
			 <div class="flex flex-col">
                <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                        <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
								{{-- https://tailwind-elements.com/docs/standard/components/tables/ --}}
								<table class="min-w-full divide-y divide-gray-200 w-full">
									<thead>
										<tr>
											<th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
												ID
											</th>
                                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
												Title
											</th>
                                            <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
												Slug
											</th>
											<th scope="col" width="200" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
											</th>
										</tr>
									</thead>
									<tbody class="bg-white divide-y divide-gray-200">
										@foreach ($methods as $method)
										<tr class="border-b">
											<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
												{{ $method->id }}
											</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
												{{ $method->title }}
											</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
												{{ $method->slug }}
											</td>
											<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
												<a class="text-sm text-indigo-600 hover:text-gray-900" href="{{ route('method.show',$method) }}">
													{{ __('View') }}
												</a>
												<a class="text-sm text-green-600 hover:text-gray-900" href="{{ route('method.edit',$method) }}">
													{{ __('Edit') }}
												</a>
												<form class="inline-block" action="{{ route('method.destroy', $method) }}" method="POST" onsubmit="return confirm('Are you sure?');">
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

							

						</div>
					</div>
				</div>
       
            </div>

			{{-- Pagination --}}
			<div class="flex flex-col bg-gray-50 border-t items-center py-2 md:flex-row md:justify-between">
				{{ $methods->links() }}
				{{-- https://laravel.com/docs/9.x/pagination#paginator-instance-methods --}}
				{{-- <span class="text-xs md:text-sm text-gray-900">
					Showing 
					{{ $methods->firstItem() }}
					to 
					{{ $methods->lastItem() }}
					of 
					{{ $methods->total() }}
					Entries
				</span> --}}
				{{-- <div class="inline-flex mt-2 xs:mt-0">
					<button
						class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
						<a href="{{ $methods->previousPageUrl() }}">Prev</a>
					</button>
					&nbsp; &nbsp;
					<button
						class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition">
						<a href="{{ $methods->nextPageUrl() }}">Next</a>
					</button>
				</div> --}}
			</div>

        </div>
    </div>

 
</x-app-layout>
