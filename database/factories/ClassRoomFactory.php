<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\ClassRoom;

class ClassRoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ClassRoom::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'capacity' => fake()->numberBetween(-1000, 1000),
            'status' => fake()->word(),
            'room_no' => fake()->numberBetween(-1000, 1000),
        ];
    }
}
