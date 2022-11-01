<?php

namespace Database\Factories;

use App\Models\Courier;
use Illuminate\Database\Eloquent\Factories\Factory;

use App\Models\User;

class CourierFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Courier::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {


        $user = User::factory()->create();

        return [
            'user_id' => $user->id,
            'active' => $this->faker->boolean,
            'last_location_at' => $this->faker->date('Y-m-d H:i:s'),
            'lat' => $this->faker->numberBetween(0, 9223372036854775807),
            'lng' => $this->faker->numberBetween(0, 9223372036854775807),
            'using_app_pricing' => $this->faker->boolean,
            'base_price' => $this->faker->numberBetween(0, 9223372036854775807),
            'base_distance' => $this->faker->numberBetween(0, 9223372036854775807),
            'additional_distance_pricing' => $this->faker->numberBetween(0, 9223372036854775807),
            'return_distance_pricing' => $this->faker->randomDigitNotNull,
            'additional_stop_tax' => $this->faker->numberBetween(0, 9223372036854775807),
            'created_at' => $this->faker->date('Y-m-d H:i:s'),
            'updated_at' => $this->faker->date('Y-m-d H:i:s'),
            'deleted_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
