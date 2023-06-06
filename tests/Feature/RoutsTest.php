<?php


namespace Tests\Feature;

use App\Http\Controllers\HomeController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoutsTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }


//    Меню
    public function testHome()
    {
        $response = $this->get('/home');

        $response->assertStatus(302);
    }

    public function testMenuIndex()
    {
        $response = $this->get('/menu');

        $response->assertStatus(200);
    }

    public function testMenuSort()
    {
        $response = $this->get('/menu/sort');

        $response->assertStatus(200);
    }
    public function testMenuSearch()
    {
        $response = $this->get('/menu/search');

        $response->assertStatus(200);
    }

//Сотрудники
    public function testEmployeesIndex()
    {
        $response = $this->get('/employees');

        $response->assertStatus(302);
    }
    public function testEmployeesClients()
    {
        $response = $this->get('/employees/clients');

        $response->assertStatus(302);
    }
    public function testEmployeesRoleUpdate()
    {
        $response = $this->post('/employees/roleUpdate');

        $response->assertStatus(419);
    }

//    Заказы
    public function testOrdersIndex()
    {
        $response = $this->get('/orders');

        $response->assertStatus(302);
    }
    public function testOrdersHistory()
    {
        $response = $this->get('/orders/history');

        $response->assertStatus(302);
    }
    public function testOrdersQuantityChange()
    {
        $response = $this->post('orders/quantityChange');

        $response->assertStatus(419);
    }
    public function testOrderComplete()
    {
        $response = $this->post('/orders/orderComplete');

        $response->assertStatus(419);
    }

//    Корзина

    public function testCartIndex()
    {
        $response = $this->get('/cart');

        $response->assertStatus(302);
    }
    public function testCartOrdersConfirm()
    {
        $response = $this->post('/cart/orderConfirm');

        $response->assertStatus(419);
    }
    public function testCartHistory()
    {
        $response = $this->get('/cart/history');

        $response->assertStatus(302);
    }

//    Блюда

    public function testFoodIndex()
    {
        $response = $this->get('/food');

        $response->assertStatus(302);
    }

    public function testFoodSort()
    {
        $response = $this->get('/food/sort');

        $response->assertStatus(302);
    }

    public function testFoodStore()
    {
        $response = $this->post('/food');

        $response->assertStatus(419);
    }

    public function testFoodUpdate()
    {
        $response = $this->post('/food/update/');

        $response->assertStatus(419);
    }

    public function testFoodDelete()
    {
        $response = $this->delete('/food/{food}');

        $response->assertStatus(419);
    }

//    Категории

    public function testCategoryIndex()
    {
        $response = $this->get('/categories');

        $response->assertStatus(302);
    }

    public function testCategorySort()
    {
        $response = $this->get('/categories/sort');

        $response->assertStatus(302);
    }

    public function testCategoryStore()
    {
        $response = $this->post('/categories');

        $response->assertStatus(419);
    }

    public function testCategoryUpdate()
    {
        $response = $this->post('/categories/update/');

        $response->assertStatus(419);
    }

    public function testCategoryDelete()
    {
        $response = $this->delete('/categories/{category}');

        $response->assertStatus(419);
    }

    //Залы

    public function testHallIndex()
    {
        $response = $this->get('/halls');

        $response->assertStatus(302);
    }

    public function testHallSort()
    {
        $response = $this->get('/halls/sort');

        $response->assertStatus(302);
    }

    public function testHallStore()
    {
        $response = $this->post('/halls');

        $response->assertStatus(419);
    }

    public function testHallUpdate()
    {
        $response = $this->post('/halls/update/{hall}');

        $response->assertStatus(405);
    }

    public function testHallDelete()
    {
        $response = $this->delete('/halls/{hall}');

        $response->assertStatus(419);
    }

    //Ингридиенты

    public function testToppingIndex()
    {
        $response = $this->get('/toppings');

        $response->assertStatus(302);
    }

    public function testToppingSort()
    {
        $response = $this->get('/toppings/sort');

        $response->assertStatus(302);
    }

    public function testToppingStore()
    {
        $response = $this->post('/toppings');

        $response->assertStatus(419);
    }

    public function testToppingUpdate()
    {
        $response = $this->post('/toppings/update/{topping}');

        $response->assertStatus(405);
    }

    public function testToppingDelete()
    {
        $response = $this->delete('/toppings/{topping}');

        $response->assertStatus(419);
    }
}
