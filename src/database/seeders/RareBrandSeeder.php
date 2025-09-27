<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RareBrand;

class RareBrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rareBrands = [
            'Abarth',
            'Ac',
            'Acura',
            'Addax',
            'Aiways',
            'Aixam',
            'Alpina',
            'Alpine',
            'Amc',
            'Aro',
            'Artega',
            'Asia Motors',
            'Aston Martin',
            'Austin',
            'Austin-Healey',
            'Auto Union',
            'Autobianchi',
            'Avia',
            'B-on',
            'Bac',
            'Baic',
            'Barkas',
            'Baw',
            'Bedford',
            'Beijing',
            'Bentley',
            'Bertone',
            'Bestune',
            'Bitter',
            'Bizzarrini',
            'Bmc',
            'Bond',
            'Borgward',
            'Bristol',
            'Bugatti'
        ];

        foreach ($rareBrands as $name) {
            RareBrand::create(['name' => $name]);
        }
    }
}
