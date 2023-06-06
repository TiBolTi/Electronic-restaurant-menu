@extends('layouts.app')

@section('content')

    <div id="alert"></div>

    <div class="d-flex justify-content-center w-100 mb-3">
        <a href="{{route('cart.index')}}">
        <button id="" type="button" class=" btn fw-bold me-2 btn-outline-dark">Корзина
            <span id="cart-count" class="d-none badge rounded-4 bg-danger cart-count"> </span>
        </button>
        </a>
        <a href="{{route('cart.history')}}">
        <button  type="button" class="btn fw-bold ms-2 btn-outline-dark">История заказов
            <span id="orders-history-count" class="badge rounded-4 bg-success @if($count = Auth::user()->order()->count() == 0) d-none @endif "> {{ $count = Auth::user()->order()->count() }} </span>
        </button>
        </a>
    </div>

    <table class="table table-bordered table-hover">
        <thead>
        <tr>
            <th class="col-auto id-column">

            </th>
            <th class="buttons-column col-6">
                Продукт
            </th>
            <th class="buttons-column">
                Цена
            </th>
            <th class="buttons-column">
                Количество
            </th>
            <th class="buttons-column">
                Сумма
            </th>
            @if (auth()->user()->can('manage records'))
                <th class="buttons-column" scope="col">Отменить</th>
            @endif
        </tr>
        </thead>

        <tbody  id="table">
        <tbody id="records" class=""></tbody>


        <tr class="d-none" id="no-records">
            <td colspan="12" align="center" class="h-100 align-items-center">
                <i class="fa-solid fa-store-slash records-missing-icon"></i>
                <p>Корзина пуста</p>
            </td>
        </tr>
        </tbody>
        <tfoot>
            <tr>
                <td colspan="12" id="table-foot">
                    <div class="col-12 d-flex justify-content-between align-items-center mt-3 mb-3" id="order-footer">
                    <h5>Итого: <span class="total-sum"></span> сом</h5>
                    <button id="payment" type="button" data-id="${products[key].id}" class="btn btn-success">Оформить заказ</button>
                    </div>


                    <div class="d-flex justify-content-between d-none" id="order-block">
                        <div class="d-flex flex-column mt-3 mb-3 ms-5 me-3 w-50">
                            <div class="mb-3">
                                <h6>Номер карты</h6>
                                <input id="card-number" maxlength="16" type="tel" inputmode="tel" value="" name="card_number" class="form-control" placeholder="0000-0000-0000-0000">
                            </div>

                            <div class="mb-3 d-flex w-75 align-items-center">
                                <div class="me-4"><h6>Действительна до:</h6></div>
                                <div class="d-flex flex-column justify-content-between align-items-center">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6>Месяц</h6>
                                        <h6>Год</h6>
                                    </div>
                                    <div class="d-flex">
                                        <div>
                                        <input id="card-mount" maxlength="2"  type="tel" value=""  name="card_mount" class="form-control " placeholder="00">
                                        </div>
                                        <div>
                                        <i class="fa-solid fa-slash fa-rotate-270 m-2"></i>
                                        </div>
                                        <div>
                                        <input id="card-year" maxlength="2" type="tel" value="" name="card_year" class="form-control " placeholder="00">
                                        </div>
                                    </div>
                                </div>
                                <div class=" d-flex flex-column align-items-center w-50">
                                    <h6>CCV</h6>
                                    <input maxlength="3" id="card-ccv" type="tel" value="" name="card_ccv" class="form-control ms-2" placeholder="123">
                                </div>

                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="mb-3">
                                    <h6>Выберете зал</h6>
                                    <select name="hall_id" id="input-hall" class="form-select me-2 w-100">
                                        <option selected disabled>Выберете зал</option>
                                        @foreach($halls as $hall)
                                            <option data-seats="{{$hall->number_of_tables}}" class="select-hall" value="{{$hall->id}}">{{$hall->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3" >
                                    <h6>Выберете столик</h6>
                                    <select name="table_number" id="input-seat" class="form-select me-2 w-100">
                                        <option selected disabled>Выберете столик</option>
                                    </select>
                                </div>
                            </div>
                        </div>


                        <div class="d-flex flex-column mt-3 me-5 ms-3 w-50 h-100 ">
                            <div class="mb-1 mt-4">
                                <h6 class="mb-3">Все блюда - <span class="total-sum"></span> сом</h6>
                                <h6 class="mb-3">Обслуживание - 20 сом</h6>
                                <h6 class="d-flex align-items-center mb-2">Чаевые -
                                    <input maxlength="4" id="tips" type="text" value="" name="name" class="form-control ms-2 w-25 " placeholder="По желанию">
                                </h6>

                            </div>
                            <div class="col-12 d-flex justify-content-between align-items-center mt-5 mb-3">
                                <h5>Итого: <span id="total-sum-price"></span> сом</h5>
                                <div>
                                <button id="confirm-payment" type="button" data-id="${products[key].id}" class="btn btn-success">Оплатить заказ</button>
                                    <button id="cancel-payment" type="button" data-id="${products[key].id}" class="btn btn-secondary">Отмена</button>
                                </div>
                            </div>
                        </div>
                    </div>

                </td>
            </tr>
        </tfoot>
    </table>

@endsection

@push('scripts')

<script>



        $(document).ready(function () {
            let products = JSON.parse(sessionStorage.getItem('cart'));


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

                startSession();

            function startSession() {
                if (!sessionStorage.getItem('cart')) {
                    let cart = {};
                    sessionStorage.setItem('cart', JSON.stringify(cart));
                }
            }

            cartCount()

            var orderPrice = 0;

            function totalPrice() {
                var totalSum = 0;
                let cart = getCart();
                for (var key in cart) {
                    var foodPrice = cart[key].price
                    var foodQuantity = cart[key].quantity
                    totalSum = (foodPrice * foodQuantity) + totalSum
                    $('.total-sum').html(totalSum)
                    orderPrice = totalSum

                }
            }
        var totalPayment

            function totalPaymentPrice() {
                var orderSum = orderPrice;
                var service = 20;
                var tips = 0;

                $('#tips').on('input', function () {
                    var value = $(this).val();

                    // Проверка на пустое значение или некорректное число
                    if (value === '' || isNaN(value)) {
                        tips = 0;
                    } else {
                        tips = parseInt(value);
                    }

                    updateTotalSumPrice();
                });

                function updateTotalSumPrice() {
                    totalPayment = orderSum + service + tips;
                    $('#total-sum-price').html(totalPayment);
                }

                updateTotalSumPrice();
                return totalPayment
            }

            function getCart() {
                return JSON.parse(sessionStorage.getItem('cart'));

            }

            function cartCount() {
                var cart = getCart()
                let count = Object.keys(cart).length;
                if (count > 0) {
                    $('.cart-count').removeClass('d-none')
                    $('#table-foot').removeClass('d-none')
                    $('#no-records').addClass('d-none')

                } else {
                    $('.cart-count').addClass('d-none')
                    $('#table-foot').addClass('d-none')
                    $('#no-records').removeClass('d-none')
                }
                $('.cart-count').html(count)
            }


            totalPrice()

            for (var key in products) {

                var foodPrice = products[key].price
                var foodQuantity = products[key].quantity

                function totalSum(foodPrice, foodQuantity) {
                    return totalSum = foodPrice * foodQuantity;
                }


                $('#records').append(`
                <tr class="table-container" id="ordered-food-${products[key].id}">
                <td><img width="150px" src="storage/files/${products[key].image}" class="rounded" alt="..."></td>
                <td>${products[key].name}</td>
                <td id="food-price-${products[key].id}" data-price="${products[key].price}">${products[key].price} сом</td>
                <td>
                <div class="d-flex flex-row align-items-center ">
                   <button type="button" data-id="${products[key].id}" class="btn btn-secondary order-minus"><i class="fa-solid fa-minus"></i></button>
                   <h6 id="food-quantity-${products[key].id}" class="m-3 bg-black">${products[key].quantity}</h6>
                    <button type="button"  data-id="${products[key].id}" class="btn btn-secondary order-plus"><i class="fa-solid fa-plus"></i></button>

</div>
</td>
                <td id="foodTotalSum-${products[key].id}">${totalSum(foodPrice, foodQuantity)} сом</td>
                <td class="text-center"><button type="button" data-id="${products[key].id}" data-bs-toggle="modal" data-bs-target="#deleteModal" class="btn btn-danger order-delete"><i class="fa-solid fa-xmark"></i></button></td>

            </tr>





`)
            }
            var orderedFood = foodOrder();
            foodOrder()
            function foodOrder() {
                var cart = getCart();
                var orderedFood = [];

                for (var key in cart) {
                    var item = {
                        id: cart[key].id,
                        quantity: cart[key].quantity,
                        price: cart[key].price,
                    };

                    orderedFood.push(item);
                }

                return orderedFood;
            }



            $('.order-plus').click(function (e) {
                e.preventDefault();
                var foodId = $(this).data('id')
                var foodQuantity = $('#food-quantity-' + foodId).html()
                var foodPrice = $('#food-price-' + foodId).data('price')

                if (foodQuantity < 99) {
                    foodQuantity++ + 1;
                    $('#food-quantity-' + foodId).html(foodQuantity)
                    $('#foodTotalSum-' + foodId).html(totalSum(foodPrice, foodQuantity) + ' сом')
                    let cart = getCart();
                    cart[foodId].quantity = foodQuantity;
                    sessionStorage.setItem('cart', JSON.stringify(cart));
                    orderedFood = foodOrder();
                    totalPrice()
                    totalPaymentPrice()
                }


            })

            $('.order-minus').click(function (e) {
                e.preventDefault();

                var foodId = $(this).data('id')
                var foodQuantity = $('#food-quantity-' + foodId).html()
                var foodPrice = $('#food-price-' + foodId).data('price')

                if (foodQuantity > 1) {
                    foodQuantity -= 1;
                    $('#food-quantity-' + foodId).html(foodQuantity)
                    $('#foodTotalSum-' + foodId).html(totalSum(foodPrice, foodQuantity) + ' сом')
                    let cart = getCart();
                    cart[foodId].quantity = foodQuantity;
                    sessionStorage.setItem('cart', JSON.stringify(cart));
                    orderedFood = foodOrder();
                    totalPrice()
                    totalPaymentPrice()


                }
            })

            var deleteModal = $('#deleteModal');

            $('.order-delete').click(function (e) {
                e.preventDefault();
                var foodId = $(this).data('id')
                let cart = getCart();
                // $('#modal-body').html(`<p>Вы действительно хотите удалить этот элемент?</p><br><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Отмена</button>
                //         <button type="submit" id="confirm-delete" class="btn btn-danger">Удалить</button>`)

                $('#confirm-delete').click(function (e) {
                    e.preventDefault();
                    delete cart[foodId]
                    sessionStorage.setItem('cart', JSON.stringify(cart));
                    $('#ordered-food-' + foodId).remove()
                    cartCount()
                    totalPrice()
                    totalPaymentPrice()
                    deleteModal.modal('toggle');
                })
            })


            $('#input-hall').on('change', function () {
                var selectedHall = $('#input-hall option:selected');
                var hallId = selectedHall.val();
                var hallName = selectedHall.text();
                var seats = selectedHall.data('seats');
                var firstOption = $('#input-hall option:first');

                if (selectedHall.val() === firstOption.val()) {
                    // Первый элемент выбран, очищаем и скрываем список столиков
                    $('#input-seat').empty();
                    $('#input-seat').addClass('d-none');
                    return;
                }

                // Продолжаем выполнение AJAX-запроса и заполняем список столиков
                $('#input-seat').empty();
                $('#input-seat').append('<option selected disabled>Выберете столик</option>');
                $('#input-seat').removeClass('d-none');
                for (i = 1; i <= seats; i += 1) {
                    $('#input-seat').append('<option value="' + i + '">' + i + ' столик</option>');
                }
            });


            $('#payment').click(function (e) {
                e.preventDefault();

                $('#order-block').removeClass('d-none')
                $('#order-footer').addClass('d-none')

                totalPaymentPrice()


                $('#cancel-payment').click(function (e) {
                    e.preventDefault();
                    $('#order-block').addClass('d-none')
                    $('#order-footer').removeClass('d-none')
                })


                $('#confirm-payment').click(function (e) {
                    e.preventDefault();

                    var data = [];


                    var selectedHall = $('#input-hall option:selected').val();
                    var selectedSeat = $('#input-seat option:selected').val();
                    var cardNumber = $('#card-number').val()
                    var cardMount = $('#card-mount').val()
                    var cardYear = $('#card-year').val()
                    var cardCcv = $('#card-ccv').val()
                    var tips = $('#tips').val()
                    var price = totalPrice()
                    var paymentSum = totalPayment

                    var order = {
                        hall_id: selectedHall,
                        table_number: selectedSeat,
                        card_number: cardNumber,
                        card_mount: cardMount,
                        card_year: cardYear,
                        card_ccv: cardCcv,
                        total_price: orderPrice,
                        tips: tips,
                        payment_sum: paymentSum,
                        }


                    data.push(order)




                    $.ajax({
                        url: '{{route('cart.orderConfirm')}}',
                        method: 'post',
                        data: {
                            items: JSON.stringify(data),
                            order: JSON.stringify(orderedFood),

                        },

                        success: function (data) {
                            if (data.success) {
                                var cart = getCart()

                                let ordersCount = $('#orders-history-count').html()

                                $('#orders-history-count').removeClass('d-none')
                                let foodInCartCount = Object.keys(cart).length;


                                $('#orders-history-count').html(ordersCount +++ 1)

                                for (var key in cart) {
                                    if (cart.hasOwnProperty(key)) {
                                        delete cart[key];
                                    }
                                }
                                sessionStorage.setItem('cart', JSON.stringify(cart));
                                $('#records').remove()
                                cartCount()
                                totalPrice()
                                totalPaymentPrice()



                                // Сообщение об успехе
                                //Получение номера записи для отображения в сообщении
                                $('#alert').html(`
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                   <strong> Оплата прошла успешно!</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>`)
                            } else {

                                // Сообщение об ошибке
                                $('#alert').html(`
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Ошибка!</strong> Не удалось провести оплату!
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>`)
                                // Если есть ошибки валидации, отобразить их
                                $.each(data.errors, function (field, errors) {
                                    // Найти элемент формы, соответствующий полю, содержащему ошибку
                                    var input = $('#table-foot  [name="' + field + '"]');
                                    // Добавить класс is-invalid для этого элемента
                                    input.addClass('is-invalid');
                                    // Вывести сообщение об ошибке
                                    var errorMessages = '<div class="invalid-feedback text-start">';
                                    $.each(errors, function (index, error) {
                                        errorMessages += '<div>' + error + '</div>';
                                    });
                                    errorMessages += '</div>';
                                    input.after(errorMessages);
                                })
                            }
                        },



                        error: function () {
                            console.log('sort ERROR!')
                        }
                    })
                });
            })
         })
</script>
@endpush
