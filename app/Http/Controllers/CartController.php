<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Hall;
use App\Models\Order;
use Illuminate\Http\Request;
use Validator;

class CartController extends Controller
{
    public function index()
    {
        $halls= Hall::all();
        $order = Order::all();
        return view('cart.index', compact('halls', 'order'));
    }

    public function orderConfirm(Request $request)
    {

        $items = json_decode($request->items, true);
        $foods = json_decode($request->order, true);
        $validator = Validator::make($items[0], [

            'hall_id' => 'required|integer',
            'table_number' => 'required|integer',
            'card_number' => 'required|integer|min:16',
            'card_mount' => 'required|integer|min:2',
            'card_year' => 'required|integer|min:2',
            'card_ccv' => 'required|integer|min:3',
            'total_price' => 'integer',
            'tips' => 'integer',
            'payment_sum' => 'integer',
        ]);



        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()]);
        }

        $user_id = auth()->id();
        $items[0]['user_id'] = $user_id;
        $items[0]['order_status'] = 'В ожидании';


        if ($request->input('tips') === null) {
            $items[0]['tips'] = null;
        }
//        dd($items[0]);
        $order = Order::create($items[0]);

        array_shift($items);

        foreach ($foods as $food) {
            $ordered_food = [
                'quantity' => $food['quantity'],
                'price' => $food['price'],
                'is_completed' => false
            ];
            $order->food()->attach($food['id'],$ordered_food);
        }

        return response()->json(['success' => true]);
    }

    public function orderHistory()
    {
        $foods= Food::all();
        $orders = Order::orderBy('id', 'desc')->get();
        return view('cart.user_cart_history', compact('foods', 'orders'));
    }
}
