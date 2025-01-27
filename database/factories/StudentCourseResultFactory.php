<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Course;
use App\Models\GradeEvaluation;
use App\Models\Student;
use App\Models\StudentCourseResult;

class StudentCourseResultFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StudentCourseResult::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'student_id' => Student::factory(),
            'student_mark' => fake()->randomFloat(0, 0, 9999999999.),
            'grade_evaluation_id' => GradeEvaluation::factory(),
        ];
    }
}
