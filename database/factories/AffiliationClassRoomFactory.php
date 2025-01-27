<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Affiliation;
use App\Models\AffiliationClassRoom;
use App\Models\ClassRoom;

class AffiliationClassRoomFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = AffiliationClassRoom::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'affiliation_id' => Affiliation::factory(),
            'class_room_id' => ClassRoom::factory(),
            'rent_price' => fake()->randomFloat(0, 0, 9999999999.),
            'reg_date' => fake()->date(),
            'start_date' => fake()->date(),
            'end_date' => fake()->date(),
            'start_time' => fake()->time(),
            'end_time' => fake()->time(),
            'period' => fake()->word(),
        ];
    }
}
