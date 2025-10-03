<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Enums\PaymentMethod;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            // order_number and tracking_token are auto-generated in the model
            'guest_email'    => $this->faker->safeEmail(),
            'subtotal'       => $this->faker->randomFloat(2, 20, 500),
            'discount'       => $this->faker->randomFloat(2, 0, 50),
            'tax'            => $this->faker->randomFloat(2, 0, 30),
            'shipping'       => $this->faker->randomFloat(2, 0, 20),
            'total'          => $this->faker->randomFloat(2, 50, 600),
            'status'         => $this->faker->randomElement(OrderStatus::cases())->value,
            'payment_method' => $this->faker->randomElement(PaymentMethod::cases())->value,
            'order_channel'  => $this->faker->randomElement(['website','whatsapp']),
            'payment_status' => $this->faker->randomElement(PaymentStatus::cases())->value,
            'placed_at'      => now(),
        ];
    }
}
