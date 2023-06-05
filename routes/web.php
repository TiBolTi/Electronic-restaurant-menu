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
use App\Http\Controllers\Auth\VerificationController;

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

Route::get('/', function () {
    return view('home');
});

Auth::routes();
Route::get('/email/verify', [VerificationController::class, 'show'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])->name('verification.verify')->middleware(['signed']);
Route::post('/email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/menu', [MenuController::class, 'index'])->name('menu.index');
Route::get('/menu/sort', [MenuController::class, 'sort'])->name('menu.sort');
Route::get('/menu/search', [MenuController::class, 'search'])->name('menu.search');

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/employees', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/employees/clients', [EmployeeController::class, 'clients'])->name('employees.clients');
    Route::post('/employees/roleUpdate', [EmployeeController::class, 'roleUpdate'])->name('employees.roleUpdate');

    Route::get('/orders/history', [OrderController::class, 'orderHistory'])->name('orders.history');
    Route::post('/orders/quantityChange', [OrderController::class, 'quantityChange'])->name('orders.quantityChange');
    Route::post('/orders/orderComplete', [OrderController::class, 'orderComplete'])->name('orders.orderComplete');
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/orderConfirm', [CartController::class, 'orderConfirm'])->name('cart.orderConfirm');
        Route::get('/cart/history', [CartController::class, 'orderHistory'])->name('cart.history');





        Route::get('/food/sort', [FoodController::class, 'sort'])->name('food.sort');
        Route::get('/food', [FoodController::class, 'index'])->name('food.index');
        Route::post('/food', [FoodController::class, 'store'])->name('food.store');
        Route::post('/food/update/', [FoodController::class, 'update'])->name('food.update');
        Route::delete('/food/{food}', [FoodController::class, 'destroy'])->name('food.destroy');


        Route::get('/categories/sort', [CategoryController::class, 'sort'])->name('categories.sort');
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::post('/categories/update/', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');


        Route::get('/halls/sort', [HallController::class, 'sort'])->name('halls.sort');

        Route::get('/toppings/sort', [ToppingController::class, 'sort'])->name('toppings.sort');


        Route::resources([
            'toppings' => ToppingController::class,
            'orders'=> OrderController::class,
            'halls' => HallController::class
        ]);

});
