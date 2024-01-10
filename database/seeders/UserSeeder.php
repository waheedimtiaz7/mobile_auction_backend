<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'admin@auction.com',
        ],[
            'fname' => 'Admin',
            'lname' => 'User',
            'email' => 'admin@auction.com',
            'password' => Hash::make(123456),
            'type' => 'Admin',
            'status' => 1,
        ]);

        $faker = Faker::create();

        foreach (range(1, 20) as $index) {
            DB::table('users')->insert([
                'fname' => $faker->firstName,
                'lname' => $faker->lastName,
                'email' => $faker->unique()->safeEmail,
                'password' => Hash::make('password'), // You may customize the default password
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
                'picture' => $faker->imageUrl(),
                'status' => 1,
                'type' => 'Customer',
            ]);
        }
    }
}
