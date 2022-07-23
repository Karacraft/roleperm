<div class="flex flex-col">
    <div class="w-full">
        <div class="py-2 px-4">
            <div class="shadow overflow-hidden border-b border-gray-200 rounded-lg">
                {{-- https://tailwind-elements.com/docs/standard/components/tables/ --}}
                <table class="min-w-full divide-y divide-gray-500">

                    {{ $slot }}

                </table>
            </div>
        </div>
    </div>
</div>