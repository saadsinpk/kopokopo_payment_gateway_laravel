<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;


class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {

        return [
            'user_id' => $this->faker->numberBetween(0, 999),
            'courier_id' => $this->faker->numberBetween(0, 999),
            'pickup_location' => $this->faker->text($this->faker->numberBetween(5, 255)),
            'pickup_location_data' => $this->faker->boolean,
            'save_pickup_location_for_next_order' => $this->faker->boolean,
            'delivery_locations_data' => $this->faker->boolean,
            'need_return_to_pickup_location' => $this->faker->boolean,
            'distance' => $this->faker->numberBetween(0, 9223372036854775807),
            'courier_value' => $this->faker->numberBetween(0, 9223372036854775807),
            'app_value' => $this->faker->numberBetween(0, 9223372036854775807),
            'total_value' => $this->faker->numberBetween(0, 9223372036854775807),
            'customer_observation' => $this->faker->boolean,
            'offline_payment_method_id' => $this->faker->numberBetween(0, 999),
            'payment_gateway' => $this->faker->text($this->faker->numberBetween(5, 255)),
            'gateway_id' => $this->faker->text($this->faker->numberBetween(5, 255)),
            'payment_status' => $this->faker->text($this->faker->numberBetween(5, 4096)),
            'payment_status_date' => $this->faker->date('Y-m-d H:i:s'),
            'order_status' => $this->faker->text($this->faker->numberBetween(5, 4096)),
            'order_status_date' => $this->faker->date('Y-m-d H:i:s'),
            'status_observation' => $this->faker->boolean,
            'created_at' => $this->faker->date('Y-m-d H:i:s'),
            'updated_at' => $this->faker->date('Y-m-d H:i:s')
        ];
    }
}
