<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Design;

class DesignsTableSeeder extends Seeder
{
    public function run(): void
    {
        $designs = [
            [
                'name' => 'Two Houses',
                'country' => 'Japan',
                'specialty' => 'Oriental',
                'photo' => file_get_contents(public_path('designs/houses.jpg')),
            ],
            [
                'name' => 'Joglo',
                'country' => 'Indonesia',
                'specialty' => 'Traditional',
                'photo' => file_get_contents(public_path('designs/joglo.jpg')),
            ],
            [
                'name' => 'Pantheon',
                'country' => 'Italy',
                'specialty' => 'Classic',
                'photo' => file_get_contents(public_path('designs/pantheon.jpg')),
            ],
            [
                'name' => 'Modern',
                'country' => 'Singapore',
                'specialty' => 'Modern',
                'photo' => file_get_contents(public_path('designs/modern.jpg')),
            ],
            [
                'name' => 'Blue House',
                'country' => 'Norway',
                'specialty' => 'Medieval',
                'photo' => file_get_contents(public_path('designs/blue.jpg')),
            ]
        ];

        foreach ($designs as $designData) {
            Design::create($designData);
        }
    }
}
