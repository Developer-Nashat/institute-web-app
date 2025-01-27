<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Nationality;
use App\Models\Person;

class PersonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Person::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'ar_name' => fake()->word(),
            'en_name' => fake()->word(),
            'date_of_birth' => fake()->date(),
            'person_gender' => fake()->randomLetter(),
            'person_email' => fake()->word(),
            'first_phone' => fake()->regexify('[A-Za-z0-9]{15}'),
            'second_phone' => fake()->regexify('[A-Za-z0-9]{15}'),
            'person_address' => fake()->text(),
            'nationalty_id' => Nationality::factory(),
        ];
    }
}
