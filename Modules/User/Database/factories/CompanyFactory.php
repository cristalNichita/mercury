<?php
namespace Modules\User\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\User\Entities\Company::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'type' => 1,
            'inn' => $this->faker->numerify('############'),
            'kpp' => $this->faker->numerify('#########'),
            'ogrn' => $this->faker->numerify('#############')
        ];
    }
}

