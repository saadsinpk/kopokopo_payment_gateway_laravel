<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;


class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        
        return [
            'name' => $this->faker->text($this->faker->numberBetween(5, 255)),
            'email' => $this->faker->email,
            'phone' => $this->faker->numerify('0##########'),
            'email_verified_at' => $this->faker->date('Y-m-d H:i:s'),
            'password' => $this->faker->lexify('1???@???A???'),
            'api_token' => $this->faker->text($this->faker->numberBetween(5, 255)),
            'firebase_token' => $this->faker->text($this->faker->numberBetween(5, 255)),
            'remember_token' => $this->faker->text($this->faker->numberBetween(5, 100)),
            'created_at' => $this->faker->date('Y-m-d H:i:s'),
            'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
