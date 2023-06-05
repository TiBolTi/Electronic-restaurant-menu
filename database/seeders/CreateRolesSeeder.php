<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class CreateRolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Создаём роль администратора
        $role = Role::create([
            'name' => 'administrator',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        //Присваиваем права для роли администратора


        $role->syncPermissions(
            'view records',
            'manage records',
            'place an order',
            'execute an order',
            'view orders',
            'manage staff',
            'view customer information'
        );


        //Создаём роль работника ресторана
        $role = Role::create([
            'name' => 'employee',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        //Присваиваем права для роли работника ресторана
        $role->syncPermissions(
            'view records',
            'place an order',
            'execute an order',
            'view orders',
        );


        //Создаём роль клиента
        $role = Role::create([
            'name' => 'client',
            'guard_name' => 'web',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        //Присваиваем права для роли клиента
        $role->syncPermissions(
            'place an order',
        );
    }
}
