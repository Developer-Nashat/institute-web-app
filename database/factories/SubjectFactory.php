<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Subject;

class SubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Subject::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'sub_name' => fake()->word(),
            'sub_price' => fake()->randomFloat(0, 0, 9999999999.),
            'DurationType' => fake()->word(),
            'TotalHours' => fake()->numberBetween(-1000, 1000),
            'Totaldays' => fake()->numberBetween(-1000, 1000),
            'cat_id' => Category::factory(),
        ];
    }
}
