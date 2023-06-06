<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Food;
use App\Models\Topping;
use App\Services\ImageUploader;
use Illuminate\Http\Request;
use Validator;

class FoodController extends Controller
{
    public function index()
    {
        $paginate = 10; //Количество элементов отображаемых на одной странице
        $foods = Food::paginate($paginate); //Пагинация
        $toppings = Topping::all();
        $categories = Category::all();

        return view('food.index', compact('foods', 'toppings', 'categories',  'paginate'));
    }

    public function sort(Request $request)
    {

        $toppings = Topping::all();
        $categories = Category::all();

        $paginate = $request->input('paginate');
        $column = $request->input('column');
        $sort = $request->input('sort');

        if ($sort == 'no-sort'){
            $foods = Food::query()->orderBy('id', 'ASC')->paginate($paginate);
        }
        else {
            $foods = Food::query()->orderBy($column, $sort)->paginate($paginate);
        }

        $view = view('food.records', compact('foods', 'toppings', 'categories'))->render();
        return response()->json(['view' => $view]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:food|max:255',
            'description' => 'required',
            'price' => 'required',
            'image' => 'file',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }
        $input_name = $request->input('name');
        $input_desc = $request->input('description');
        $input_price = $request->input('price');

        $input_is_vegan = $request->input('is_vegan');
        $input_is_special = $request->input('is_special');
        $input_is_publish = $request->input('is_publish');

        $is_vegan = boolval($input_is_vegan);
        $is_special = boolval($input_is_special);
        $is_publish = boolval($input_is_publish);

        $categories = $request->input('categories');
        $toppings = $request->input('toppings');
        $quantity_toppings = $request->input('quantity_toppings');



        if ($request->has('image')) {
            $image = ImageUploader::upload($request->image, 'food', 'image');
        }

        $new_food = [
            'name' => $input_name,
            'description'=> $input_desc,
            'price'=> $input_price,
            'is_vegan' => $is_vegan,
            'is_special' => $is_special,
            'is_publish' => $is_publish,
            'image' => $image,
        ];

        $food = Food::create($new_food);



        if ($request->has('toppings')) {

            foreach ($toppings as $index => $toppingId) {
                $quantity = $quantity_toppings[$index];
                $food->topping()->attach($toppingId, ['quantity' => $quantity]);
            }
        }

            $food->category()->attach($categories);


        $records_number = Food::count();



        return response()->json(['record_id' => $records_number, 'success' => true]);
    }

    public function update(Request $request)
    {


        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:food,name,' . $request->input('id') . '|max:255',
            'description' => 'required',
            'price' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $input_name = $request->input('name');
        $input_desc = $request->input('description');
        $input_price = $request->input('price');

        $input_is_vegan = $request->input('is_vegan');
        $input_is_special = $request->input('is_special');
        $input_is_publish = $request->input('is_publish');

        $is_vegan = boolval($input_is_vegan);
        $is_special = boolval($input_is_special);
        $is_publish = boolval($input_is_publish);

        $categories = $request->input('categories');
        $toppings = $request->input('toppings');
        $quantity_toppings = $request->input('quantity_toppings');

        $food = Food::findOrFail($request->input('id'));
        $food->name = $input_name;
        $food->description = $input_desc;
        $food->price = $input_price;
        $food->is_vegan = $is_vegan;
        $food->is_special = $is_special;
        $food->is_publish = $is_publish;

        if ($request->input('image') !== 'undefined') {
            $image = ImageUploader::upload($request->image, 'food', 'image');
            $food->image = $image;
        }

        $food->save();

// Sync categories
        $food->category()->sync($categories);

// Sync toppings

        if ($request->has('toppings')) {
            $food->topping()->detach();
            foreach ($toppings as $index => $toppingId) {
                $quantity = $quantity_toppings[$index];
                $food->topping()->attach($toppingId, ['quantity' => $quantity]);
            }
        }
        $records_number = Food::where('id', '<=', $request->id)->count();;

        return response()->json(['record_id' => $records_number, 'success' => true]);
    }

    public function destroy(Food $food)
    {
        $record_counts=  Food::where('id', '<=', $food->id)->count();
        $food = Food::findOrFail($food->id);
        $food->topping()->detach();
        $food->delete();

        return  response()->json(['record' => 'delete success','record_id'=> $record_counts ]);
    }
}
