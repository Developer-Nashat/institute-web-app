<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\GradeEvaluation;

class GradeEvaluationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = GradeEvaluation::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'grade_max' => fake()->numberBetween(-1000, 1000),
            'grade_min' => fake()->numberBetween(-1000, 1000),
        ];
    }
}
