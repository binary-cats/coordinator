<?php

namespace BinaryCats\Coordinator\Eloquent\Factories;

use BinaryCats\Coordinator\Eloquent\Booking;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Booking::class;

    /**
     * Define the model's default state.
     *
     * @return mixed[]
     */
    public function definition()
    {
        return [
            'starts_at' => $this->faker->dateTime,
            'ends_at' => $this->faker->dateTimeBetween('now', '30 days'),
        ];
    }

    /**
     * Indicate that the booking is canceled.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function canceled()
    {
        return $this->state(function (array $attributes) {
            return [
                'canceled_at' => $this->faker->dateTime,
            ];
        });
    }
}
