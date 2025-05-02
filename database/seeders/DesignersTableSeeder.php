<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignersTableSeeder extends Seeder
{
    public function run()
    {
        $designers = [
            [
                'name' => 'Haruto Tanaka',
                'country' => 'Japan',
                'origin_city' => 'Kyoto',
                'specialty' => 'Oriental',
                'photo' => file_get_contents(public_path('designers/1.jpg')),
                'description' => 'Spesialis desain rumah tradisional Jepang...',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Budi Santoso',
                'country' => 'Indonesia',
                'origin_city' => 'Yogyakarta',
                'specialty' => 'Traditional',
                'photo' => file_get_contents(public_path('designers/2.jpg')),
                'description' => 'Ahli arsitektur Jawa tradisional...',
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'name' => 'Giovanni Rossi',
                'country' => 'Italy',
                'origin_city' => 'Florence',
                'specialty' => 'Classic',
                'photo' => file_get_contents(public_path('designers/3.jpg')),
                'description' => 'Master desain Renaissance dengan kolom ionic, detail cornice, dan simetri sempurna. Karya: Vila di Tuscany.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'name' => 'Lina Wong',
                'country' => 'Singapore',
                'origin_city' => 'Singapore',
                'specialty' => 'Modern',
                'photo' => file_get_contents(public_path('designers/4.jpg')),
                'description' => 'Pakar hunian vertikal dengan konsep green architecture. Mengintegrasikan sky garden dan solar panel.',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            [
                'name' => 'Lidwina Eleonora',
                'country' => 'Norway',
                'origin_city' => 'Jakarta',
                'specialty' => 'Medieval',
                'photo' => file_get_contents(public_path('designers/lidwinae.jpg')),
                'description' => 'Spesialis renovasi bangunan abad pertengahan dengan material stone masonry dan timber framing.',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('designers')->insert($designers);
    }
}