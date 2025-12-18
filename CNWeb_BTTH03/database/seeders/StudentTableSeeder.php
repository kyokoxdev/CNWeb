<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class StudentTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $classIds = DB::table("classes")->pluck("id")->toArray();
        for ($i = 0; $i < 10; $i++) {
            DB::table("students")->insert([
                'first_name' => $faker->firstName,
                'last_name' => $faker->lastName,
                'date_of_birth' => $faker->date('Y-m-d', '-5 years'),
                'parent_phone' => $faker->phoneNumber,
                'class_id' => $faker->randomElement($classIds)
            ]);
        }
    }
}
