
@extends('layouts.app')

@section('content')
<div class="ms-es-5">

{{--    block_1--}}
    <div class="bg-1"></div>
    <div class="d-flex mt-5 h-100 justify-content-between container-sm">
        <div class="d-flex flex-column justify-content-between mt-5 mb-5">
            <h1 class="h1_title">Мы готовим <br>лучшую еду для вас</h1>

            <p class="w-75 description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                labore et dolore magna aliqua.</p>
            <div class="mb-5">
            <a href="{{ route('menu.index') }}" class="fw-bold btn btn-outline-dark">Меню</a>
            <a href="{{ route('register') }}" class="fw-bold btn btn-dark">Регистрация</a>
            </div>
            <div class="d-flex w-75 col-sm-12 justify-content-between" style="font-size: 35px">

                <div class="d-flex flex-column justify-content-center">

                    <div class="d-flex  flex-row">
                        <i class="fa-brands fa-instagram"></i>
                        <p class="ms-2" style="font-size: 21px">@instagram</p>
                    </div>
                    <div class="d-flex flex-row">
                        <i class="fa-brands fa-whatsapp"></i>
                        <p class="ms-2" style="font-size: 21px">+1234567890</p>
                    </div>

                </div>
                <div>
                    <div class="d-flex flex-row">
                        <i class="fa-brands fa-telegram"></i>
                        <p class="ms-2" style="font-size: 21px">+1234567890</p>
                    </div>
                    <div class="d-flex flex-row">
                        <i class="fa-solid fa-square-phone"></i>
                        <p class="ms-2" style="font-size: 21px">+1234567890</p>
                    </div>


                </div>
            </div>
        </div>
        <div class="d-none d-lg-flex align-items-end">
            <img src="{{asset('img/elements/hall.png')}}" width="450px">
            <div style="display:contents;">
            <img class="position-absolute me-lg-5" src="{{asset('img/elements/dish.png')}}" width="300px" style="margin-left: -100px">
            </div>
        </div>
    </div>
{{--    end block-1--}}

    <div class="mt-5 mb-5"></div>
{{--block-2--}}
    <div class="bg-2"></div>
<div class="d-flex align-items-center mb-5 mt-5  h-100 justify-content-between">
    <div class="d-flex  align-items-end">
        <img class="block_img" src="{{asset('img/elements/dish.png')}}">
    </div>
    <div class="d-flex flex-column w-100 justify-content-between mt-5 mb-5">
        <h1 class="h1_title">Добро пожаловать<br> в наш ресторан</h1>

        <p class="w-75 description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
            labore et dolore magna aliqua.</p>
        <div class="mb-5">
            <a href="{{ route('menu.index') }}" class="fw-bold btn btn-outline-dark">Меню</a>
            <a href="{{ route('register') }}" class="fw-bold btn btn-dark">Регистрация</a>
        </div>

        </div>
    </div>



{{--    end block-2--}}
    <div class="mt-5 mb-5"></div>
{{--block-3--}}
    <div class="bg-3" ></div>
<div class="d-flex mt-5 h-100 justify-content-between">
    <div class="d-flex flex-column  justify-content-between mt-5 mb-5">
        <div>
        <h1 class="h1_title">У нас работают лучшие повара </h1>

        <p class="w-75 description">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
            labore et dolore magna aliqua.</p>
        </div>
        <div class="mb-5">
            <a href="{{ route('menu.index') }}" class="fw-bold btn btn-outline-dark">Меню</a>
            <a href="{{ route('register') }}" class="fw-bold btn btn-dark">Регистрация</a>
        </div>


        <div class="d-flex w-100 justify-content-between" style="font-size: 35px">

            <div class="d-flex flex-column justify-content-center">
                <div class="d-flex flex-row w-100 mb-1 align-items-center">
                    <i class="fa-solid fa-square-check"></i>
                    <p class="ms-2 mb-0" style="font-size: 15px">Lorem ipsum dolor sit amet, consectetur </p>
                </div>
                <div class="d-flex flex-row mb-1 align-items-center">
                    <i class="fa-solid fa-square-check"></i>
                    <p class="ms-2 mb-0" style="font-size: 15px">Lorem ipsum dolor sit amet, consectetur </p>
                </div>
                <div class="d-flex flex-row mb-1 align-items-center">
                    <i class="fa-solid fa-square-check"></i>
                    <p class="ms-2 mb-0" style="font-size: 15px">Lorem ipsum dolor sit amet, consectetur </p>
                </div>
            </div>
            <div>
                <div class="d-flex flex-row mb-1 align-items-center">
                    <i class="fa-solid fa-square-check"></i>
                    <p class="ms-2 mb-0" style="font-size: 15px">Lorem ipsum dolor sit amet, consectetur </p>
                </div>
                <div class="d-flex flex-row mb-1 align-items-center">
                    <i class="fa-solid fa-square-check"></i>
                    <p class="ms-2 mb-0" style="font-size: 15px">Lorem ipsum dolor sit amet, consectetur </p>
                </div>
                <div class="d-flex flex-row mb-1 align-items-center">
                    <i class="fa-solid fa-square-check"></i>
                    <p class="ms-2 mb-0" style="font-size: 15px">Lorem ipsum dolor sit amet, consectetur </p>
                </div>
            </div>

        </div>

    </div>
    <div class="d-flex  align-items-end">
        <img class="block_img" src="{{asset('img/elements/chief.png')}}">

    </div>
