<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ClassRoom;
use App\Models\Course;
use App\Models\Diploma;
use App\Models\Staff;
use App\Models\Subject;

class CourseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Course::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'course_name' => fake()->word(),
            'teacher_id' => Staff::factory(),
            'subject_id' => Subject::factory(),
            'diploma_id' => Diploma::factory(),
            'class_room_id' => ClassRoom::factory(),
            'reg_date' => fake()->date(),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'start_time' => fake()->time(),
            'end_time' => fake()->time(),
            'period' => fake()->word(),
            'days' => '{}',
            'status' => fake()->word(),
        ];
    }
}
