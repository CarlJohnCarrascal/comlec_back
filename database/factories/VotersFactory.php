<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class VotersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $gender = ["male", "female"];
        $g = $gender[array_rand($gender, 1)];
        return [
            'house_id' => 1,
            'house_number' => 1,
            'purok' => 1,
            'barangay' => 'Brgy 1',
            'municipality' => 'Irosin',
            'city' => 'Sorsogon',
            'fname' => fake()->firstName($g),
            'lname' => fake()->lastName(),
            'mname' => fake()->lastName(),
            'suffix' => '',
            'birthdate' => fake()->date('Y-m-d', '2005-01-01'),
            'mark' => '',
            'gender' => $g,
            'status' => '',
            'ishead' => false
        ];
    }
}
