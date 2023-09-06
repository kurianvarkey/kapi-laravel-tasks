<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Kapi\Models\Contact;
use Kapi\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'landline' => fake()->phoneNumber(),
            'mobile' => fake()->phoneNumber(),
        ];
    }
}
