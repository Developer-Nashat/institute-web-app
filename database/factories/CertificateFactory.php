<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Certificate;
use App\Models\Course;
use App\Models\Student;

class CertificateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Certificate::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'course_id' => Course::factory(),
            'certificate_no' => fake()->word(),
            'issue_date' => fake()->date(),
            'expiration_date' => fake()->date(),
            'status' => fake()->word(),
        ];
    }
}
