<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
        PermissionsSeeder::class,
        CreateRolesSeeder::class,
        UnitSeeder::class,
        CreateAdminSeeder::class,

        ]);
    }
}
