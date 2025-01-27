<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Diploma;

class DiplomaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Diploma::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'dip_name' => fake()->word(),
            'dip_price' => fake()->randomFloat(0, 0, 9999999999.),
        ];
    }
}
