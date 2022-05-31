<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Closure;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $role = Role::inRandomOrder()->first();

        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt(123), // password
            'remember_token' => Str::random(10),
            'role_id' => $role->id,

        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (User $user) {
            $id = (int)$user->role_id;
            if (User::ROLES['admin'] === $id) {
                $user->createToken('ADMIN', User::ABILITIES['ADMIN_ABILITIES']);
            }
            if (User::ROLES['client'] === $id) {
                $user->createToken('CLIENT', User::ABILITIES['CLIENT_ABILITIES']);
            }
            if (User::ROLES['company'] === $id) {
                $user->createToken('COMPANY', User::ABILITIES['COMPANY_ABILITIES']);
            }
            if (User::ROLES['travel'] === $id) {
                $user->createToken('COMPANY', User::ABILITIES['TRAVEL_ABILITIES']);
            }
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return static
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
