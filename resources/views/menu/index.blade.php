@extends('layouts.app')

@section('content')
    <div class="modal fade" id="foodModal" tabindex="-1" aria-labelledby="foodModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Подтвердите заказ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="modal-body">
                    </div>
            </div>
        </div>
    </div>




    <div id="category-select">

        <div class="d-flex w-100 justify-content-center">
        <img src="{{asset('img/logo/menu-logo.png')}}" width="250px">

        </div>

        <div class="d-flex mt-5">

            <div class="h-100 me-2 d-none" id="category-list">

                <div id="tags-list"  class="d-flex align-items-start justify-content-between">
{{--                Веганское--}}
                 <div>
                <p class="btn bg-success me-2 fw-bold rounded sort-by-tag" style="color: #ffffff; border-radius: 0;">
                    <i class="fa-solid fa-leaf"></i>
                    <input class="ms-3 form-check-input" style="transform:scale(1.7);" type="checkbox" id="sort-by-vegan" value="">
                </p>
{{--                Специальное--}}
                <p class="btn bg-danger me-2 fw-bold rounded sort-by-tag" style="color: #ffffff; border-radius: 0;">
                    <i class="fa-solid fa-star"></i>
                    <input class="ms-3 form-check-input" style="transform:scale(1.7);" type="checkbox" id="sort-by-special" value="1">
                </p>
                </div>

{{--                    Поиск--}}

                    <button type="button" id="search-button" class="btn btn-dark me-1 fw-bold rounded" >
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </button>

                </div>
            <div class="d-flex d-none align-items-center mb-3" id="search-block">
                <button type="button" id="close-search" class="btn btn-danger me-1 fw-bold rounded" style="color: #ffffff; border-radius: 0;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                        <input type="text" id="search" class="form-control" name="query" placeholder="Поиск...">
            </div>

            @foreach($categories as $category)
                @if($category->is_publish == true)
                    <button id="category-list-id-{{$category->id}}" type="button" data-category="{{$category->id}}"  class=" card mb-2  d-flex align-items-center category-button category-list-button" style="width: 16rem; border: none; margin-right: 3px; max-height:56px">
                        <p  class="w-100 d-flex justify-content-between align-items-center  category-item-button text-center" >
                            {{$category->name}}<i class="fa-solid fa-chevron-right"></i>
                        </p>
                    </button>
                @endif
            @endforeach
        </div>


    <div class="d-flex flex-wrap w-100" id="food-list">


        @foreach($categories as $category)
        @if($category->is_publish == true)
<button data-category="{{$category->id}}" id="category-id-{{$category->id}}" class="card mb-2 d-flex align-items-center category-button" style="width: 16rem; border: none; margin-right: 3px">
    <img style="height: 15rem; width: 16rem; border-radius: 10px" src="{{asset('storage/files/' . $category->image)}}" class="" alt="...">
    <div class=" category-card-body w-100">
        <div class="d-flex justify-content-between align-self-baseline  w-100 category-card-button">
            <p class="w-100 d-flex justify-content-between align-items-center h-100 text-center" >
            {{$category->name}}<i class="fa-solid fa-chevron-right"></i>
            </p>
        </div>
    </div>
</button>
            @endif
    @endforeach
