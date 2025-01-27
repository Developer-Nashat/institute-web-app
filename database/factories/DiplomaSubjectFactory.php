<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Diploma;
use App\Models\DiplomaSubject;
use App\Models\Subject;

class DiplomaSubjectFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = DiplomaSubject::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'subject_id' => Subject::factory(),
            'diploma_id' => Diploma::factory(),
            'order' => fake()->numberBetween(-1000, 1000),
        ];
    }
}
