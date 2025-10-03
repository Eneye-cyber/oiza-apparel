<?php

namespace Database\Seeders\Shipping;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShippingStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countryIds = DB::table('shipping_countries')->pluck('id', 'code');

        // Gambia (GM)
        $gm_id = $countryIds['GM'];
        DB::table('shipping_states')->insert([
            ['country_id' => $gm_id, 'code' => 'B', 'name' => 'Banjul', 'is_active' => true],
            ['country_id' => $gm_id, 'code' => 'M', 'name' => 'Central River', 'is_active' => true],
            ['country_id' => $gm_id, 'code' => 'L', 'name' => 'Lower River', 'is_active' => true],
            ['country_id' => $gm_id, 'code' => 'N', 'name' => 'North Bank', 'is_active' => true],
            ['country_id' => $gm_id, 'code' => 'U', 'name' => 'Upper River', 'is_active' => true],
            ['country_id' => $gm_id, 'code' => 'W', 'name' => 'Western', 'is_active' => true],
        ]);

        // Ghana (GH)
        $gh_id = $countryIds['GH'];
        DB::table('shipping_states')->insert([
            ['country_id' => $gh_id, 'code' => 'AF', 'name' => 'Ahafo', 'is_active' => true],
            ['country_id' => $gh_id, 'code' => 'AH', 'name' => 'Ashanti', 'is_active' => true],
            ['country_id' => $gh_id, 'code' => 'BO', 'name' => 'Bono', 'is_active' => true],
            ['country_id' => $gh_id, 'code' => 'BE', 'name' => 'Bono East', 'is_active' => true],
            ['country_id' => $gh_id, 'code' => 'CP', 'name' => 'Central', 'is_active' => true],
            ['country_id' => $gh_id, 'code' => 'EP', 'name' => 'Eastern', 'is_active' => true],
            ['country_id' => $gh_id, 'code' => 'AA', 'name' => 'Greater Accra', 'is_active' => true],
            ['country_id' => $gh_id, 'code' => 'NE', 'name' => 'North East', 'is_active' => true],
            ['country_id' => $gh_id, 'code' => 'NP', 'name' => 'Northern', 'is_active' => true],
            ['country_id' => $gh_id, 'code' => 'OT', 'name' => 'Oti', 'is_active' => true],
            ['country_id' => $gh_id, 'code' => 'SV', 'name' => 'Savannah', 'is_active' => true],
            ['country_id' => $gh_id, 'code' => 'UE', 'name' => 'Upper East', 'is_active' => true],
            ['country_id' => $gh_id, 'code' => 'UW', 'name' => 'Upper West', 'is_active' => true],
            ['country_id' => $gh_id, 'code' => 'TV', 'name' => 'Volta', 'is_active' => true],
            ['country_id' => $gh_id, 'code' => 'WP', 'name' => 'Western', 'is_active' => true],
            ['country_id' => $gh_id, 'code' => 'WN', 'name' => 'Western North', 'is_active' => true],
        ]);

        // Liberia (LR)
        $lr_id = $countryIds['LR'];
        DB::table('shipping_states')->insert([
            ['country_id' => $lr_id, 'code' => 'BM', 'name' => 'Bomi', 'is_active' => true],
            ['country_id' => $lr_id, 'code' => 'BG', 'name' => 'Bong', 'is_active' => true],
            ['country_id' => $lr_id, 'code' => 'GP', 'name' => 'Gbarpolu', 'is_active' => true],
            ['country_id' => $lr_id, 'code' => 'GB', 'name' => 'Grand Bassa', 'is_active' => true],
            ['country_id' => $lr_id, 'code' => 'CM', 'name' => 'Grand Cape Mount', 'is_active' => true],
            ['country_id' => $lr_id, 'code' => 'GG', 'name' => 'Grand Gedeh', 'is_active' => true],
            ['country_id' => $lr_id, 'code' => 'GK', 'name' => 'Grand Kru', 'is_active' => true],
            ['country_id' => $lr_id, 'code' => 'LO', 'name' => 'Lofa', 'is_active' => true],
            ['country_id' => $lr_id, 'code' => 'MG', 'name' => 'Margibi', 'is_active' => true],
            ['country_id' => $lr_id, 'code' => 'MY', 'name' => 'Maryland', 'is_active' => true],
            ['country_id' => $lr_id, 'code' => 'MO', 'name' => 'Montserrado', 'is_active' => true],
            ['country_id' => $lr_id, 'code' => 'NI', 'name' => 'Nimba', 'is_active' => true],
            ['country_id' => $lr_id, 'code' => 'RI', 'name' => 'River Cess', 'is_active' => true],
            ['country_id' => $lr_id, 'code' => 'RG', 'name' => 'River Gee', 'is_active' => true],
            ['country_id' => $lr_id, 'code' => 'SI', 'name' => 'Sinoe', 'is_active' => true],
        ]);

        // Nigeria (NG)
        $ng_id = $countryIds['NG'];
        DB::table('shipping_states')->insert([
            ['country_id' => $ng_id, 'code' => 'AB', 'name' => 'Abia', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'FC', 'name' => 'Abuja Federal Capital Territory', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'AD', 'name' => 'Adamawa', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'AK', 'name' => 'Akwa Ibom', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'AN', 'name' => 'Anambra', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'BA', 'name' => 'Bauchi', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'BY', 'name' => 'Bayelsa', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'BE', 'name' => 'Benue', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'BO', 'name' => 'Borno', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'CR', 'name' => 'Cross River', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'DE', 'name' => 'Delta', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'EB', 'name' => 'Ebonyi', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'ED', 'name' => 'Edo', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'EK', 'name' => 'Ekiti', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'EN', 'name' => 'Enugu', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'GO', 'name' => 'Gombe', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'IM', 'name' => 'Imo', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'JI', 'name' => 'Jigawa', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'KD', 'name' => 'Kaduna', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'KN', 'name' => 'Kano', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'KT', 'name' => 'Katsina', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'KE', 'name' => 'Kebbi', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'KO', 'name' => 'Kogi', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'KW', 'name' => 'Kwara', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'LA', 'name' => 'Lagos', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'NA', 'name' => 'Nasarawa', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'NI', 'name' => 'Niger', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'OG', 'name' => 'Ogun', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'ON', 'name' => 'Ondo', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'OS', 'name' => 'Osun', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'OY', 'name' => 'Oyo', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'PL', 'name' => 'Plateau', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'RI', 'name' => 'Rivers', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'SO', 'name' => 'Sokoto', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'TA', 'name' => 'Taraba', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'YO', 'name' => 'Yobe', 'is_active' => true],
            ['country_id' => $ng_id, 'code' => 'ZA', 'name' => 'Zamfara', 'is_active' => true],
        ]);

        // Sierra Leone (SL)
        $sl_id = $countryIds['SL'];
        DB::table('shipping_states')->insert([
            ['country_id' => $sl_id, 'code' => 'E', 'name' => 'Eastern', 'is_active' => true],
            ['country_id' => $sl_id, 'code' => 'NW', 'name' => 'North Western', 'is_active' => true],
            ['country_id' => $sl_id, 'code' => 'N', 'name' => 'Northern', 'is_active' => true],
            ['country_id' => $sl_id, 'code' => 'S', 'name' => 'Southern', 'is_active' => true],
            ['country_id' => $sl_id, 'code' => 'W', 'name' => 'Western Area', 'is_active' => true],
        ]);
    }
}