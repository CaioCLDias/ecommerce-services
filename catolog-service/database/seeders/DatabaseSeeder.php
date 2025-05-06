<?php

namespace Database\Seeders;

use App\Models\Address;
use App\Models\Product;
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
        User::factory(10)->create();
        Product::factory(10)->create();
      
        $userIds = User::pluck('id')->toArray();

        Address::factory(10)->create([
            'is_default' => true,
        ])->each(function ($address) use ($userIds) {
            $address->user_id = fake()->randomElement($userIds);
            $address->save();
        });
    }
}
