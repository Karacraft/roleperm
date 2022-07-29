<x-app-layout>
	
    <x-page-header>
		Roles
	</x-page-header>

	<x-content>
		{{-- Your Data Here --}}
		<div class="flex flex-row justify-between items-center px-2">
			{{-- Create button --}}
			<x-create-button route="{{ route('role.create') }}">
				Role
			</x-create-button>
			{{-- Search  --}}
			<x-search>
				{{ route('role.index') }}
			</x-search>
		</div>

		<!-- component -->
		<x-table>
			<thead>
				<tr>
					<x-th>ID</x-th>
					<x-th>Title</x-th>
					<x-th>Slug</x-th>
					<x-th>Description</x-th>
					<x-th>Permissions</x-th>
					<x-th>Actions</x-th>
				</tr>
			</thead>
			<tbody class="bg-white divide-y divide-gray-200">
				@foreach ($roles as $role)
				<tr class="border-b">
					<x-td>{{ $role->id }}</x-td>
					<x-td>{{ $role->title }}</x-td>
					<x-td>{{ $role->slug }}</x-td>
					<x-td>{{ $role->description }}</x-td>
					<x-td>{{ count($role->permissions) }}</x-td>
					<x-td>
						<x-crud 
							show="{{ route('role.show',$role)}}" 
							edit="{{ route('role.edit',$role)}}" 
							destroy="{{ route('role.destroy',$role)}}" 
						/>
					</x-td>
				</tr>
				@endforeach
			</tbody>
		</x-table>

		{{-- Pagination --}}
		<x-pagination> {{ $roles->links() }}</x-pagination>

	</x-content>
 
</x-app-layout>
