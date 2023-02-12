<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            "name"=>"admin",
        ]);
        DB::table('roles')->insert([
            "name"=>"user",
        ]);
        DB::table('roles')->insert([
            "name"=>"banned",
        ]);
        \App\Models\User::factory(10)->create();
        \App\Models\Type::factory(1)->create();
        \App\Models\City::factory(1)->create();
        \App\Models\Bill::factory(10)->create();
    }
}
