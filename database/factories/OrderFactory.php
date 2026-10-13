<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'order_number' => 'ORD-' . strtoupper(Str::random(8)),
            'status'       => fake()->randomElement(['pending', 'paid', 'shipped', 'completed', 'cancelled']),
        ];
    }
}
