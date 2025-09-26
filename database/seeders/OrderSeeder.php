<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Orders\Order;
use App\Models\Address;
use App\Models\Shipping\ShippingState;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $countryIds = DB::table('shipping_countries')->where('code', 'NG')->pluck('id', 'code');
        $ng_id = $countryIds['NG'] ?? null;

        if (! $ng_id) {
            // Handle case where country not found (e.g., log or skip)
            return;
        }

        // Get all states for NG once, for efficiency
        $states = ShippingState::where('country_id', $ng_id)->get();

        if ($states->isEmpty()) {
            // Handle no states (e.g., log or skip)
            return;
        }

        Order::factory()
            ->count(10)
            ->create()
            ->each(function (Order $order) use ($states) {
                // Pick a random state per order for variety
                $state = $states->random();

                // Always create a shipping address
                $order->shippingAddress()->create(
                    Address::factory()->make([
                        'country'  => 'NG',
                        'state'    => $state->name,
                        'state_id' => $state->id,
                        'type'     => 'shipping',
                    ])->toArray()
                );

                // Billing address: 50% chance to be unique, 50% copy of shipping
                if (rand(0, 1)) {
                    $order->billingAddress()->create(
                        Address::factory()->make([
                            'type' => 'billing',
                        ])->toArray()
                    );
                } else {
                    $shipping = $order->shippingAddress;
                    $order->billingAddress()->create(
                        $shipping->replicate()->fill(['type' => 'billing'])->toArray()
                    );
                }
            });
    }
}