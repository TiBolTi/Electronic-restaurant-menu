<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index()
    {
        $foods = Food::all();
        $orders = Order::orderBy('id', 'desc')->paginate(30);
        return view('orders.index', compact('foods', 'orders'));
    }


    public function orderHistory()
    {
        $foods = Food::all();
        $orders = Order::orderBy('id', 'desc')->paginate(30);
        return view('orders.orders_history', compact('foods', 'orders'));
    }

    public function quantityChange(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->food()->updateExistingPivot($request->food_id, ['is_completed' => $request->is_completed, 'quantity_complete' => $request->quantity_complete]);
        return response()->json(['record' => 'success']);
    }

    public function orderComplete(Request $request)
    {

        $order = Order::find($request->id);
        $order->update(['order_status' => 'Выполнено']);
        $order->save();
        return response()->json(['record' => 'success']);
    }


}
