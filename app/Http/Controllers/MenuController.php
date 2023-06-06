<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Food;
use App\Models\Topping;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $paginate = 15; //Количество элементов отображаемых на одной странице
        $foods = Food::paginate($paginate);
        $categories = Category::all();
        $toppings = Topping::all();

        return view('menu.index', compact('foods','categories', 'toppings', 'paginate'));
    }

    public function sort(Request $request)
    {
        $vegan = $request->input('vegan');
        $special = $request->input('special');


        if ($special == 1 && $vegan == 1) {
            $foods = Food::where('is_vegan', 1)
                ->orWhere('is_special', 1)
                ->get();
        }
        elseif ($vegan == 1) {
            $foods = Food::where('is_vegan', 1)->get();

        }
        elseif ($special == 1) {
            $foods = Food::where('is_special', 1)->get();
        }

        else {
            $foods = Food::all();
        }



        $toppings = Topping::all();
        $category = $request->input('category');


        $view = view('menu.food_list', compact('foods', 'toppings', 'category'))->render();
        return response()->json(['view' => $view]);
    }
    public function search(Request $request)
    {
        $search = $request->input('search');

        $foods = Food::where('name', 'LIKE', "%$search%")->get();

        $toppings = Topping::all();


        $view = view('menu.search_result', compact('foods', 'toppings'))->render();
        return response()->json(['view' => $view]);
    }

}
