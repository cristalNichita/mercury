<?php
namespace Modules\Site\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InfoBlockFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Site\Entities\InfoBlock::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence(2);

        return [
            'title' => $title,
            'slug' => (!rand(0,1)) ? null : Str::slug($title),
            'description' => $this->faker->sentence(),
            'background_color'=> $this->faker->hexColor(),
            'in_main' => false
        ];
    }
}

