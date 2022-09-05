<x-app-layout>

	<x-page-header>
		Methods List
	</x-page-header>

	<x-content>
		{{-- Your Data Here --}}
		<div class="flex flex-row justify-between items-center px-2">
			{{-- Create button --}}
			{{-- <x-create-button route="{{ route('method.create') }}">
				Method
			</x-create-button> --}}
			{{-- Search  --}}
			<a href="{{ route('method.create') }}" class="w-48 h-8 content-center ml-2 mt-1 items-center bg-green-400 rounded-md font-semibold text-white hover:bg-green-600">
				<i class="fa fa-file fa-fw text-white ml-2 mt-2"></i>
				New Method
			</a>
			<div class="p-2">
				<form action="{{ route('method.index') }}" method="GET" role="search">
					<input type="search" name="search" id="search" class="block mt-1 w-full rounded-md" value="{{ old('search') }}" autofocus placeholder="Search Database...">
				</form>
			</div>
			{{-- <x-search>
				{{ route('method.index') }}
			</x-search> --}}
		</div>

			<!-- component -->
			<x-table>
				<thead>
					<tr>
						<x-th>ID</x-th>
						<x-th>Title</x-th>
						<x-th>Slug</x-th>
						<x-th>Actions</x-th>
					</tr>
				</thead>
				<tbody class="bg-white divide-y divide-gray-200">
					@foreach ($methods as $method)
					<tr class="border-b">
						<x-td>{{ $method->id }}</x-td>
						<x-td>{{ $method->title }}</x-td>
						<x-td>{{ $method->slug }}</x-td>
						<x-td>
							<x-crud 
								show="{{ route('method.show',$method)}}" 
								edit="{{ route('method.edit',$method)}}" 
								destroy="{{ route('method.destroy',$method)}}" 
							/>
						</x-td>
					</tr>
					@endforeach
				</tbody>
			</x-table>
	
			{{-- Pagination --}}
			{{-- <x-pagination> {{ $methods->links() }}</x-pagination> --}}
			<div class="flex flex-col bg-gray-50 border-t items-center px-4 py-2 mt-1 md:flex-row md:justify-between">
				{{ $methods->links() }}
			</div>

	</x-content>
 
</x-app-layout>
