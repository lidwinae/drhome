<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Design;
use Illuminate\Support\Facades\Storage;

class DesignsTableSeeder extends Seeder
{
    public function run(): void
    {
        // Pastikan folder storage sudah ada
        if (!Storage::disk('public')->exists('designs/photos')) {
            Storage::disk('public')->makeDirectory('designs/photos');
        }
        if (!Storage::disk('public')->exists('designs/previews')) {
            Storage::disk('public')->makeDirectory('designs/previews');
        }

        $designs = [
            [
                'name' => 'Two Houses',
                'country' => 'Japan',
                'specialty' => 'Oriental',
                'description' => 'Traditional Japanese houses with modern touch',
                'photo_path' => $this->storeImage('designs/houses.jpg'),
                'preview_path' => $this->storeVideo('designs/houses-preview.mp4'), // Contoh video
            ],
            [
                'name' => 'Joglo',
                'country' => 'Indonesia',
                'specialty' => 'Traditional',
                'description' => 'Classic Javanese joglo house design',
                'photo_path' => $this->storeImage('designs/joglo.jpg'),
                'preview_path' => $this->storeVideo('designs/joglo-preview.mp4'),
            ],
            [
                'name' => 'Pantheon',
                'country' => 'Italy',
                'specialty' => 'Classic',
                'description' => 'Ancient Roman architecture inspiration',
                'photo_path' => $this->storeImage('designs/pantheon.jpg'),
                'preview_path' => $this->storeVideo('designs/pantheon-preview.mp4'),
            ],
            [
                'name' => 'Modern',
                'country' => 'Singapore',
                'specialty' => 'Modern',
                'description' => 'Contemporary urban house design',
                'photo_path' => $this->storeImage('designs/modern.jpg'),
                'preview_path' => $this->storeVideo('designs/modern-preview.mp4'),
            ],
            [
                'name' => 'Blue House',
                'country' => 'Norway',
                'specialty' => 'Medieval',
                'description' => 'Nordic style wooden house with blue accents',
                'photo_path' => $this->storeImage('designs/blue.jpg'),
                'preview_path' => $this->storeVideo('designs/blue-preview.mp4'),
            ]
        ];

        foreach ($designs as $designData) {
            Design::create($designData);
        }
    }

    /**
     * Store image to storage and return the path
     */
    private function storeImage(string $publicPath): ?string
    {
        if (!file_exists(public_path($publicPath))) {
            return null;
        }

        $storagePath = 'designs/photos/' . basename($publicPath);
        Storage::disk('public')->put(
            $storagePath,
            file_get_contents(public_path($publicPath))
        );

        return $storagePath;
    }

    /**
     * Store video to storage and return the path
     */
    private function storeVideo(string $publicPath): ?string
    {
        if (!file_exists(public_path($publicPath))) {
            return null;
        }

        $storagePath = 'designs/previews/' . basename($publicPath);
        Storage::disk('public')->put(
            $storagePath,
            file_get_contents(public_path($publicPath))
        );

        return $storagePath;
    }
}
