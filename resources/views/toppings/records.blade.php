@foreach ($toppings as $topping)
    @php $records_number=  \App\Models\Topping::where('id', '<=', $topping->id)->count()@endphp

    <tr class="table-container" id="record-{{$topping->id}}">
        <td class="text-center">{{ $records_number }} </td>
        <td>{{ $topping->name }}</td>
        <td>{{ $topping->unit->getUnitName()}}</td>
        <td>{{ $topping->created_at }}</td>
        @if (auth()->user()->can('manage records'))
            <td class="table-content" id="record-btn-{{$topping->id}}">
                <div class="table-buttons text-center">
                    <button type="button" data-number="{{ $records_number }}" data-unit-id="{{$topping->unit->id}}" data-name="{{ $topping->name }}" data-id="{{$topping->id}}" class="btn btn-primary update-record"><i class="fa-solid fa-pen"></i></button>
                    <button class="btn btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-table="toppings" data-id="{{ $topping->id }}">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </div>
            </td>
        @endif
    </tr>
    <tr class="d-none" id="update-record-{{$topping->id}}"></tr>

@endforeach
<tr id="new-record"></tr>

@if($toppings->count() == 0) {{-- Записи отсутствуют --}}
<tr id="no-records">
    <td colspan="12" align="center" class="h-100 align-items-center">
        <i class="fa-solid fa-file-circle-xmark records-missing-icon"></i>
        <p>Записи отсутствуют</p>
    </td>
</tr>
@endif
