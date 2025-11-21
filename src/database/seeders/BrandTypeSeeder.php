<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brands\Type;

class BrandTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $types = [
            ['brand_id' => 3, 'name' => '1'],
            ['brand_id' => 3, 'name' => '2'],
            ['brand_id' => 3, 'name' => '2.5-3.2'],
            ['brand_id' => 3, 'name' => '3'],
            ['brand_id' => 3, 'name' => '4'],

            ['brand_id' => 2, 'name' => '50'],
            ['brand_id' => 2, 'name' => '60'],
            ['brand_id' => 2, 'name' => '72'],
            ['brand_id' => 2, 'name' => '75'],
            ['brand_id' => 2, 'name' => '80'],
        ];

        foreach ($types as $type) {
            Type::create([
                'brand_id' => $type['brand_id'],
                'name'     => $type['name'],
            ]);
        }
    }
}
