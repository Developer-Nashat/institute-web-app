<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Affiliation;

class AffiliationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Affiliation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'aff_name' => fake()->word(),
            'supervisor' => fake()->word(),
            'aff_address' => fake()->text(),
            'first_phone' => fake()->regexify('[A-Za-z0-9]{15}'),
            'second_phone' => fake()->regexify('[A-Za-z0-9]{15}'),
            'aff_email' => fake()->word(),
        ];
    }
}
