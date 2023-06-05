<?php

namespace App\Http\Controllers;

use App\Models\Hall;
use Illuminate\Http\Request;
use Validator;

class HallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paginate = 15; //Количество элементов отображаемых на одной странице
        $halls = Hall::paginate($paginate); //Пагинация

        return view('halls.index', compact('halls', 'paginate'));
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
            $halls = Hall::query()->orderBy('id', 'ASC')->paginate($paginate);
        }
        else {
            $halls = Hall::query()->orderBy($column, $sort)->paginate($paginate);
        }

        $view = view('halls.records', compact('halls'))->render();
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
            'name' => 'required|unique:halls|max:255',
            'number_of_tables' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        Hall::create($request->all());


        $hall = Hall::latest()->take(1)->first();

        $records_number = Hall::count();

        $record = "<tr class='table-container record' id='record-" . $hall->id . "'>
    <td class='text-center'>" . $records_number . "</td>
    <td>" . $hall->name . "</td>
    <td>" . $hall->number_of_tables. "</td>
    <td>" . $hall->created_at . "</td>"
            . (auth()->user()->can('manage records') ? "
    <td class='table-content'>
        <div class='table-buttons text-center'>
            <button type='button' data-number='" . $records_number . "' data-number_of_tables='" . $hall->number_of_tables . "' data-name='" . $hall->name . "' data-id='" . $hall->id . "' class='btn btn-primary update-record'><i class='fa-solid fa-pen'></i></button>
            <button class='btn btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' data-table='halls' data-id='" . $hall->id . "'>
                <i class='fa-solid fa-trash'></i>
            </button>
        </div>
    </td>
    " : "")
            . "</tr>
<tr style='display:none;' id='update-record-" . $hall->id . "'></tr>";


        return response()->json(['record' => $record, 'record_id' => $records_number, 'success' => true]);
    }


    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Hall  $hall
     * @return \Illuminate\Http\Response
     */
    public function show(Hall $hall)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Hall  $hall
     * @return \Illuminate\Http\Response
     */
    public function edit(Hall $hall)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Hall  $hall
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'number_of_tables' => 'required|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $hall = Hall::find($request->id);
        $hall->update($request->all());


        $records_number=  Hall::where('id', '<=', $request->id)->count();

        $record = "<tr class='table-container record' id='record-" . $hall->id . "'>
    <td class='text-center'>" . $records_number . "</td>
    <td>" . $hall->name . "</td>
    <td>" . $hall->number_of_tables. "</td>
    <td>" . $hall->created_at . "</td>"
            . (auth()->user()->can('manage records') ? "
    <td class='table-content'>
        <div class='table-buttons text-center'>
            <button type='button' data-number='" . $records_number . "' data-number_of_tables='" . $hall->number_of_tables . "' data-name='" . $hall->name . "' data-id='" . $hall->id . "' class='btn btn-primary update-record'><i class='fa-solid fa-pen'></i></button>
            <button class='btn btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' data-table='halls' data-id='" . $hall->id . "'>
                <i class='fa-solid fa-trash'></i>
            </button>
        </div>
    </td>
    " : "")
            . "</tr>
<tr style='display:none;' id='update-record-" . $hall->id . "'></tr>";
        return  response()->json(['record' => $record, 'record_id'=> $records_number, 'success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Hall  $hall
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hall $hall)
    {
        $record_counts=  Hall::where('id', '<=', $hall->id)->count();
        $hall = Hall::findOrFail($hall->id);
        $hall->delete();

        return  response()->json(['record' => 'delete success','record_id'=> $record_counts ]);
    }
}
