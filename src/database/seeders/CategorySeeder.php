<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Olaj shop',
                'icon' => 'category/olaj.png',
            ],
            [
                'name' => 'Fék mechanika',
                'icon' => 'category/fek.png',
            ],
            [
                'name' => 'Lengéscsillpítók, autórugó, futómű',
                'icon' => 'category/lengescsillapito.png',
            ],
            [
                'name' => 'Autó Ékszíj, hosszbordás szíj, vezérműszíj, görgők',
                'icon' => 'category/szij.png',
            ],
            [
                'name' => 'Autófűtés, klíma, autó hűtőrendszerek',
                'icon' => 'category/hutes.png',
            ],
            [
                'name' => 'Téli autós termékek',
                'icon' => 'category/teli.png',
            ],
            [
                'name' => 'Autószűrők',
                'icon' => 'category/szurok.png',
            ],
            [
                'name' => 'Autó felfüggesztés, Kormányzás',
                'icon' => 'category/felfuggesztes.png',
            ],
            [
                'name' => 'Autó erőátvitel',
                'icon' => 'category/eroatvitel.png',
            ],
            [
                'name' => 'Motorblokk, autó motor tömítések',
                'icon' => 'category/motorblokk.png',
            ],
            [
                'name' => 'Akkumulátorok',
                'icon' => 'category/akkumulator.png',
            ],
            [
                'name' => 'Autó gyújtás, izzítás',
                'icon' => 'category/izzitas.png',
            ],
            [
                'name' => 'Kipufugó és elemei',
                'icon' => 'category/kipufogo.png',
            ],
        ];

        foreach ($categories as $data) {
            Category::create([
                'name' => $data['name'],
                'icon' => $data['icon'],
                
            ]);
        }
    }
}
