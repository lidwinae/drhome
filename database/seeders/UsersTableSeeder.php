<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
        // Setup storage directories if they don't exist
        $directories = ['public/avatars', 'public/backgrounds', 'public/portfolios'];
        foreach ($directories as $directory) {
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }
        }

        // Copy sample files to storage
        $this->copySampleFiles();

        // Background images available
        $backgrounds = ['bg1.png', 'bg2.png', 'bg3.png', 'bg4.png', 'bg5.png'];

        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('rahasia1'),
            'role' => 'admin',
            'status' => 'active',
            'country' => 'Indonesia',
            'origin_city' => 'Jakarta',
            'avatar' => 'avatars/lidwinaes.png',
            'background' => 'backgrounds/bg6.png',
        ]);
        
        // Clients
        User::create([
            'name' => 'Client One',
            'email' => 'client1@gmail.com',
            'password' => Hash::make('rahasia1'),
            'role' => 'client',
            'status' => 'active',
            'country' => 'United States',
            'origin_city' => 'New York',
            'avatar' => null,
            'background' => null,
        ]);

        User::create([
            'name' => 'Client Two',
            'email' => 'client2@gmail.com',
            'password' => Hash::make('rahasia1'),
            'role' => 'client',
            'status' => 'active',
            'country' => 'United Kingdom',
            'origin_city' => 'London',
            'avatar' => null,
            'background' => null,
        ]);

        // Contractors
        $contractors = [
            [
                'name' => 'Lidwina Contractor',
                'email' => 'lidwina.contractor@gmail.com',
                'country' => 'Germany',
                'city' => 'Munich',
                'avatar' => 'lidwinae.jpg',
                'specialty' => 'medieval',
                'description' => 'Specialist in medieval-style construction and restoration.',
                'portfolio' => 'portfolio.pdf',
                'background' => 'backgrounds/bg2.png'
            ],
            [
                'name' => 'Argenta Builder',
                'email' => 'argenta.builder@gmail.com',
                'country' => 'Italy',
                'city' => 'Rome',
                'avatar' => 'argenta.jpg',
                'specialty' => 'classical',
                'description' => 'Expert in classical Roman architecture and construction.',
                'portfolio' => 'portfolio1.pdf',
                'background' => 'backgrounds/bg3.png'
            ],
            [
                'name' => 'Steve Construct',
                'email' => 'steve.construct@gmail.com',
                'country' => 'USA',
                'city' => 'Chicago',
                'avatar' => 'steve.png',
                'specialty' => 'modern',
                'description' => 'Modern construction specialist with 15 years experience.',
                'portfolio' => 'portfolio2.pdf',
                'background' => 'backgrounds/bg4.png'
            ]
        ];

        foreach ($contractors as $contractor) {
            $user = User::create([
                'name' => $contractor['name'],
                'email' => $contractor['email'],
                'password' => Hash::make('rahasia1'),
                'role' => 'contractor',
                'status' => 'active',
                'country' => $contractor['country'],
                'origin_city' => $contractor['city'],
                'avatar' => 'avatars/' . $contractor['avatar'],
                'background' => $contractor['background'],
            ]);

            Contractor::create([
                'user_id' => $user->id,
                'specialty' => $contractor['specialty'],
                'description' => $contractor['description'],
                'portfolio_path' => 'portfolios/' . $contractor['portfolio'],
            ]);
        }

        // Designers
        $designers = [
            [
                'name' => 'Hayao Miyazaki',
                'email' => 'hayao.design@gmail.com',
                'country' => 'Japan',
                'city' => 'Tokyo',
                'avatar' => 'hayao.png',
                'specialty' => 'oriental',
                'description' => 'Master of traditional Japanese architecture and design.',
                'portfolio' => 'portfolio3.pdf',
                'background' => 'backgrounds/bg1.png'
            ],
            [
                'name' => 'Cherry Blossom',
                'email' => 'cherry.design@gmail.com',
                'country' => 'Japan',
                'city' => 'Kyoto',
                'avatar' => 'cherry.png',
                'specialty' => 'minimalist',
                'description' => 'Minimalist Japanese design with natural elements.',
                'portfolio' => 'portfolio4.pdf',
                'background' => 'backgrounds/bg5.png'
            ],
            [
                'name' => 'Dr. Strange Designs',
                'email' => 'strange.design@gmail.com',
                'country' => 'USA',
                'city' => 'New York',
                'avatar' => 'drstrange.png',
                'specialty' => 'futuristic',
                'description' => 'Futuristic and avant-garde architectural designs.',
                'portfolio' => 'portfolio.pdf',
                'background' => 'backgrounds/bg7.png'
            ],
            [
                'name' => 'Samoyed Interiors',
                'email' => 'samoyed.design@gmail.com',
                'country' => 'Russia',
                'city' => 'Moscow',
                'avatar' => 'samoyed.png',
                'specialty' => 'luxury',
                'description' => 'Luxury interior designs with a cozy touch.',
                'portfolio' => 'portfolio1.pdf',
                'background' => 'backgrounds/bg8.png'
            ],
            [
                'name' => 'Corgi Architecture',
                'email' => 'corgi.design@gmail.com',
                'country' => 'UK',
                'city' => 'London',
                'avatar' => 'corgi.png',
                'specialty' => 'traditional',
                'description' => 'Traditional British architecture with modern comforts.',
                'portfolio' => 'portfolio2.pdf',
                'background' => 'backgrounds/bg9.png'
            ],
            [
                'name' => 'Malamute Structures',
                'email' => 'malamute.design@gmail.com',
                'country' => 'Canada',
                'city' => 'Vancouver',
                'avatar' => 'malamute.png',
                'specialty' => 'sustainable',
                'description' => 'Eco-friendly and sustainable building designs.',
                'portfolio' => 'portfolio3.pdf',
                'background' => 'backgrounds/bg10.png'
            ],
            [
                'name' => 'Hatsune Virtual',
                'email' => 'hatsune.design@gmail.com',
                'country' => 'Japan',
                'city' => 'Sapporo',
                'avatar' => 'hatsune.png',
                'specialty' => 'virtual',
                'description' => 'Virtual and augmented reality architectural visualization.',
                'portfolio' => 'portfolio4.pdf',
                'background' => 'backgrounds/bg11.png'
            ]
        ];

        foreach ($designers as $designer) {
            $user = User::create([
                'name' => $designer['name'],
                'email' => $designer['email'],
                'password' => Hash::make('rahasia1'),
                'role' => 'designer',
                'status' => 'active',
                'country' => $designer['country'],
                'origin_city' => $designer['city'],
                'avatar' => 'avatars/' . $designer['avatar'],
                'background' => $designer['background'],
            ]);

            Designer::create([
                'user_id' => $user->id,
                'specialty' => $designer['specialty'],
                'description' => $designer['description'],
                'portfolio_path' => 'portfolios/' . $designer['portfolio'],
            ]);
        }
    }

    /**
     * Copy sample files from public to storage
     */
