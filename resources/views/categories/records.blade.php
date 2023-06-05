@foreach ($categories as $category)
    @php $records_number=  \App\Models\Category::where('id', '<=', $category->id)->count()@endphp

    <tr class="table-container" id="record-{{$category->id}}">
        <td class="text-center">{{ $records_number }} </td>
        <td><img width="200px" class="img rounded" src="{{asset('storage/files/' . $category->image)}}" alt=""></td>
        <td>{{ $category->name }}</td>
        <td>
            @if($category->is_publish == 1)
                Опубликовано
            @else
                Не опубликовано
            @endif
        </td>
        <td>{{ $category->created_at }}</td>
        @if (auth()->user()->can('manage records'))
            <td class="table-content" id="record-btn-{{$category->id}}">
                <div class="table-buttons text-center">
                    <button type="button" data-publish="{{ $category->is_publish }}" data-number="{{ $records_number }}"  data-name="{{ $category->name }}" data-id="{{$category->id}}" class="btn btn-primary update-record"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-table="categories" data-id="{{ $category->id }}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </td>
        @endif
    </tr>
    <tr class="d-none" id="update-record-{{$category->id}}"></tr>

@endforeach
<tr id="new-record"></tr>

@if($categories->count() == 0) {{-- Записи отсутствуют --}}
<tr id="no-records">
    <td colspan="12" align="center" class="h-100 align-items-center">
        <i class="fa-solid fa-file-circle-xmark records-missing-icon"></i>
        <p>Записи отсутствуют</p>
    </td>
</tr>
@endif
