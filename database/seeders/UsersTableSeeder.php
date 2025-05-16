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
        // client
        User::create([
            'name' => 'Klien',
            'email' => 'klien@example.com',
            'password' => Hash::make('rahasia1'),
            'role' => 'client',
            'status' => 'active',
            'country' => 'Germany',
            'origin_city' => 'Munich',
            'photo' => file_get_contents(public_path('designers/lidwinae.jpg')),
            'background_image' => file_get_contents(public_path('designs/pantheon.jpg')),
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
            'photo' => file_get_contents(public_path('designers/lidwinae.jpg')),
            'background_image' => file_get_contents(public_path('designs/pantheon.jpg')),
        ]);

        Contractor::create([
            'user_id' => $user->id,
            'specialty' => 'medieval',
            'description' => 'Specialist in medieval-style construction and restoration based in Munich, Germany.',
            'portfolio' => file_get_contents(public_path('portfolio/Portfolio.pdf')),
        ]);
    }
}
