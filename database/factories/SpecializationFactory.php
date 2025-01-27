<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Specialization;

class SpecializationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Specialization::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'specialization_name' => fake()->word(),
        ];
    }
}
