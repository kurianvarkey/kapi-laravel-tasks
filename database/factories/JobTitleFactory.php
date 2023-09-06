<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Kapi\Models\JobTitle;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class JobTitleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = JobTitle::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'job_title' => fake()->unique()->jobTitle(),
        ];
    }
}
