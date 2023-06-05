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

                if ($order_number > 0 &&  $order->order_status == 'В ожидании') {
                    $has_unfinished_food = true;

                    break;
                }
            }

            if ($has_unfinished_food) {
                $no_finished_orders_count++;
            }
        }
    @endphp
    <h3 class="mb-5 mt-3">Новые заказы</h3>
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
            <th class="buttons-column">
                Количество
            </th>
            <th class="buttons-column">
                Дата заказа
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

            @if($no_finished_orders_count && $order->order_status == 'В ожидании')
            <tr class="table-container" id="ordered-food-{{$order->id}}">
                <td class="id-column fw-bold" >Заказ №{{$order_num}}</td>

                <td>{{$order->user->name}}</td>
                <td>{{$order->hall->name}} зал<br>{{$order->table_number}} столик</td>
                <td>
                    <div class="d-flex flex-row align-items-center ">
                        <h6 class="m-3 bg-black">{{$food_count}} блюдо</h6>
                    </div>
                </td>
                <td>{{$order->created_at}}</td>


                <td class="text-center">
                    <div class="table-buttons text-center">
                        <button type="button" id="show-more-{{$order->id}}" data-id="{{$order->id}}"  class="fw-bold show-more btn btn-secondary">
                            <i class="fa-solid fa-angle-up"></i> Подробнее
                        </button>
                        <button type="button" id="show-less-{{$order->id}}" data-id="{{$order->id}}" class="fw-bold show-less btn btn-secondary d-none">
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

            <td class="buttons-column" colspan="2">
                <b>Заказано</b>
            </td>
            <td class="buttons-column">
                <b>Сделано</b>
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
                <tr class="table-container">
                    <td><img width="150px" src="{{asset('storage/files/' . $food->image)}}" class="rounded" alt="..."></td>
                    <td>{{$food->name}}</td>
                    <td colspan="2">
                        <div class="d-flex flex-row align-items-center" >
                            <h6 class="m-3 bg-black" style="width: max-content;">{{$food->pivot->quantity}} порций</h6>
                        </div>
                    </td>
                    <td >


                        <div class="d-flex flex-row align-items-center justify-content-center" >
                            <button type="button" @if($food->pivot->quantity_complete == 0) disabled @endif id="order-minus-{{$order->id}}-{{$food->id}}" data-id="{{$food->id}}" data-order="{{$order->id}}" data-quantity="{{$food->pivot->quantity}}" data-is-complete="{{$food->pivot->is_completed}}" class="order-minus btn btn-secondary"><i class="fa-solid fa-minus"></i></button>
                            <h6 id="food-quantity-complete-{{$order->id}}-{{$food->id}}" class="m-3 bg-black" style="width: max-content;">{{$food->pivot->quantity_complete}}</h6>
                             <button type="button" @if($food->pivot->quantity_complete == $food->pivot->quantity) disabled @endif id="order-plus-{{$order->id}}-{{$food->id}}"  data-id="{{$food->id}}" data-order="{{$order->id}}" data-quantity="{{$food->pivot->quantity}}" class="order-plus btn btn-secondary"><i class="fa-solid fa-plus"></i></button>

                        </div>
                    </td>
                    <td>
                        @if($food->pivot->is_completed == false)
                            <div id="is_completed-{{$order->id}}-{{$food->id}}" class="btn btn-secondary fw-bold w-100">
                                <i class="fa-solid fa-utensils"></i> Готовиться
                            </div>
                        @endif
                        @if($food->pivot->is_completed == true)
                            <div id="is_completed-{{$order->id}}-{{$food->id}}" class="btn btn-success  fw-bold w-100">
                                <i class="fa-solid fa-check"></i> Приготовлено
                            </div>
                        @endif
                    </td>
                </tr>

            @endforeach

            <tr>
                <td colspan="12" class="p-4 border-0">
                    <button type="button" data-count="{{$no_finished_orders_count}}" data-num="{{$order_num}}" data-id="{{$order->id}}"  class="order-complete btn btn-success">Выполнить заказ</button>
                </td>
            </tr>

        </tbody>
        @endif
        @endforeach
        @if($no_finished_orders_count == 0)
            <tr  id="no-records">
                <td colspan="12" align="center" class="h-100 align-items-center">
                    <i class="fa-solid fa-store-slash records-missing-icon"></i>
                    <p>Новые заказы отсутствуют</p>
                </td>
            </tr>
            @endif

        </tbody>
    </table>

    <div class="col-12">{{ $orders->links('vendor.pagination.bootstrap-4') }}</div>
