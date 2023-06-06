@extends('layouts.app')

@section('content')

    @php
        $no_finished_orders_count = 0;
        foreach ($orders as $order) {
            $has_unfinished_food = false;
            foreach ($foods as $food) {
                $order_number = DB::table('food_orders')
                    ->where('order_id', $order->id)
                    ->where('food_id', $food->id)
                    ->where('is_completed', false)
                    ->count();

                if ($order_number > 0) {
                    $has_unfinished_food = true;
                    break;
                }
            }

            if ($has_unfinished_food && $order->order_status == 'Оплачено') {
                $no_finished_orders_count++;
            }
        }
    @endphp
    <h3 class="mb-5 mt-3">История заказов</h3>
    <div id="alert"></div>
    <div class="d-flex justify-content-center w-100 mb-3">
        <a href="{{route('orders.index')}}">
            <button id="" type="button" class=" btn fw-bold me-2 btn-outline-dark">Новые заказы
                <span id="cart-count" class="badge rounded-4 bg-danger ">{{$no_finished_orders_count}} </span>
            </button>
        </a>
        <a href="{{route('orders.history')}}">
            <button  type="button" class="btn fw-bold ms-2 btn-outline-dark">История заказов</button>
        </a>
    </div>
    <table class="table table-bordered  table-hover">
        <thead>
        <tr>
            <th  class="buttons-column id-column">
                #
            </th>
            <th class="buttons-column ">
                Клиент
            </th>
            <th class="buttons-column ">
                Столик
            </th>
            <th class="buttons-column ">
                Стоимость
            </th>
            <th class="buttons-column">
                Количество
            </th>
            <th class="buttons-column">
                Оплачено
            </th>

            <th class="buttons-column">
                Дата заказа
            </th>
            <th class="buttons-column">
                Статус заказа
            </th>
            <th class="buttons-column" scope="col">Подробнее</th>
        </tr>
        </thead>
        <tbody  id="table">

        @foreach($orders as $order)

            @php $order_num=  \App\Models\order::where('id', '<=', $order->id)->count();
             $food_count = DB::table('food_orders')
                ->where('order_id', $order->id)

                ->count();
            @endphp

            <tr class="table-container" id="ordered-food-">
                <td class="id-column fw-bold" >Заказ №{{$order_num}}</td>

                <td>{{$order->user->name}}</td>
                <td>{{$order->hall->name}} зал<br>{{$order->table_number}} столик</td>
                <td>{{$order->total_price}} сом</td>
                <td>
                    <div class="d-flex flex-row align-items-center ">
                        <h6 class="m-3 bg-black">{{$food_count}} блюдо</h6>
                    </div>
                </td>
                <td>{{$order->payment_sum}} сом</td>
                <td>{{$order->created_at}}</td>
                <td>
                    @if($order->order_status == 'В ожидании')
                        <div class="btn btn-secondary fw-bold w-100">
                            <i class="fa-solid fa-clock-rotate-left"></i> {{$order->order_status}}
                        </div>
                    @endif
                    @if($order->order_status == 'Выполнено')
                        <div class="btn btn-success fw-bold w-100">
                            <i class="fa-solid fa-check"></i> {{$order->order_status}}
                        </div>
                    @endif
                    @if($order->order_status == 'Ошибка')
                        <div class="btn btn-danger fw-bold w-100">
                            <i class="fa-solid fa-xmark"></i> {{$order->order_status}}
                        </div>
                    @endif
                </td>

                <td class="text-center">
                    <div class="table-buttons text-center">
                        <button type="button" id="show-more-{{$order->id}}" data-id="{{$order->id}}"  class="fw-bold show-more btn btn-secondary order-delete">
                            <i class="fa-solid fa-angle-up"></i> Подробнее
                        </button>
                        <button type="button" id="show-less-{{$order->id}}" data-id="{{$order->id}}" class="fw-bold show-less btn btn-secondary order-delete d-none">
                            <i class="fa-solid fa-angle-down"></i> Скрыть
                        </button>
                    </div>
                </td>
            </tr>


        <tbody id="show-order-more-{{$order->id}}" class="d-none">
        <tr class="bg-dark" style="color: #fff">

            <td class="buttons-column" colspan="2">
                <b>Блюдо</b>
            </td>
            <td class="buttons-column" colspan="1">
                <b>Цена</b>
            </td>
            <td class="buttons-column" colspan="2">
                <b>Количество порций</b>
            </td>
            <td class="buttons-column" colspan="2">
                <b>Приготовлено</b>
            </td>
            <td class="buttons-column" colspan="1">
                <b>Сумма</b>
            </td>
            <td class="buttons-column" >
                <b>Статус</b>
            </td>




        @foreach($order->food as $food)

            @php
                $food_price = $food->price;
                $food_quantity = $food->pivot->quantity;
                $total_sum = $food_price * $food_quantity;
            @endphp
            <tr class="table-container" id="ordered-food-">
                <td><img width="150px" src="{{asset('storage/files/' . $food->image)}}" class="rounded" alt="..."></td>
                <td>{{$food->name}}</td>
                <td colspan="1" id="food-price">{{$food->price}} сом</td>
                <td colspan="2">
                    <div class="d-flex flex-row align-items-center" >
                        <h6 id="food-quantity" class="m-3 bg-black" style="width: max-content;">{{$food->pivot->quantity}} порций</h6>
                    </div>
                </td>
                <td colspan="2">
                    <div class="d-flex flex-row align-items-center" >
                        <h6 id="food-quantity" class="m-3 bg-black" style="width: max-content;">{{$food->pivot->quantity_complete}} порций</h6>
                    </div>
                </td>
                <td colspan="1" id="foodTotalSum">{{$total_sum}} сом</td>
                <td>
                    @if($food->pivot->is_completed == false)
                        <div class="btn btn-secondary fw-bold w-100">
                            <i class="fa-solid fa-utensils"></i> Готовиться
                        </div>
                    @endif
                    @if($food->pivot->is_completed == true)
                        <div class="btn btn-success  fw-bold w-100">
                            <i class="fa-solid fa-check"></i> Приготовлено
                        </div>
                    @endif
                </td>
            </tr>

        @endforeach
        <tr><td colspan="12" class="p-4 border-0"></td>
        </tr>

        </tbody>
        @endforeach
        <tr class="d-none" id="no-records">
            <td colspan="12" align="center" class="h-100 align-items-center">
                <i class="fa-solid fa-store-slash records-missing-icon"></i>
                <p>Корзина пуста</p>
            </td>
        </tr>
        </tbody>
    </table>

    <div class="col-12">{{ $orders->links('vendor.pagination.bootstrap-4') }}</div>
@endsection
@push('scripts')
    <script>

        $('.show-more').click(function (e){
            e.preventDefault();

            var order = $(this)
            var id = order.data('id')

            $('#show-more-'+id).addClass('d-none')
            $('#show-less-'+id).removeClass('d-none')

            $('#show-order-more-'+id).removeClass('d-none')


        })
        $('.show-less').click( function (e){
            e.preventDefault();

            var order = $(this)
            var id = order.data('id')

            $('#show-more-'+id).removeClass('d-none')
            $('#show-less-'+id).addClass('d-none')

            $('#show-order-more-'+id).addClass('d-none')

        })

    </script>

@endpush
