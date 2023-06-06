<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\ImageUploader;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends Controller
{

    public function index()
    {
        $paginate = 15; //Количество элементов отображаемых на одной странице
        $categories = Category::paginate($paginate); //Пагинация

        return view('categories.index', compact('categories',  'paginate'));
    }

    public function sort(Request $request)
    {

        $paginate = $request->input('paginate');
        $column = $request->input('column');
        $sort = $request->input('sort');

        if ($sort == 'no-sort'){
            $categories = Category::query()->orderBy('id', 'ASC')->paginate($paginate);
        }
        else {
            $categories = Category::query()->orderBy($column, $sort)->paginate($paginate);
        }

        $view = view('categories.records', compact('categories'))->render();
        return response()->json(['view' => $view]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:categories|max:255',
            'image' => 'file',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $input_name = $request->input('name');
        $input_value = $request->input('is_publish');
        $converted_value = boolval($input_value);

        if ($request->has('image')) {
            $image = ImageUploader::upload($request->image, 'category', 'image');
        }

        $new_category = [
            'name' => $input_name,
            'is_publish' => $converted_value,
            'image' => $image,
        ];

        Category::create($new_category);
        $category = Category::latest()->take(1)->first();

        $records_number = Category::count();

        $record = "<tr class='table-container record' id='record-" . $category->id . "'>
    <td class='text-center'>" . $records_number . "</td>
    <td><img width='200px' class='img rounded' src='".asset('storage/files/' . $category->image)."' alt=''></td>
    <td>" . $category->name . "</td>
     <td>" . ($category->is_publish == 1 ? 'Опубликовано' : 'Не опубликовано') . "</td>
    <td>" . $category->created_at . "</td>"
            . (auth()->user()->can('manage records') ? "
    <td class='table-content'>
        <div class='table-buttons text-center'>
            <button type='button' data-number='" . $records_number . "'  data-name='" . $category->name . "' data-id='" . $category->id . "' class='btn btn-primary update-record'><i class='fa-solid fa-pen'></i></button>
            <button class='btn btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' data-table='categories' data-id='" . $category->id . "'>
                <i class='fa-solid fa-trash'></i>
            </button>
        </div>
    </td>
    " : "")
            . "</tr>
<tr style='display:none;' id='update-record-" . $category->id . "'></tr>";


        return response()->json(['record' => $record, 'record_id' => $records_number, 'success' => true]);
    }

    public function update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',

        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $category = Category::find($request->id);

        $input_name = $request->input('name');
        $input_value = $request->input('is_publish');
        $converted_value = boolval($input_value);


        if ($request->input('image') !== 'undefined') {
            $image = ImageUploader::upload($request->image, 'category', 'image');
            $category->image = $image;
        }

        $category->name = $input_name;

        $category->is_publish = $converted_value;

        $category->save();


        $records_number=  Category::where('id', '<=', $request->id)->count();

        $record = "<tr class='table-container record' id='record-" . $category->id . "'>
    <td class='text-center'>" . $records_number . "</td>
    <td><img width='200px' class='img rounded' src='".asset('storage/files/' . $category->image)."' alt=''></td>
    <td>" . $category->name . "</td>
     <td>" . ($category->is_publish == 1 ? 'Опубликовано' : 'Не опубликовано') . "</td>
    <td>" . $category->created_at . "</td>"
            . (auth()->user()->can('manage records') ? "
    <td class='table-content'>
        <div class='table-buttons text-center'>
            <button type='button' data-number='" . $records_number . "'  data-name='" . $category->name . "' data-id='" . $category->id . "' class='btn btn-primary update-record'><i class='fa-solid fa-pen'></i></button>
            <button class='btn btn btn-danger' data-bs-toggle='modal' data-bs-target='#deleteModal' data-table='categories' data-id='" . $category->id . "'>
                <i class='fa-solid fa-trash'></i>
            </button>
        </div>
    </td>
    " : "")
            . "</tr>
<tr style='display:none;' id='update-record-" . $category->id . "'></tr>";
        return  response()->json(['record' => $record, 'record_id'=> $records_number, 'success' => true]);
    }

    public function destroy(Category $category)
    {
        $record_counts=  Category::where('id', '<=', $category->id)->count();
        $category = Category::findOrFail($category->id);
        $category->delete();

        return  response()->json(['record' => 'delete success','record_id'=> $record_counts ]);
    }
}