</div>
{{--    end block-3--}}



    <div class="bg-4 mt-5"></div>
<div class="d-flex flex-column mt-5 mb-5 h-100 align-items-center">
        <h1 class="h1_title">Время работы</h1>

    <div class="flex-es-wrap d-flex w-100  flex-row justify-content-between mt-5">
        <div class="d-flex  flex-column justify-content-center" style="font-size: 35px">

            <div class="d-flex fw-bold flex-row mb-3 align-items-center">
                <i class="fa-regular fa-clock"></i>
                <p class="ms-2 mb-0" style="font-size: 25px">13:00 - 21:00</p>
            </div>
            <div class="d-flex fw-bold  flex-row mb-3 align-items-center">
                <i class="fa-regular fa-calendar-days"></i>
                <p class="ms-2 mb-0" style="font-size: 25px">ПН - СБ </p>
            </div>
            <div class="d-flex fw-bold  flex-row mb-3 align-items-center">
                <i class="fa-solid fa-location-dot"></i>
                <p class="ms-2 mb-0" style="font-size: 25px">Ибраимова, 115/1 </p>
            </div>
            <div class="mb-5">
                <a href="{{ route('menu.index') }}" class="fw-bold btn btn-outline-dark">Меню</a>
                <a href="{{ route('register') }}" class="fw-bold btn btn-dark">Регистрация</a>
            </div>
        </div>





        <div class="d-flex map" >
        <a class="dg-widget-link w-75" href="http://2gis.kg/bishkek/firm/70000001033921024/center/74.61987018585206,42.875948377589005/zoom/16?utm_medium=widget-source&utm_campaign=firmsonmap&utm_source=bigMap">Посмотреть на карте Бишкека</a>
            <div class="dg-widget-link">
                <a href="http://2gis.kg/bishkek/firm/70000001033921024/photos/70000001033921024/center/74.61987018585206,42.875948377589005/zoom/17?utm_medium=widget-source&utm_campaign=firmsonmap&utm_source=photos">Фотографии компании</a>
            </div>
            <div class="dg-widget-link">
                <a href="http://2gis.kg/bishkek/center/74.619865,42.874072/zoom/16/routeTab/rsType/bus/to/74.619865,42.874072╎IT Academy?utm_medium=widget-source&utm_campaign=firmsonmap&utm_source=route">Найти проезд до IT Academy</a>
            </div>
            <script charset="utf-8" src="https://widgets.2gis.com/js/DGWidgetLoader.js"></script>
            <script charset="utf-8">new DGWidgetLoader({"width":700,"height":400,"borderColor":"#a3a3a3","pos":{"lat":42.875948377589005,"lon":74.61987018585206,"zoom":16},"opt":{"city":"bishkek"},"org":[{"id":"70000001033921024"}]});</script>
            <noscript style="color:#c00;font-size:16px;font-weight:bold;">Виджет карты использует JavaScript. Включите его в настройках вашего браузера.</noscript>
        </div>

        </div>
</div>
</div>
@endsection

@push('scripts')

    <script>
        $('#background').removeClass('bg-4')
    </script>

@endpush

