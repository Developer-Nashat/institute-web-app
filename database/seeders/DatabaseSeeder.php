<?php

namespace Database\Seeders;

use App\Models\Nationality;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Nationality::factory()->create([
            'name' => 'يمني'
        ]);

        Nationality::factory()->create([
            'name' => 'سعودي'
        ]);

        Nationality::factory()->create([
            'name' => 'عماني'
        ]);

        Nationality::factory()->create([
            'name' => 'أماراتي'
        ]);
    }
}
