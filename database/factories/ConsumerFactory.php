<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ConsumerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_key' => User::all()->random()->user_key,
            'first_name' => fake()->first_Name(),
            'middle_name'=> fake()->last_Name(),
            'last_name'=> fake()->last_Name(),
            'gender'=>$this->faker->randomElement(['M','F']),
            'birthday' => fake()->dateTimeBetween('1990-01-01', '2012-12-31')
             ->format('Y/m/d'),
            'phone'=> fake()->randomDigit(),
            'civil_status'=>$this->faker->randomElement(['S','M','W']),
            'name_of_spouse'=>fake()->name(),
            'barangay'=>$this->faker->randomElement(["Baucan Norte",
            "Baucan Sur",
            "Boctol",
            "Boyog Norte",
            "Boyog Proper",
            "Boyog Sur",
            "Cabad",
            "Candasig",
            "Cantalid",
            "Cantomimbo",
            "Cogon",
            "Datag Norte",
            "Datag Sur",
            "Del Carmen Este (Poblacion)",
            "Del Carmen Norte (Poblacion)",
            "Del Carmen Sur (Poblacion)",
            "Del Carmen Weste (Poblacion)",
            "Del Rosario",
            "Dorol",
            "Haguilanan Grande",
            "Hanopol Este",
            "Hanopol Norte",
            "Hanopol Weste",
            "Magsija",
            "Maslog",
            "Sagasa",
            "Sal-ing",
            "San Isidro",
            "San Roque",
            "Santo Niño",
            "Tagustusan"]),
            'purok'=>$this->faker->randomElement(['1','2','3','4','5','6']),
            'household_no'=>fake()->randomDigit(),
            'first_reading'=>fake()->randomDigit(),
            'usage_type'=>$this->faker->randomElement(['Residential','Commercial']),
            'serial_no'=>fake()->randomDigit(),
            'brand'=>fake()->name(),
            'consumer_status'=>$this->faker->randomElement(['connected','delinquent','archived'])

        ];
    }
}
