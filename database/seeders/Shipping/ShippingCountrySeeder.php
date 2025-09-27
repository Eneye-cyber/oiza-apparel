<?php

namespace Database\Seeders\Shipping;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class ShippingCountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('shipping_countries')->insert([
            ['code' => 'GM', 'name' => 'Gambia', 'is_active' => true],
            ['code' => 'GH', 'name' => 'Ghana', 'is_active' => true],
            ['code' => 'LR', 'name' => 'Liberia', 'is_active' => true],
            ['code' => 'NG', 'name' => 'Nigeria', 'is_active' => true],
            ['code' => 'SL', 'name' => 'Sierra Leone', 'is_active' => true],
        ]);
    }
}
