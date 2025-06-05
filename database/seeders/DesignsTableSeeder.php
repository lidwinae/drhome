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
        if (!Storage::disk('public')->exists('designs/files')) {
            Storage::disk('public')->makeDirectory('designs/files');
        }

        $designs = [
            [
                'name' => 'Two Houses',
                'country' => 'Japan',
                'specialty' => 'Oriental',
                'description' => 'Traditional Japanese houses with modern touch. Available with 1 bedroom, 1 bathroom, and a spacious living area. Perfect for families or as a vacation home. Price: Rp1.500.000.000',
                'photo_path' => $this->storeImage('designs/houses.jpg'),
                'preview_path' => $this->storeVideo('designs/houses-preview.mp4'),
                'file_path' => $this->storeFile('designs/houses.pdf'),
            ],
            [
                'name' => 'Joglo',
                'country' => 'Indonesia',
                'specialty' => 'Traditional',
                'description' => 'Classic traditional Javanese joglo house design. Available with 1 bedroom, 1 bathroom, and a spacious living area. Perfect for families or as a vacation home. Price: Rp1.000.000.000',
                'photo_path' => $this->storeImage('designs/joglo.jpg'),
                'preview_path' => $this->storeVideo('designs/joglo-preview.mp4'),
                'file_path' => $this->storeFile('designs/joglo.pdf'),
            ],
            [
                'name' => 'Pantheon',
                'country' => 'Italy',
                'specialty' => 'Classic',
                'description' => 'Ancient Roman architecture inspiration. Beautifully designed with a garden. Perfect for those who appreciate classic architecture. Price: Rp500.000.000',
                'photo_path' => $this->storeImage('designs/pantheon.jpg'),
                'preview_path' => $this->storeVideo('designs/pantheon-preview.mp4'),
                'file_path' => $this->storeFile('designs/pantheon.pdf'),
            ],
            [
                'name' => 'Modern',
                'country' => 'Singapore',
                'specialty' => 'Modern',
                'description' => 'Contemporary urban house design. Available with 1 bedroom, 1 bathroom, and a spacious living area. Perfect for urban living. Have a parking space for 1 car. Price: Rp1.000.000.000',
                'photo_path' => $this->storeImage('designs/modern.jpg'),
                'preview_path' => $this->storeVideo('designs/modern-preview.mp4'),
                'file_path' => $this->storeFile('designs/modern.pdf'),
            ],
            [
                'name' => 'Blue House',
                'country' => 'Norway',
                'specialty' => 'Medieval',
                'description' => 'Nordic style wooden house with blue accents. Available with 3 bedrooms, 2 bathrooms, and a spacious living area. Perfect for families or as a vacation home. Have a parking space for 1 car. Price: Rp2.000.000.000',
                'photo_path' => $this->storeImage('designs/blue.jpg'),
                'preview_path' => $this->storeVideo('designs/blue-preview.mp4'),
                'file_path' => $this->storeFile('designs/blue.pdf'),
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

    /**
     * Store file (e.g. PDF) to storage and return the path
     */
    private function storeFile(string $publicPath): ?string
    {
        if (!file_exists(public_path($publicPath))) {
            return null;
        }

        $storagePath = 'designs/files/' . basename($publicPath);
        Storage::disk('public')->put(
            $storagePath,
            file_get_contents(public_path($publicPath))
        );

        return $storagePath;
    }
}
