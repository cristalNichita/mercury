<?php
namespace Modules\User\Database\factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BankRequisitesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\User\Entities\BankRequisites::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company(),
            'bik' => $this->faker->numerify('#########'),
            'invoice' => $this->faker->numerify('####################'),
            'kor' => $this->faker->numerify('####################')
        ];
    }
}

