<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql = file_get_contents(database_path('seeders\seeds\units.sql'));
        DB::unprepared($sql);
    }
}
