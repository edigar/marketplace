<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * @inheritDoc
     */
    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'description' => $this->faker->paragraph,
            'price' => $this->faker->randomFloat(2, 2, 999999),
        ];
    }
}
