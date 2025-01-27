<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudentAttendance;

class StudentAttendanceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = StudentAttendance::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'course_id' => Course::factory(),
            'student_id' => Student::factory(),
            'attendance_date' => fake()->date(),
            'attendance_status' => fake()->word(),
        ];
    }
}
