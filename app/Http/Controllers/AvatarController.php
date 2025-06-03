<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
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
}