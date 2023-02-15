<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Connection>
 */
class ConnectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [


               'status' =>$this->faker->randomElement([
                'requested', 'connected', 'withdrawn'
               ]),
                    'user_id' => $this->faker->numberBetween(1, 50),
                    'connected_user_id' =>$this->faker->numberBetween(1, 50),



        ];
    }
}
