<?php

namespace Database\Seeders;

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
        $this->call([
            FuelTypeSeeder::class,
            BrandSeeder::class,
            RareBrandSeeder::class,
            CategorySeeder::class,
            SubCategorySeeder::class,
            ProductCategorySeeder::class,
            BrandTypeSeeder::class,
            BrandVintageSeeder::class,
            BrandModelSeeder::class,
        ]);

        User::factory()->create([
           //
        ]);
    }
}
