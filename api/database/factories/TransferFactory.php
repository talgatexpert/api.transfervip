<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TransferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $city_start = City::inRandomOrder()->first();
        $company = Company::with('user')->find(rand(0, 30));
        $car = City::inRandomOrder()->first();
        $city_end = City::whereNot('id', $city_start->end)->inRandomOrder()->first();
        $start_date = $this->faker->dateTimeBetween('-2 month', '+1 month');
        $end_date = $this->faker->dateTimeBetween($start_date, $start_date->modify('+5 hours'));
        if ($company){
            return [
                'start_city_id' => $city_start->id,
                'finish_city_id' => $city_end->id,
                'car_id' => $car->id,
                'tax' => rand(10, 50),
                'price' => rand(150, 3000),
                'started_at' => $start_date,
                'ended_at' => $end_date,
                'company_id' => $company->id,
                'user_id' => $company->user->id,
                'description' => $this->faker->text
            ];
        }

        return [
            'start_city_id' => $city_start->id,
            'finish_city_id' => $city_end->id,
            'car_id' => $car->id,
            'tax' => rand(10, 50),
            'price' => rand(150, 3000),
            'started_at' => $start_date,
            'ended_at' => $end_date,
            'company_id' => 0,
            'user_id' => 1,
            'description' => $this->faker->text

        ];

    }
}
