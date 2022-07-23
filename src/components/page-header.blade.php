{{-- <header class="bg-white shadow-lg rounded-lg m-4">
    <div class="py-4 lg:px-4">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $slot }}
        </h2>
    </div>
</header> --}}

<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{$slot}} List
    </h2>
</x-slot>