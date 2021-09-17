<?php
namespace Modules\Site\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Site\Entities\Slider;

class SliderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Slider::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = rand(0, count(Slider::types) - 1);

        return [
            'title' => $this->faker->sentence(2),
            'description' => $this->faker->sentence(3),
            'button_text' => $this->faker->word(),
            'button_color' => "#FFFFFF",
            'url' => 'https://placeholder.com/',
            'active' => ($this->faker->randomDigit % 2) == 1,

            'type' => $type,
        ];
    }
}

