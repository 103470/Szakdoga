<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Brands\Vintage;

class BrandVintageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vintages = [
            [
                'type_id'    => 4,
                'name'       => '3',
                'frame'      => 'e21',
                'body_type'  => 'Sedan',
                'year_from'  => 1975,
                'month_from' => 6,
                'year_to'    => 1984,
                'month_to'   => 3,
            ],
            [
                'type_id'    => 4,
                'name'       => '3',
                'frame'      => 'e36',
                'body_type'  => 'Sedan',
                'year_from'  => 1990,
                'month_from' => 9,
                'year_to'    => 1998,
                'month_to'   => 11,
            ],
            [
                'type_id'    => 4,
                'name'       => '3',
                'frame'      => 'e46',
                'body_type'  => 'Sedan',
                'year_from'  => 1997,
                'month_from' => 12,
                'year_to'    => 2005,
                'month_to'   => 5,
            ],

        ];

        foreach ($vintages as $v) {
            Vintage::create([
                'type_id'    => $v['type_id'],
                'name'       => $v['name'],
                'frame'      => $v['frame'],
                'body_type'  => $v['body_type'],
                'year_from'  => $v['year_from'],
                'month_from' => $v['month_from'],
                'year_to'    => $v['year_to'],
                'month_to'   => $v['month_to'],
                'updated_by' => null,
                'deleted_by' => null,
            ]);
        }
    }
}
