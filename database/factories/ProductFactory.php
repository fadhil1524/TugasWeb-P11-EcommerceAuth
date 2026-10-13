<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'category_id' => Category::factory(),
            'user_id'     => User::factory(),
            'name'        => ucwords($name),
            'slug'        => Str::slug($name) . '-' . fake()->unique()->numberBetween(100, 99999),
            'description' => fake()->paragraph(),
            'price'       => fake()->numberBetween(25, 5000) * 1000,
            'stock'       => fake()->numberBetween(0, 200),
            'is_active'   => fake()->boolean(90),
        ];
    }
}
