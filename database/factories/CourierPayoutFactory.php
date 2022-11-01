<?php

namespace Database\Factories;

use App\Models\CourierPayout;
use Illuminate\Database\Eloquent\Factories\Factory;


class CourierPayoutFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CourierPayout::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        return [
            'courier_id' => $this->faker->numberBetween(0, 999),
            'method' => $this->faker->text($this->faker->numberBetween(5, 255)),
            'amount' => $this->faker->numberBetween(0, 9223372036854775807),
            'date' => $this->faker->date('Y-m-d'),
            'created_at' => $this->faker->date('Y-m-d H:i:s'),
            'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
