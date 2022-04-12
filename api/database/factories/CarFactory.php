<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Arr;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $user  = User::inRandomOrder()->first();

        $random = rand(0, 20);
        $random = $random === 0 ? 0 : $random;
        $user = $random === 0 ? User::first() : $user;
        return [
            'name' => Arr::random(['Mazda', 'Mercedes', 'Nisan', 'Audi', 'Fiat']),
            'model' => 'RX ' . rand(1, 200),
            'type' =>  Arr::random(['Comfort' , 'Economy', 'Business', 'Premium Minibus', 'Minivan']),
            'person_quantity' => rand(4, 7),
            'baggage_quantity' => rand(1,3),
            'image' => 'https://images.unsplash.com/photo-1453728013993-6d66e9c9123a?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8Mnx8dmlld3xlbnwwfHwwfHw%3D&w=1000&q=80',
            'active' => rand(0,1),
            'company_id' => $random,
            'user_id' => $user->id,
        ];
    }
}