protected function copySampleFiles(): void
{
    // Copy avatar images
    $avatarFiles = [
        'lidwinaes.png', 'lidwinae.jpg', 'hayao.png', 'argenta.jpg', 'cherry.png', 
        'steve.png', 'drstrange.png', 'samoyed.png', 'corgi.png', 
        'malamute.png', 'hatsune.png'
    ];

    foreach ($avatarFiles as $file) {
        $src = public_path("designers/{$file}");
        $dst = "avatars/{$file}";
        if (file_exists($src) && !Storage::disk('public')->exists($dst)) {
            Storage::disk('public')->put($dst, file_get_contents($src));
        }
    }

    // Copy background images
    $backgroundFiles = ['bg1.png', 'bg2.png', 'bg3.png', 'bg4.png', 'bg5.png', 'bg6.png', 'bg7.png', 'bg8.png', 'bg10.png', 'bg11.png'];
    foreach ($backgroundFiles as $file) {
        $src = public_path("background/{$file}");
        $dst = "backgrounds/{$file}";
        if (file_exists($src) && !Storage::disk('public')->exists($dst)) {
            Storage::disk('public')->put($dst, file_get_contents($src));
        }
    }

    // Copy portfolio files
    $portfolioFiles = [
        'portfolio.pdf', 'portfolio1.pdf', 'portfolio2.pdf', 
        'portfolio3.pdf', 'portfolio4.pdf'
    ];

    foreach ($portfolioFiles as $file) {
        $src = public_path("portfolio/{$file}");
        $dst = "portfolios/{$file}";
        if (file_exists($src) && !Storage::disk('public')->exists($dst)) {
            Storage::disk('public')->put($dst, file_get_contents($src));
        }
    }
}
}