@endsection
@push('scripts')
    <script>

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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


        $('.order-plus').click(function (e) {
            e.preventDefault();
            var food = $(this)
            var id = food.data('id')
            var order = food.data('order')
            var quantity = food.data('quantity')


            var quantity_complete = $('#food-quantity-complete-'+order+'-'+id).html()
            $('#order-minus-'+order+'-'+id).prop('disabled', false);
            if(quantity > quantity_complete){
                quantity_complete +++ 1;
                $('#food-quantity-complete-'+order+'-'+id).html(quantity_complete);

            }


            if(quantity == quantity_complete) {


                $('#order-plus-' + order + '-' + id).prop('disabled', true);
                var complete = `
                <div id="is_completed-${order}-${id}" class="btn btn-success  fw-bold w-100">
                                <i class="fa-solid fa-check"></i> Приготовлено
                            </div>`
                $('#is_completed-' + order + '-' + id).replaceWith(complete);


            }

                    var quantity = food.data('quantity')
                    var quantity_complete = $('#food-quantity-complete-'+order+'-'+id).html()
                    if(quantity == quantity_complete) {

                       var is_completed = 1;
                    }
                    if (quantity > quantity_complete){
                        var is_completed = 0;
                    }

                        $('#order-minus-'+order+'-'+id).data('is-complete', 1);
                        $.ajax({
                            url: '{{route('orders.quantityChange')}}',
                            method: 'post',
                            data: {
                                order_id: order,
                                food_id: id,
                                quantity_complete: quantity_complete,
                                is_completed: is_completed,
                            },
                        })




        })

        $('.order-minus').click(function (e) {
            e.preventDefault();
            var food = $(this)
            var id = food.data('id')
            var order = food.data('order')
            var quantity = food.data('quantity')
            var quantity_complete = $('#food-quantity-complete-'+order+'-'+id).html()




            if(quantity >= quantity_complete && quantity_complete != 0){
                quantity_complete -= 1;
                food.data('complete', 0);
                $('#food-quantity-complete-'+order+'-'+id).html(quantity_complete);
                $('#order-plus-'+order+'-'+id).prop('disabled', false);



                    var quantity = food.data('quantity')
                    var quantity_complete = $('#food-quantity-complete-'+order+'-'+id).html()
                if(quantity > quantity_complete) {
                    var is_completed = 0;

                }
                else {
                    var is_completed = 1;
                }

                        $('#order-minus-'+order+'-'+id).data('is-complete', 0);
                        $.ajax({
                            url: '{{route('orders.quantityChange')}}',
                            method: 'post',
                            data: {
                                order_id: order,
                                food_id: id,
                                quantity_complete: quantity_complete,
                                is_completed: is_completed,
                            },
                        })
                    }


                if(quantity_complete == 0){

                    $('#order-minus-'+order+'-'+id).prop('disabled', true);
                }



                var not_complete = `
                 <div id="is_completed-${order}-${id}" class="btn btn-secondary fw-bold w-100">
                                <i class="fa-solid fa-utensils"></i> Готовиться
                            </div>`
                $('#is_completed-'+order+'-'+id).replaceWith(not_complete);





        })

        $('.order-complete').click(function (e) {
            e.preventDefault();

            var food = $(this)
            var id = food.data('id')
            var num = food.data('num')
            var orders_count =  food.data('count')


            $.ajax({
                url: '{{route('orders.orderComplete')}}',
                method: 'post',
                data: {
                    id: id,
                },
                success: function (data) {
                    $('#alert').html(`
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <b>Успех!</b> Заказ №${num}, успешно был выполнен!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`)
                    $('#ordered-food-' + id).remove()
                    $('#show-order-more-' + id).remove();


                    orders_count -= 1;
                    var food = $('.order-complete')
                    food.data('count', orders_count);


                    $('#cart-count').html(orders_count)

                    if (orders_count == 0) {
                        $('#table').after(`
                         <tr  id="no-records">
                <td colspan="12" align="center" class="h-100 align-items-center">
                    <i class="fa-solid fa-store-slash records-missing-icon"></i>
                    <p>Новые заказы отсутствуют</p>
                </td>
            </tr>`)
                    }

                },

                error: function (data) {
                    // Сообщение об ошибке
                    $('#alert').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Ошибка!</strong> Не удалось обновить роль у пользователя!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`)
                }
            })
        })

    </script>

@endpush
