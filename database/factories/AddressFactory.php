<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    public function definition(): array
    {
        return [
            'type'     => 'shipping',
            'name'     => $this->faker->name(),
            'country'  => $this->faker->country(),
            'address'  => $this->faker->streetAddress(),
            'state'    => $this->faker->state(),
            'state_id' => null,
            'city'     => $this->faker->city(),
            'zip'      => $this->faker->postcode(),
            'phone'    => $this->faker->phoneNumber(),
        ];
    }
}
