<x-app-layout>
	
    <x-page-header>
		Roles
	</x-page-header>

	<x-content>
		{{-- Your Data Here --}}
		<div class="flex flex-row justify-between items-center px-2">
			{{-- Create button --}}
			{{-- <x-create-button route="{{ route('role.create') }}">
				Role
			</x-create-button> --}}
			<a href="{{ route('role.create') }}" class="w-48 h-8 content-center ml-2 mt-1 items-center bg-green-400 rounded-md font-semibold text-white hover:bg-green-600">
				<i class="fa fa-file fa-fw text-white ml-2 mt-2"></i>
				New Role
			</a>
			{{-- Search  --}}
			<div class="p-2">
				<form action="{{ route('role.index') }}" method="GET" role="search">
					<input type="search" name="search" id="search" class="block mt-1 w-full rounded-md" value="{{ old('search') }}" autofocus placeholder="Search Database...">
				</form>
			</div>
			{{-- <x-search>
				{{ route('role.index') }}
			</x-search> --}}
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
		{{-- <x-pagination> {{ $roles->links() }}</x-pagination> --}}
		<div class="flex flex-col bg-gray-50 border-t items-center px-4 py-2 mt-1 md:flex-row md:justify-between">
			{{ $roles->links() }}
		</div>

	</x-content>
 
</x-app-layout>
