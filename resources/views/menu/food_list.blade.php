<div id="preloader" class="d-none w-100 h-100 d-flex justify-content-center mt-5">
    <svg class="tea" width="37" height="48" viewBox="0 0 37 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M27.0819 17H3.02508C1.91076 17 1.01376 17.9059 1.0485 19.0197C1.15761 22.5177 1.49703 29.7374 2.5 34C4.07125 40.6778 7.18553 44.8868 8.44856 46.3845C8.79051 46.79 9.29799 47 9.82843 47H20.0218C20.639 47 21.2193 46.7159 21.5659 46.2052C22.6765 44.5687 25.2312 40.4282 27.5 34C28.9757 29.8188 29.084 22.4043 29.0441 18.9156C29.0319 17.8436 28.1539 17 27.0819 17Z" stroke="var(--secondary)" stroke-width="2"></path>
        <path d="M29 23.5C29 23.5 34.5 20.5 35.5 25.4999C36.0986 28.4926 34.2033 31.5383 32 32.8713C29.4555 34.4108 28 34 28 34" stroke="var(--secondary)" stroke-width="2"></path>
        <path id="teabag" fill="var(--secondary)" fill-rule="evenodd" clip-rule="evenodd" d="M16 25V17H14V25H12C10.3431 25 9 26.3431 9 28V34C9 35.6569 10.3431 37 12 37H18C19.6569 37 21 35.6569 21 34V28C21 26.3431 19.6569 25 18 25H16ZM11 28C11 27.4477 11.4477 27 12 27H18C18.5523 27 19 27.4477 19 28V34C19 34.5523 18.5523 35 18 35H12C11.4477 35 11 34.5523 11 34V28Z"></path>
        <path id="steamL" d="M17 1C17 1 17 4.5 14 6.5C11 8.5 11 12 11 12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" stroke="var(--secondary)"></path>
        <path id="steamR" d="M21 6C21 6 21 8.22727 19 9.5C17 10.7727 17 13 17 13" stroke="var(--secondary)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
    </svg>
</div>

<div class="w-100 d-flex flex-wrap" id="food-items">
@foreach($foods as $food)
        @if($food->is_publish == true && $food->category->contains($category))
            <a data-bs-toggle="modal" data-bs-target="#foodModal" data-food="{{$food->id}}" class="food-item card mb-2 d-flex align-items-center category-button " style="width: 16rem; height: 15rem; border: none; margin-right: 3px">
                <div id="order-check-{{$food->id}}" class="d-none w-100 h-100 d-flex align-items-center justify-content-center position-absolute" style="color: #fff; font-size: 75px; border-radius: 10px; text-shadow: 0px 0px 20px black;"><i class="fa-regular fa-square-check"></i></div>
                <img style="height: 15rem; width: 16rem; border-radius: 10px" src="{{asset('storage/files/' . $food->image)}}" class="" alt="...">
                <div class="category-card-body w-100 d-flex align-items-start justify-content-between">
                    <div class="d-flex flex-row align-items-center justify-content-between w-100">
                        <p class="btn bg-success food-price-tag" style="color: #ffffff; border-radius: 0;">{{$food->price}} сом</p>
                        <div class="d-flex">
                            @if($food->is_vegan == true)
                                <p class="btn bg-success food-vegan-tag" style="color: #ffffff; border-radius: 0;"><i class="fa-solid fa-leaf"></i></p>
                            @endif
                            @if($food->is_special == true)
                                    <p class="btn bg-danger food-special-tag" style="color: #ffffff; border-radius: 0;"><i class="fa-solid fa-star"></i></p>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-self-baseline  w-100 category-card-button">
                        <p class="w-75 d-flex justify-content-between align-items-center h-100" >
                            {{$food->name}}
                        </p>
                        <button  id="select-food-{{$food->id}}" class="btn btn-light"><i class="fa-solid fa-cart-shopping"></i></button>
                    </div>
                </div>
            </a>



{{--МОДАЛЬНОЕ ОКНО--}}
            <div class="d-none" id="food-modal-{{$food->id}}">
                <div class="d-flex flex-row">

                <img style="height: 15rem; width: 16rem;" src="{{asset('storage/files/' . $food->image)}}" class="rounded" alt="...">
                <div class="flex-column ms-4 w-100">
                <h3>{{$food->name}}</h3>
                <p class="h-50 mb-4" style="overflow-wrap: anywhere;">{{$food->description}}</p>

                    <div class="d-flex flex-row align-items-center justify-content-between">
                        <h4>{{$food->price}} сом</h4>
                        <div class="d-flex flex-row align-items-center justify-content-between w-50">
                        <div class="d-flex flex-row align-items-center justify-content-between">

                            <button type="button" id="order-minus" class="btn btn-secondary"><i class="fa-solid fa-minus"></i></button>
                                <h6 id="food-quantity" class="m-3 bg-black">1</h6>
                            <button type="button" id="order-plus" class="btn btn-secondary"><i class="fa-solid fa-plus"></i></button>

                        </div>

                        <button type="button" data-id="{{$food->id}}" data-name="{{$food->name}}" data-image="{{$food->image}}"
                                data-price="{{$food->price}}" class="btn btn-success cart"><i class="fa-solid fa-cart-shopping"></i> Заказать</button>
                        </div>
                    </div>



                    </div>

                </div>

                @if($food->topping->count() != 0 || $food->is_vegan == true || $food->is_special == true)
                <div class="border-top mt-5 mb-3 w-100"></div>
                <div class="d-flex flex-row">
                    <div class="d-flex flex-column me-5" style="width: 41%;">
                        @if($food->is_vegan == true)
                            <p class="btn bg-success fw-bold rounded" style="color: #ffffff; border-radius: 0;"><i class="fa-solid fa-leaf"></i> Веганское блюдо</p>
                        @endif
                        @if($food->is_special == true)
                            <p class="btn bg-danger fw-bold rounded" style="color: #ffffff; border-radius: 0;"><i class="fa-solid fa-star"></i> Специальное блюдо</p>
                        @endif
                    </div>
                    @if($food->topping->count() != 0)
                <div class="flex-column w-75">
                    <h4 class="text-center">Ингридиенты</h4>
                    <div style="max-height: 100px" class="d-flex flex-row  flex-wrap">
                    @foreach($food->topping as $topping)
                        @php
                            $quantity = $topping->pivot->quantity;
                        @endphp
                        <h6><span class="ms-2 badge bg-secondary">• {{$topping->name}} @if($quantity) - {{$quantity}} {{$topping->unit->getAbbreviationUnit()}}@endif</span></h6>
                    @endforeach
                    </div>
                </div>
                    @endif


                </div>
                @endif
            </div>
        @endif
    @endforeach
</div>
    @if($foods->count() == 0) {{-- Записи отсутствуют --}}
    <div id="no-records" class="w-100">
        <div align="center" class="h-100 align-items-center justify-content-center">
            <i class="fa-solid fa-file-circle-xmark records-missing-icon"></i>
            <p>Категория пуста</p>
        </div>
    </div>
    @endif
