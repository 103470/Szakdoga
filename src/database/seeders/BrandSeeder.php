<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brand;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Alfa Romeo',    'slug' => 'alfa-romeo',   'logo' => 'brands/alfa.png'],
            ['name' => 'Audi',          'slug' => 'audi',         'logo' => 'brands/Audi.png'],
            ['name' => 'BMW',           'slug' => 'bmw',          'logo' => 'brands/BMW.png'],
            ['name' => 'Chevrolet',     'slug' => 'chevrolet',    'logo' => 'brands/chewrolet.png'],
            ['name' => 'Citroen',       'slug' => 'citroen',      'logo' => 'brands/citroen.png'],
            ['name' => 'Dacia',         'slug' => 'dacia',        'logo' => 'brands/dacia.png'],
            ['name' => 'Daewoo',        'slug' => 'daewoo',       'logo' => 'brands/daewoo.png'],
            ['name' => 'Fiat',          'slug' => 'fiat',         'logo' => 'brands/fiat.png'],
            ['name' => 'Ford',          'slug' => 'ford',         'logo' => 'brands/ford.png'],
            ['name' => 'Honda',         'slug' => 'honda',        'logo' => 'brands/honda.png'],
            ['name' => 'Hyundai',       'slug' => 'hyundai',      'logo' => 'brands/hyundai.png'],
            ['name' => 'Jeep',          'slug' => 'jeep',         'logo' => 'brands/jeep.png'],
            ['name' => 'VW',            'slug' => 'vw',           'logo' => 'brands/vw.png'],
        ];

         foreach ($brands as $brand) {
            Brand::create($brand); 
        }
        
    }
}
