<x-app-layout>

	<x-page-header>
		Permissions List
	</x-page-header>

	<x-content>
		{{-- Your Data Here --}}
		<div class="flex flex-row justify-between items-center px-2">
			{{-- Create button --}}
			<x-create-button route="{{ route('permission.create') }}">
				Permission
			</x-create-button>
			{{-- Search  --}}
			<x-search>
				{{ route('permission.index') }}
			</x-search>
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
			<x-pagination> {{ $permissions->links() }}</x-pagination>


	</x-content>
 
</x-app-layout>

