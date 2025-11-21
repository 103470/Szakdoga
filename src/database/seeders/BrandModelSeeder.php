<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brands\BrandModel;

class BrandModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $models = [
            [
                'type_id'     => 4,
                'name'        => '318i',
                'ccm'         => 1895,
                'kw_hp'       => '87/118',
                'engine_type' => 'm43b19',
                'fuel_type_id'=> 1, 
                'year_from'   => 1997,
                'month_from'  => 12,
                'year_to'     => 2001,
                'month_to'    => 9,
                'frame'       => 'e46',
                'body_type'   => 'Sedan',
            ],
            [
                'type_id'     => 4,
                'name'        => '320i',
                'ccm'         => 2171,
                'kw_hp'       => '125/170',
                'engine_type' => 'm54b22',
                'fuel_type_id'=> 1,
                'year_from'   => 2000,
                'month_from'  => 9,
                'year_to'     => 2005,
                'month_to'    => 2,
                'frame'       => 'e46',
                'body_type'   => 'Sedan',
            ],

        ];

        foreach ($models as $model) {
            BrandModel::create(array_merge($model, [
                'updated_by' => null,
                'deleted_by' => null,
            ]));
        }
    }
}
