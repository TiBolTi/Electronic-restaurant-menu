<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Hash;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Создаём пользователя админа
        $admin = User::create([
            'email' => 'admin@email.ru',
            'name' => 'Admin',
            'password' => Hash::make('1234567890'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        //Выдаём роль администратора
        $admin->assignRole('administrator');
    }
}
