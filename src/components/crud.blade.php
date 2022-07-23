<a class="text-sm text-orange-400 hover:text-orange-600" href="{{$show}}">
    {{-- <i class="fa fa-binoculars fa-fw"></i> --}}
    👁️‍🗨️
</a>
<a class="text-sm text-indigo-400 hover:text-indigo-600" href="{{$edit}}">
    {{-- <i class="fa fa-edit fa-fw"></i> --}}
    ✏️
</a>
<form class="inline-block" action="{{$destroy}}" method="POST" onsubmit="return confirm('Are you sure?');">
    @csrf
    @method('DELETE')
    {{-- <input type="hidden" name="_method" value="DELETE"> --}}
    {{-- <input type="hidden" name="_token" value="{{ csrf_token() }}"> --}}
    <button type="submit" class=" text-red-400 hover:text-red-600">❌</button>
    {{-- <button type="submit" class=" text-red-400 hover:text-red-600"><i class="fa fa-trash fa-fw"></i></button> --}}
    {{-- <input type="submit" class="text-red-400 hover:text-red-600 mb-2 mr-2" value="Delete"> --}}
</form>