<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DevicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Get user IDs excluding the admin (ID 1)
        $userIds = \App\Models\User::where('id', '>', 1)->pluck('id')->toArray();

        foreach (range(1, 20) as $index) {
            $deviceId = DB::table('devices')->insertGetId([
                'device_name' => $faker->unique()->word,
                'model' => $faker->word,
                'picture' => $faker->imageUrl(),
                'os' => $faker->word,
                'ui' => $faker->word,
                'dimensions' => $faker->word,
                'weight' => $faker->randomFloat(2, 100, 1000),
                'color' => $faker->word,
                'sim' => $faker->word,
                'cpu' => $faker->word,
                'gpu' => $faker->word,
                'size' => $faker->randomFloat(2, 4, 7),
                'resolution' => $faker->word,
                'ram' => $faker->randomFloat(1, 1, 16),
                'rom' => $faker->randomFloat(1, 16, 512),
                'sdcard' => $faker->randomFloat(1, 16, 256),
                'bluetooth' => $faker->word,
                'wifi' => $faker->word,
                'battery' => $faker->randomFloat(1, 1000, 10000),
                'price' => $faker->randomFloat(2, 100, 2000),
                'suggest_price' => $faker->randomFloat(2, 80, 1800),
                'user_id' => $faker->randomElement($userIds),
                'status' => $faker->randomElement(['Pending','Available']),
            ]);

            // Output the inserted device ID for reference
            echo "Inserted Device ID: $deviceId\n";
        }
    }
}
