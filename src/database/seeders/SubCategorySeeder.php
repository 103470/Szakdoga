<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubCategory;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = [

            ['category_id' => 2, 'name' => 'Fékbetét'],
            ['category_id' => 2, 'name' => 'Féknyereg'],
            ['category_id' => 2, 'name' => 'Féktárcsa'],
            ['category_id' => 2, 'name' => 'Rögzítőfék'],
            ['category_id' => 2, 'name' => 'Kézifékkar'],

            ['category_id' => 12, 'name' => 'Gyújtógyertya'],
            ['category_id' => 12, 'name' => 'Izzítógyertya'],
            ['category_id' => 12, 'name' => 'Gyújtótekercs'],
            ['category_id' => 12, 'name' => 'Izzító relé'],
            ['category_id' => 12, 'name' => 'Megszakító'],
        ];

        foreach ($subcategories as $subcategory) {
            SubCategory::create([
                'category_id'  => $subcategory['category_id'],
                'name'         => $subcategory['name'],
                'fuel_type_id' => 4,
                'updated_by'   => null,
                'deleted_by'   => null,
            ]);
        }
    }
}
