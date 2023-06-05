<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $foods= Food::all();
        $orders = Order::orderBy('id', 'desc')->paginate(30);
        return view('orders.index', compact('foods', 'orders'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderHistory()
    {
        $foods= Food::all();
        $orders = Order::orderBy('id', 'desc')->paginate(30);
        return view('orders.orders_history', compact('foods', 'orders'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function quantityChange(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->food()->updateExistingPivot($request->food_id, ['is_completed' => $request->is_completed, 'quantity_complete' => $request->quantity_complete]);
        return  response()->json(['record' => 'success']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function orderComplete(Request $request)
    {

        $order = Order::find($request->id);
        $order->update(['order_status' => 'Выполнено']);
        $order->save();
        return  response()->json(['record' => 'success']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Order $order)
    {
        //
    }
}
