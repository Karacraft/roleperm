<x-app-layout>

	<x-page-header>
		Methods List
	</x-page-header>

	<x-content>
		{{-- Your Data Here --}}
		<div class="flex flex-row justify-between items-center px-2">
			{{-- Create button --}}
			<x-create-button route="{{ route('method.create') }}">
				Method
			</x-create-button>
			{{-- Search  --}}
			<x-search>
				{{ route('method.index') }}
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
			<x-pagination> {{ $methods->links() }}</x-pagination>


	</x-content>
 
</x-app-layout>
