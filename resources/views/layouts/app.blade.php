<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>


    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

    <!-- jQuery -->
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- DataTables JS -->
    <script type="text/javascript" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>


    <!-- Scripts -->
    {{--    <script src="{{ asset('js/app.js') }}" defer></script>--}}
    <script src="https://kit.fontawesome.com/9604b26771.js" crossorigin="anonymous"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
    @stack('styles')
</head>
<body style="min-width: 121vh;
    width: 100%;">
<div id="background" class="bg-4" style="max-height: -webkit-fill-available; z-index: auto; min-height: calc(100vh - 60px)">
    <div id="app" style="min-width: 100vh;
    width: 100%; min-height: 100vh">

        <nav class="navbar  navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{asset('img/logo/logo.jpg')}}" width="50px">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                        aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ url('/') }}">Главная</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" aria-current="page" href="{{ route('menu.index') }}">Меню</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav me-5 mb-2 mb-lg-0 justify-content-end">
                        @if (Auth::user())
                            @if (auth()->user()->can('view records'))
                                <li class="nav-item dropdown">
                                    <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                       data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" >
                                        База данных
                                    </a>

                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                        <a class="dropdown-item" aria-current="page"
                                           href="{{ route('food.index') }}">Блюда</a>
                                        <a class="dropdown-item" aria-current="page"
                                           href="{{ route('categories.index') }}">Категории</a>
                                        <a class="dropdown-item" aria-current="page"
                                           href="{{ route('toppings.index') }}">Ингридиенты</a>
                                        <a class="dropdown-item" aria-current="page"
                                           href="{{ route('halls.index') }}">Залы</a>
                                    </div>

                                </li>
                            @endif
                            @if (auth()->user()->can('manage staff'))
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="{{ route('employees.index') }}">Сотрудники</a>
                                </li>
                            @endif
                            @if (auth()->user()->can('view orders'))
                                <li class="nav-item">
                                    <a class="nav-link" aria-current="page" href="{{ route('orders.index') }}">Заказы</a>
                                </li>
                            @endif
                        @endif
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        <a href="{{route('cart.index')}}" type="button" class="btn btn-dark position-relative me-3">

                            <i class="fa-solid fa-cart-shopping"></i>
                            <span
                                class="cart-count d-none position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        </span>
                        </a>
                        @guest

                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Войти') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Регистрация') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                   data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Выйти') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 container w-100" style="width: 100vh;min-height: 100%">

            @extends('layouts.modals')
            @yield('content')

        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <script>
        $(document).ready(function () {
            startSession();
            cartCount();
        });

        function startSession() {
            if (!sessionStorage.getItem('cart')) {
                let cart = {};
                sessionStorage.setItem('cart', JSON.stringify(cart));
            }
        }

        function cartCount() {
            var cart = getCart()
            let count = Object.keys(cart).length;
            if (count > 0) {
                $('.cart-count').removeClass('d-none')

            } else {
                $('.cart-count').addClass('d-none')
            }
            $('.cart-count').html(count)

        }

        function getCart() {
            return JSON.parse(sessionStorage.getItem('cart'));

        }

        $(document).on('mouseenter', '.btn-dark', function() {
            var btn = $(this);
            btn.removeClass('btn-dark').addClass('btn-light');
        });

        $(document).on('mouseleave', '.btn-light', function() {
            var btn = $(this);
            btn.removeClass('btn-light').addClass('btn-dark');
        });

    </script>

@stack('scripts')

</body>

<footer class=" bg-5 d-flex shadow-lg">
    <div class="d-flex justify-content-between container mt-3 mb-5" style="height: 100px;">


        <div class="d-flex w-100  flex-row justify-content-between mt-5">
            <div class="d-flex  flex-column justify-content-center" style="font-size: 25px">

                <div class="d-flex fw-bold flex-row mb-3 align-items-center">
                    <i class="fa-regular fa-clock"></i>
                    <p class="ms-2 mb-0" style="font-size: 18px">13:00 - 21:00 </p>
                </div>
                <div class="d-flex fw-bold  flex-row mb-3 align-items-center">
                    <i class="fa-regular fa-calendar-days"></i>
                    <p class="ms-2 mb-0" style="font-size: 18px">ПН - СБ </p>
                </div>
                <div class="d-flex fw-bold  flex-row mb-3 align-items-center">
                    <i class="fa-solid fa-location-dot"></i>
                    <p class="ms-2 mb-0" style="font-size: 18px">Ибраимова, 115/1 </p>
                </div>

            </div>
            <div class="d-none d-lg-flex align-items-center ms-5 justify-content-center">
                <a class="navbar-brand ms-5" href="{{ url('/') }}">
                    <img src="{{asset('img/logo/logo.jpg')}}" width="135px">
                </a>
            </div>
            <div class="d-flex justify-content-between align-items-center" style="font-size: 25px ">

                <div class="d-flex flex-column justify-content-center me-3">
                    <div class="d-flex flex-row">
                        <i class="fa-brands fa-instagram"></i>
                        <p class="ms-2" style="font-size: 18px">@instagram</p>
                    </div>
                    <div class="d-flex flex-row">
                        <i class="fa-brands fa-whatsapp"></i>
                        <p class="ms-2" style="font-size: 18px">+1234567890</p>
                    </div>
                </div>
                <div>
                    <div class="d-flex flex-row">
                        <i class="fa-brands fa-telegram"></i>
                        <p class="ms-2" style="font-size: 18px">+1234567890</p>
                    </div>
                    <div class="d-flex flex-row">
                        <i class="fa-solid fa-square-phone"></i>
                        <p class="ms-2" style="font-size: 18px">+1234567890</p>
                    </div>


                </div>
            </div>
        </div>
    </div>

</footer>
</html>
