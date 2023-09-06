<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Kapi\Models\Task;
use Kapi\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\Kapi\Models\Model>
 */
class TaskFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Task::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $start_date = fake()->dateTimeBetween('2022-12-01 00:00:00', '2023-12-31 23:00:00');
        $end_date = fake()->dateTimeBetween($start_date, $start_date->format('Y-m-d H:i:s').' +2 days');

        return [            
            'ref' => fake()->text(50),
            //'user_id' => User::factory()->create()->id,
            'user_id' => User::inRandomOrder()->first()->id,
            'title' => fake()->text(100),
            'description' => fake()->text(500),
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
    }
}
