<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ProductCategory;

class ProductCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productCategories = [
            ['subcategory_id' => 4, 'name' => 'Összes termék'],
            ['subcategory_id' => 4, 'name' => 'Beépítési oldal: elsőtengely'],
            ['subcategory_id' => 4, 'name' => 'Beépítési oldal: hátsótengely'],

            ['subcategory_id' => 5, 'name' => 'Összes termék'],
            ['subcategory_id' => 5, 'name' => 'Beépítési oldal: elsőtengely jobb'],
            ['subcategory_id' => 5, 'name' => 'Beépítési oldal: elsőtengely bal'],
            ['subcategory_id' => 5, 'name' => 'Beépítési oldal: hátsótengely jobb'],
            ['subcategory_id' => 5, 'name' => 'Beépítési oldal: hátsótengely bal'],
        ];

        foreach ($productCategories as $productCategory) {
            ProductCategory::create([
                'subcategory_id' => $productCategory['subcategory_id'],
                'name'           => $productCategory['name'],
                'updated_by'     => null,
                'deleted_by'     => null,
            ]);
        }
    }
}
