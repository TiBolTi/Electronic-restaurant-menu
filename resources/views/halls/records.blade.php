@foreach ($halls as $hall)
    @php $records_number=  \App\Models\Hall::where('id', '<=', $hall->id)->count()@endphp

    <tr class="table-container" id="record-{{$hall->id}}">
        <td class="text-center">{{ $records_number }} </td>
        <td>{{ $hall->name }} зал</td>
        <td><b>{{ $hall->number_of_tables}}</b> столиков</td>
        <td>{{ $hall->created_at }}</td>
        @if (auth()->user()->can('manage records'))
            <td class="table-content" id="record-btn-{{$hall->id}}">
                <div class="table-buttons text-center">
                    <button type="button" data-number="{{ $records_number }}" data-number_of_tables="{{ $hall->number_of_tables}}" data-name="{{ $hall->name }}" data-id="{{$hall->id}}" class="btn btn-primary update-record"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-table="halls" data-id="{{ $hall->id }}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </td>
        @endif
    </tr>
    <tr class="d-none" id="update-record-{{$hall->id}}"></tr>

@endforeach
<tr id="new-record"></tr>

@if($halls->count() == 0) {{-- Записи отсутствуют --}}
<tr id="no-records">
    <td colspan="12" align="center" class="h-100 align-items-center">
        <i class="fa-solid fa-file-circle-xmark records-missing-icon"></i>
        <p>Записи отсутствуют</p>
    </td>
</tr>
@endif
