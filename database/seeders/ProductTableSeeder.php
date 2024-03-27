<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Faker\Factory as Faker;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        for ($i = 1; $i <= 5; $i++) {
            for ($j = 0; $j < 5; $j++) {
                Product::create([
                    'name' => $faker->word,
                    'price' => $faker->randomNumber(2),
                    'stock' => $faker->randomNumber(2),
                    'category_id' => $i,
                    'brand_id' => $faker->numberBetween(1, 10),
                    'user_id' => $faker->numberBetween(1,2)
                ]);
            }
        }

    }
}
