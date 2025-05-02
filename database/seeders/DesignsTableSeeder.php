<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DesignsTableSeeder extends Seeder
{
    public function run()
    {
        $designs = [
            [
                'name' => 'Two Houses',
                'country' => 'Japan',
                'specialty' => 'Oriental',
                'photo' => file_get_contents(public_path('designs/houses.jpg')),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Joglo',
                'country' => 'Indonesia',
                'specialty' => 'Traditional',
                'photo' => file_get_contents(public_path('designs/joglo.jpg')),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Pantheon',
                'country' => 'Italy',
                'specialty' => 'Classic',
                'photo' => file_get_contents(public_path('designs/pantheon.jpg')),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Modern',
                'country' => 'Singapore',
                'specialty' => 'Modern',
                'photo' => file_get_contents(public_path('designs/modern.jpg')),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Blue House',
                'country' => 'Norway',
                'specialty' => 'Medieval',
                'photo' => file_get_contents(public_path('designs/blue.jpg')),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];

        DB::table('designs')->insert($designs);
    }
}