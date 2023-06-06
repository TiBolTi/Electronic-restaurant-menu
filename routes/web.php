<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ToppingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\HallController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\HomeController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//Главная
Route::get('/', function () {
    return view('home');
});

Auth::routes();
//Главная
Route::get('/home', [HomeController::class, 'index'])->name('home');

//Меню
Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/sort', [MenuController::class, 'sort'])->name('menu.sort');
Route::get('/menu/search', [MenuController::class, 'search'])->name('menu.search');

Route::middleware(['auth:sanctum'])->group(function () {

//    Сотрудники
    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/clients', [EmployeeController::class, 'clients'])->name('employees.clients');
    Route::post('/employees/roleUpdate', [EmployeeController::class, 'roleUpdate'])->name('employees.roleUpdate');

//    Заказы
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/history', [OrderController::class, 'orderHistory'])->name('orders.history');
    Route::post('/orders/quantityChange', [OrderController::class, 'quantityChange'])->name('orders.quantityChange');
    Route::post('/orders/orderComplete', [OrderController::class, 'orderComplete'])->name('orders.orderComplete');

//    Корзина
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/orderConfirm', [CartController::class, 'orderConfirm'])->name('cart.orderConfirm');
    Route::get('/cart/history', [CartController::class, 'orderHistory'])->name('cart.history');

//    Блюда
    Route::get('/food', [FoodController::class, 'index'])->name('food.index');
    Route::get('/food/sort', [FoodController::class, 'sort'])->name('food.sort');
    Route::post('/food', [FoodController::class, 'store'])->name('food.store');
    Route::post('/food/update/', [FoodController::class, 'update'])->name('food.update');
    Route::delete('/food/{food}', [FoodController::class, 'destroy'])->name('food.destroy');

//    Категории
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/sort', [CategoryController::class, 'sort'])->name('categories.sort');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::post('/categories/update/', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

//    Залы
    Route::get('/halls', [HallController::class, 'index'])->name('halls.index');
    Route::get('/halls/sort', [HallController::class, 'sort'])->name('halls.sort');
    Route::post('/halls', [HallController::class, 'store'])->name('halls.store');
    Route::put('/halls/update/{hall}', [HallController::class, 'update'])->name('halls.update');
    Route::delete('/halls/{hall}', [HallController::class, 'destroy'])->name('halls.destroy');

//    Ингридиенты
    Route::get('/toppings', [ToppingController::class, 'index'])->name('toppings.index');
    Route::get('/toppings/sort', [ToppingController::class, 'sort'])->name('toppings.sort');
    Route::post('/toppings', [ToppingController::class, 'store'])->name('toppings.store');
    Route::put('/toppings/update/{topping}', [ToppingController::class, 'update'])->name('toppings.update');
    Route::delete('/toppings/{topping}', [ToppingController::class, 'destroy'])->name('toppings.destroy');

});
