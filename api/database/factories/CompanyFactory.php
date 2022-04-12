<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Company>
 */
class CompanyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user  = User::where('email', '!=', 'client@mail.com')->inRandomOrder()->first();
        return [
            'name' => $this->faker->company(),
            'email' => $this->faker->unique()->safeEmail(),
            'user_id' => $user->id, // password
            'active' => rand(0, 1),
            'tax' => rand(8, 15),
            'phone' => $this->faker->phoneNumber,
            'agree_terms' => 1,
        ];
    }
}
