<?php

namespace App\Http\Controllers;

use App\Models\Topping;
use App\Models\Unit;
use Illuminate\Http\Request;
use Validator;
use Spatie\Permission\Models\Permission;

class ToppingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginate = 10; //Количество элементов отображаемых на одной странице
        $toppings = Topping::paginate($paginate); //Пагинация
        $units = Unit::all(); //Единицы измерения

        return view('toppings.index', compact('toppings', 'units', 'paginate'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sort(Request $request)
    {

        $paginate = $request->input('paginate');
        $column = $request->input('column');
        $sort = $request->input('sort');

        if ($sort == 'no-sort'){
            $toppings = Topping::query()->orderBy('id', 'ASC')->paginate($paginate);
        }
        else {
            $toppings = Topping::query()->orderBy($column, $sort)->paginate($paginate);
        }

        $view = view('toppings.records', compact('toppings'))->render();
        return response()->json(['view' => $view]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:toppings|max:255',
            'unit_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        Topping::create($request->all());


        $topping = Topping::latest()->take(1)->first();

        $records_number = Topping::count();

        $record = "<tr class='table-container record' id='record-" . $topping->id . "'>
    <td class='text-center'>" . $records_number . "</td>
    <td>" . $topping->name . "</td>
    <td>" . $topping->unit->getUnitName() . "</td>
    <td>" . $topping->created_at . "</td>"
            . (auth()->user()->can('manage records') ? "
    <td class='table-content'>
        <div class='table-buttons text-center'>
            <button type='button' data-number='" . $records_number . "' data-unit-id='" . $topping->unit->id . "' data-name='" . $topping->name . "' data-id='" . $topping->id . "' class='btn btn-primary update-record'><i class='fa-solid fa-pen'></i></button>
            <button class='btn btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' data-table='toppings' data-id='" . $topping->id . "'>
                <i class='fa-solid fa-trash'></i>
            </button>
        </div>
    </td>
    " : "")
            . "</tr>
<tr style='display:none;' id='update-record-" . $topping->id . "'></tr>";


        return response()->json(['record' => $record, 'record_id' => $records_number, 'success' => true]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Topping  $topping
     * @return \Illuminate\Http\Response
     */
    public function show(Topping $topping)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Topping  $topping
     * @return \Illuminate\Http\Response
     */
    public function edit(Topping $topping)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Topping  $topping
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'unit_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $topping = Topping::find($request->id);
        $topping->update($request->all());


        $records_number=  Topping::where('id', '<=', $request->id)->count();

        $record = "<tr class='table-container record' id='record-" . $topping->id . "'>
    <td class='text-center'>" . $records_number . "</td>
    <td>" . $topping->name . "</td>
    <td>" . $topping->unit->getUnitName() . "</td>
    <td>" . $topping->created_at . "</td>"
            . (auth()->user()->can('manage records') ? "
    <td class='table-content'>
        <div class='table-buttons text-center'>
            <button type='button' data-number='" . $records_number . "' data-unit-id='" . $topping->unit->id . "' data-name='" . $topping->name . "' data-id='" . $topping->id . "' class='btn btn-primary update-record'><i class='fa-solid fa-pen'></i></button>
            <button class='btn btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' data-table='toppings' data-id='" . $topping->id . "'>
                <i class='fa-solid fa-trash'></i>
            </button>
        </div>
    </td>
    " : "")
            . "</tr>
<tr style='display:none;' id='update-record-" . $topping->id . "'></tr>";
        return  response()->json(['record' => $record, 'record_id'=> $records_number, 'success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Topping  $topping
     * @return \Illuminate\Http\Response
     */
    public function destroy(Topping $topping)
    {
        $record_counts=  Topping::where('id', '<=', $topping->id)->count();
        $topping = Topping::findOrFail($topping->id);
        $topping->delete();

        return  response()->json(['record' => 'delete success','record_id'=> $record_counts ]);
    }
}
