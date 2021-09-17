<?php

namespace Modules\Catalog\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Catalog\Entities\Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id_1c' => $this->faker->text(55),
            'article' => Str::random(10),

            'title' => $this->faker->realText,
            'description' => $this->faker->realText,
            'content' => $this->faker->realText,

            'price' => $this->faker->numberBetween(0, 99999),
            'old_price' => $this->faker->numberBetween(0, 99999),
            'quantity' => $this->faker->numberBetween(0, 50000),

            'is_sale' => $this->faker->boolean,
            'is_offer' => $this->faker->boolean,
            'is_new' => $this->faker->boolean,

            'rating' => $this->faker->numberBetween(0, 5),
        ];
    }
}

