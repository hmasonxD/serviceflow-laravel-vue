<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'company' => fake()->optional()->company(),
            'email' => fake()->optional()->safeEmail(),
            'phone' => fake()->optional()->phoneNumber(),
            'address_line_1' => fake()->optional()->streetAddress(),
            'address_line_2' => null,
            'city' => fake()->optional()->city(),
            'province' => fake()->optional()->randomElement([
                'Alberta',
                'British Columbia',
                'Ontario',
            ]),
            'postal_code' => fake()->optional()->postcode(),
            'notes' => fake()->optional()->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
