<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SessionYear>
 */
class SessionYearFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = $this->faker->numberBetween(2020, 2025);
        $startDate = "$year-01-01"; 
        $endDate = ($year + 1) . "-12-31"; 

        return [
            'name' => "$year/" . ($year + 1),
            'start_date' => $this->faker->dateTimeBetween($startDate, "$year-06-30")->format('Y-m-d'), 
            'end_date' => $this->faker->dateTimeBetween("$year-07-01", $endDate)->format('Y-m-d'), 
        ];
    }
}
