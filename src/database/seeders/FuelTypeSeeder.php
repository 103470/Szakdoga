<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\FuelType;

class FuelTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $fuelTypes = [
            ['name' => 'Benzin', 'is_universal' => false],
            ['name' => 'Dízel', 'is_universal' => false],
            ['name' => 'Elektromos', 'is_universal' => false],
            ['name' => 'Univerzális', 'is_universal' => true],
        ];

        foreach ($fuelTypes as $type) {
            FuelType::create($type);
        }
    }
}
