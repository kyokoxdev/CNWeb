<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
class MedicinesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        for ($i = 0; $i < 10; $i++) {
            DB::table("medicines")->insert([
                "name"=> $faker->word,
                "brand"=> $faker->word,
                "dosage"=> $faker->word,
                "form"=> $faker->word,
                "price"=>$faker->randomFloat(2,0,0),
                "stock"=>$faker->numberBetween(1,100),
            ]);
        }
    }
}
