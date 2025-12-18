<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class SalesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $medicineId = DB::table("medicines")->pluck("medicine_id")->toArray();
        for ($i = 0; $i < 10; $i++) {
            DB::table("sales")->insert([
                'medicine_id'=>$faker->randomElement($medicineId),
                'quantity'=>$faker->numberBetween(1,10),
                'sale_date'=>$faker->dateTime('now')->format('Y-m-d H:i:s'),
                'customer_phone' => $faker->phoneNumber()
            ]);
        }
    }
}
