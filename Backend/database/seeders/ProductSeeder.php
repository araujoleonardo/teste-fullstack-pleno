<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ProductSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $users = User::all();

        foreach ($users as $user) {
            // Gera entre 1 e 10 produtos para cada usuário
            $totalProducts = rand(1, 10);

            for ($i = 0; $i < $totalProducts; $i++) {
                Product::create([
                    'name'        => $faker->words(3, true),
                    'price'       => $faker->randomFloat(2, 10, 1000),
                    'description' => $faker->sentence(5),
                    'user_id'     => $user->id,
                ]);
            }
        }
    }
}
