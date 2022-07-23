<div class="p-2">
    <form action="{{ $slot }}" method="GET" role="search">
        <input type="search" name="search" id="search" class="block mt-1 w-full rounded-md" value="{{ old('search') }}" autofocus placeholder="Search Database...">
    </form>
</div>