</div>
</div>


    </div>

    @if($categories->count() == 0) {{-- Записи отсутствуют --}}
    <div id="no-records">
        <div  align="center" class="h-100 align-items-center">
            <i class="fa-solid fa-file-circle-xmark records-missing-icon"></i>
            <p>Категории отсутствуют</p>
        </div>
    </div>
    @endif







    @push('scripts')
        <script>
            $(document).ready(function() {
                var categoryId;
                var lastCategoryId;

                var checkVegan = 0;
                var checkSpecial = 0;


                $('#sort-by-special').click(function () {
                    if ($(this).is(':checked')) {
                        checkSpecial = 1;
                        sort()
                    } else {
                        checkSpecial = 0;
                        sort()
                    }
                })

                $('#sort-by-vegan').click(function () {
                    if ($(this).is(':checked')) {
                        checkVegan = 1;
                        sort()
                    } else {
                        checkVegan = 0;
                        sort()
                    }
                })


                $('#search-button').click(function (e) {
                    e.preventDefault();

                    $('#tags-list').addClass('d-none')
                    $('#search-block').removeClass('d-none')

                    $('#search').keyup(function () {
                        $('#food-items').addClass('d-none')
                        $('#preloader').removeClass('d-none');

                            var search_input = $('#search').val()

                            search(search_input)

                    })
                })

                $('#close-search').click(function (e) {
                    e.preventDefault();
                    sort()
                    $('#search').val('');
                    $('#search-block').addClass('d-none')
                    $('#tags-list').removeClass('d-none')

                })



                $('.category-button').click(function (e) {
                    e.preventDefault();

                    $('#food-items').addClass('d-none');
                    $('#preloader').removeClass('d-none');

                    var category = $(this)
                    categoryId = category.data('category')
                    $('#search').val('');
                    $('#search-block').addClass('d-none')
                    $('#tags-list').removeClass('d-none')
                    sort()

                    $('#category-list-id-' + lastCategoryId).removeClass('category-active')
                    $('#category-list-id-' + categoryId).addClass('category-active')

                    lastCategoryId = categoryId;

                })
                $(document).on('mouseenter', '.food-item', function() {
                    var food = $(this);
                    var foodId = food.data('food');
                    $('#select-food-' + foodId).removeClass('btn-light').addClass('btn-dark');
                });

                $(document).on('mouseleave', '.food-item', function() {
                    var food = $(this);
                    var foodId = food.data('food');
                    $('#select-food-' + foodId).removeClass('btn-dark').addClass('btn-light');
                });
                $(document).on('click','.food-item', (function (e) {
                        e.preventDefault();
                        var food = $(this)
                        var foodId = food.data('food')
                        var foodMore = $('#food-modal-' + foodId).html();

                        $('#modal-body').html(foodMore)
                        var foodQuantity = 1;


                    $('#order-plus').click(function (e)
                    {
                        e.preventDefault();
                        if(foodQuantity < 99) {
                            foodQuantity += 1;
                            $('#food-quantity').html(foodQuantity)
                        }
                    })
                    $('#order-minus').click(function (e)
                    {
                        e.preventDefault();
                        if(foodQuantity > 1) {
                            foodQuantity -= 1;
                            $('#food-quantity').html(foodQuantity)
                        }
                    })

                    $(document).ready(function() {
                        startSession();
                    });

                    function startSession() {
                        if (!sessionStorage.getItem('cart')) {
                            let cart = {};
                            sessionStorage.setItem('cart', JSON.stringify(cart));
                        }
                    }
                    var foodModal = $('#foodModal');
                    $('.cart').click(function () {
                        let id = $(this).data('id');
                        let image = $(this).data('image');
                        let name = $(this).data('name');
                        let price = $(this).data('price');
                        item = {
                            id: id,
                            name: name,
                            image: image,
                            price: price,
                            quantity: foodQuantity,
                        }
                        let cart = JSON.parse(sessionStorage.getItem('cart'));
                        if (typeof cart !== "undefined" && cart.hasOwnProperty(item.id)) {
                            cart[item.id].quantity = item.quantity;
                        } else {
                            cart[item.id] = item;
                        }
                        sessionStorage.setItem('cart', JSON.stringify(cart));
                        cartCount()
                        foodModal.modal('toggle');

                        $('#order-check-'+id).removeClass('d-none')
                        setTimeout(function (){
                            $('#order-check-'+id).addClass('d-none')
                        }, 3500)
                    });

                    function getCart() {
                        return JSON.parse(sessionStorage.getItem('cart'));

                    }
                    function cartCount() {
                        var cart = getCart()
                        let count = Object.keys(cart).length;
                        if(count > 0){
                            $('.cart-count').removeClass('d-none')
                        }
                        $('.cart-count').html(count)
                    }

                }))

                function search(search_input) {

                    $.ajax({
                        url: '{{route('menu.search')}}',
                        method: 'get',
                        data: {
                            search: search_input,
                            category: categoryId,
                        },
                        success: function (data) {
                            $('#food-list').html(data.view);
                            $('#preloader').addClass('d-none');
                        },
                        error: function () {
                            console.log('sort ERROR!')
                            $('#preloader').addClass('d-none');
                            $('#food-items').removeClass('d-none')
                        }
                    });
                }


                function sort() {

                    $.ajax({
                        url: '{{route('menu.sort')}}',
                        method: 'get',
                        data: {
                            category: categoryId,
                            vegan: checkVegan,
                            special: checkSpecial,
                        },
                        success: function (data) {
                            $('#food-list').html(data.view);
                            $('#preloader').addClass('d-none');
                            $('#food-items').removeClass('d-none')


                            $('#category-list').removeClass('d-none')
                        },
                        error: function () {
                            console.log('sort ERROR!')
                            $('#preloader').addClass('d-none');
                            $('#food-items').removeClass('d-none')
                        }
                    });
                }
            })

        </script>

    @endpush
@endsection
