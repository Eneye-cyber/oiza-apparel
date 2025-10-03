<?php

namespace Database\Seeders\Shipping;


use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shipping_methods')->insert([
            ['name' => 'Pickup', 'code' => 'pickup', 'is_active' => true],
            ['name' => 'Standard Delivery', 'code' => 'standard', 'is_active' => true],
            ['name' => 'Express Delivery', 'code' => 'express', 'is_active' => true],
        ]);

        $countryIds = DB::table('shipping_countries')->pluck('id', 'code');
        $methodIds = DB::table('shipping_methods')->pluck('id', 'code');

        $ng_id = $countryIds['NG'];

        // Standard Delivery
        DB::table('shipping_country_methods')->insert([
            'country_id' => $ng_id,
            'method_id' => $methodIds['standard'],
            'delivery_cost' => 8000.00,
            'free_shipping_minimum' => null,
            'delivery_min_days' => 3,
            'delivery_max_days' => 5,
            'is_active' => true,
        ]);

        // Express Delivery
        DB::table('shipping_country_methods')->insert([
            'country_id' => $ng_id,
            'method_id' => $methodIds['express'],
            'delivery_cost' => 15000.00,
            'free_shipping_minimum' => null,
            'delivery_min_days' => 1,
            'delivery_max_days' => 2,
            'is_active' => true,
        ]);

        $stateIds = DB::table('shipping_states')->where('country_id', $ng_id)->pluck('id', 'code');
        $lagos_id = $stateIds['LA'];

        // Standard Delivery
        DB::table('shipping_state_methods')->insert([
            'state_id' => $lagos_id,
            'method_id' => $methodIds['standard'],
            'delivery_cost' => 2000.00,
            'free_shipping_minimum' => 200000.00,
            'delivery_min_days' => 2,
            'delivery_max_days' => 4,
            'is_active' => true,
        ]);

        // Express Delivery
        DB::table('shipping_state_methods')->insert([
            'state_id' => $lagos_id,
            'method_id' => $methodIds['express'],
            'delivery_cost' => 5000.00,
            'free_shipping_minimum' => 350000.00,
            'delivery_min_days' => 1,
            'delivery_max_days' => 2,
            'is_active' => true,
        ]);
    }
}