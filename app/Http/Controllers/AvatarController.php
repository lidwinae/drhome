<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Designer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AvatarController extends Controller {
    public function updateAvatar(Request $request)
    {
        $validated = $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg|max:16384'
        ]);

        // Hapus file lama jika ada
        if (auth()->user()->avatar) {
            Storage::delete('public/' . auth()->user()->avatar);
        }

        // Simpan file baru
        $path = $request->file('avatar')->store('avatars', 'public');

        // Update database dengan PATH RELATIF
        auth()->user()->update(['avatar' => $path]);

        return response()->json([
            'avatar_url' => asset("storage/$path") // Full URL untuk frontend
        ]);
    }

    public function updateBackground(Request $request)
    {
        $validated = $request->validate([
            'background' => 'required|image|mimes:jpeg,png,jpg|max:16384'
        ]);

        if (auth()->user()->background) {
            Storage::delete('public/' . auth()->user()->background);
        }

        $path = $request->file('background')->store('backgrounds', 'public');

        // Update database dengan PATH RELATIF
        auth()->user()->update(['background' => $path]);

        return response()->json([
            'background_url' => asset("storage/$path")
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'origin_city' => 'required|string|max:255',
            'country' => 'required|string|max:255',
        ]);
        $user->name = $request->name;
        $user->origin_city = $request->origin_city;
        $user->country = $request->country;
        $user->save();

        return response()->json([
            'name' => $user->name,
            'origin_city' => $user->origin_city,
            'country' => $user->country,
        ]);
    }

    public function updateSpecialty(Request $request)
{
    $request->validate([
        'specialty' => 'required|string|max:50',
    ]);

    $designer = Designer::firstOrCreate(
        ['user_id' => auth()->id()],
        ['specialty' => '', 'description' => '']
    );
    $designer->specialty = $request->specialty;
    $designer->save();

    return response()->json([
        'specialty' => $designer->specialty,
    ]);
}

// Update About/Description
public function updateAbout(Request $request)
{
    $request->validate([
        'description' => 'required|string|max:1000',
    ]);

    $designer = Designer::firstOrCreate(
        ['user_id' => auth()->id()],
        ['specialty' => '', 'description' => '']
    );
    $designer->description = $request->description;
    $designer->save();

    return response()->json([
        'description' => $designer->description,
    ]);
}
}