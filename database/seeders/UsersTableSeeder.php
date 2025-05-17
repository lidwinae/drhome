<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Contractor;
use App\Models\Designer;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // admin
        User::create([
            'name' => 'ADMIN',
            'email' => 'admin@test.com',
            'password' => Hash::make('rahasia1'),
            'role' => 'admin',
            'status' => 'active',
            'country' => 'Indonesia',
            'origin_city' => 'Jakarta',
            'photo' => null,
            'background_image' => null,
        ]);
        
        // client
        User::create([
            'name' => 'Klien',
            'email' => 'klien@example.com',
            'password' => Hash::make('rahasia1'),
            'role' => 'client',
            'status' => 'active',
            'country' => 'Malaysia',
            'origin_city' => 'Kuala Lumpur',
            'photo' => null,
            'background_image' => null,
        ]);

        // kontraktor
        $user = User::create([
            'name' => 'Lidwina',
            'email' => 'lidwina@example.com',
            'password' => Hash::make('rahasia1'),
            'role' => 'contractor',
            'status' => 'active',
            'country' => 'Germany',
            'origin_city' => 'Munich',
            'photo' => null,
            'background_image' => null,
        ]);

        Contractor::create([
            'user_id' => $user->id,
            'specialty' => 'medieval',
            'description' => 'Specialist in medieval-style construction and restoration based in Munich, Germany.',
            'portfolio' => file_get_contents(public_path('portfolio/Portfolio.pdf')),
        ]);

        // designer
        $user = User::create([
            'name' => 'Hayao Miyazaki',
            'email' => 'hayaomiyazaki@example.com',
            'password' => Hash::make('rahasia1'),
            'role' => 'designer',
            'status' => 'active',
            'country' => 'Japan',
            'origin_city' => 'Kyoto',
            'photo' => null,
            'background_image' => null,
        ]);

        Designer::create([
            'user_id' => $user->id,
            'specialty' => 'oriental / traditional',
            'description' => 'Specialist in oriental-style house in Japan.',
            'portfolio' => file_get_contents(public_path('portfolio/Portfolio.pdf')),
        ]);
    }
}
