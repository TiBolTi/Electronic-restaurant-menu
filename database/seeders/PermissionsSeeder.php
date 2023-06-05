<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Права доступа
        Permission::create(['name' => 'view records','guard_name' => 'web',]);//Просмотр записей
        Permission::create(['name' => 'manage records','guard_name' => 'web',]); //Управление записями

        Permission::create(['name' => 'place an order','guard_name' => 'web',]); //Оформить заказ
        Permission::create(['name' => 'view orders','guard_name' => 'web',]); //Просмотр заказов
        Permission::create(['name' => 'execute an order','guard_name' => 'web',]); //Выполнить заказ

        Permission::create(['name' => 'manage staff','guard_name' => 'web',]); //Управление сотрудниками

        Permission::create(['name' => 'view customer information','guard_name' => 'web',]); //Просмотр информации о клиентах


    }
}
