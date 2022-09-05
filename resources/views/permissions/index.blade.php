<x-app-layout>

	<x-page-header>
		Permissions List
	</x-page-header>

	<x-content>
		{{-- Your Data Here --}}
		<div class="flex flex-row justify-between items-center px-2">
			{{-- Create button --}}
			{{-- <x-create-button route="{{ route('permission.create') }}">
				Permission
			</x-create-button> --}}
			<a href="{{ route('permission.create') }}" class="w-48 h-8 content-center ml-2 mt-1 items-center bg-green-400 rounded-md font-semibold text-white hover:bg-green-600">
				<i class="fa fa-file fa-fw text-white ml-2 mt-2"></i>
				New Permission
			</a>
			{{-- Search  --}}
			<div class="p-2">
				<form action="{{ route('permission.index') }}" method="GET" role="search">
					<input type="search" name="search" id="search" class="block mt-1 w-full rounded-md" value="{{ old('search') }}" autofocus placeholder="Search Database...">
				</form>
			</div>
			{{-- <x-search>
				{{ route('permission.index') }}
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
					@foreach ($permissions as $permission)
					<tr class="border-b">
						<x-td>{{ $permission->id }}</x-td>
						<x-td>{{ $permission->title }}</x-td>
						<x-td>{{ $permission->slug }}</x-td>
						<x-td>
							<x-crud 
								show="{{ route('permission.show',$permission)}}" 
								edit="{{ route('permission.edit',$permission)}}" 
								destroy="{{ route('permission.destroy',$permission)}}" 
							/>
						</x-td>
					</tr>
					@endforeach
				</tbody>
			</x-table>
	
			{{-- Pagination --}}
			{{-- <x-pagination> {{ $permissions->links() }}</x-pagination> --}}
			<div class="flex flex-col bg-gray-50 border-t items-center px-4 py-2 mt-1 md:flex-row md:justify-between">
				{{ $permissions->links() }}
			</div>

	</x-content>
 
</x-app-layout>

