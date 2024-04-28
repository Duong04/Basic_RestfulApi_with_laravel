<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use App\Models\Classe;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        for ($i = 1; $i <= 100; $i++) {
            Classe::create([
                'name' => $faker->sentence(2),
                'code' => $faker->unique()->randomNumber(6),
                'start_date' => $faker->dateTimeBetween('-1 month', '+1 month'),
                'end_date' => $faker->dateTimeBetween('+1 month', '+3 months'),
                'schedule' => $faker->randomElement(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday']),
                'description' => $faker->paragraph(3),
            ]);
        }
    }
}
