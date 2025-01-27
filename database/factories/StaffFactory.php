<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Position;
use App\Models\Staff;

class StaffFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Staff::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'staff_salary' => fake()->randomFloat(0, 0, 9999999999.),
            'staff_percentage' => fake()->randomFloat(0, 0, 9999999999.),
            'position_id' => Position::factory(),
            'is_teacher' => fake()->boolean(),
        ];
    }
}
