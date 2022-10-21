<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('types')->truncate();

        DB::table('types')->insert([
            ['name' => 'percentage'],
            ['name' => 'fixed'],
            ['name' => 'free']
        ]);
    }
}
