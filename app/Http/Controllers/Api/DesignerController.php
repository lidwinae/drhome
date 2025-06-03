<?php

namespace App\Http\Controllers\Api;
use App\Models\Designer;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DesignerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index()
{
    $designers = User::with('designer')
        ->where('role', 'designer')
        ->select('id', 'name', 'origin_city', 'country', 'avatar')
        ->get()
        ->map(function ($designer) {
            return [
                'id' => $designer->id,
                'name' => $designer->name,
                'origin_city' => $designer->origin_city,
                'country' => $designer->country,
                'avatar_url' => $designer->avatar ? \Storage::url($designer->avatar) : null,
                'specialty' => $designer->designer->specialty ?? null,
            ];
        });

    return response()->json([
        'success' => true,
        'data' => $designers
    ]);
}
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    // GET /api/designers/{id}
public function show($id)
{
    // Ambil user dengan relasi designer
    $designer = User::with('designer')->where('role', 'designer')->findOrFail($id);

    $portfolio = null;
    if ($designer->designer && $designer->designer->portfolio_path) {
        $portfolio = [
            'url' => $designer->designer->portfolio_path ? asset('storage/' . $designer->designer->portfolio_path) : null,
            'filename' => basename($designer->designer->portfolio_path),
        ];
    }

    return response()->json([
        'success' => true,
        'data' => [
            'id' => $designer->id,
            'name' => $designer->name,
            'role' => $designer->role,
            'country' => $designer->country,
            'origin_city' => $designer->origin_city,
            'avatar_url' => $designer->avatar ? asset('storage/' . $designer->avatar) : null,
            'background_url' => $designer->background ? asset('storage/' . $designer->background) : null,
            'specialty' => $designer->designer->specialty ?? null,
            'description' => $designer->designer->description ?? null,
            'portfolio' => $portfolio,
            'created_at' => $designer->designer->created_at ?? null,
            'updated_at' => $designer->designer->updated_at ?? null,
        ]
    ]);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    // Update About and Specialty
public function patchUpdate(Request $request, $id)
{
    $designer = Designer::findOrFail($id);

    // Optional: pastikan hanya owner yang bisa update
    if ($designer->user_id !== Auth::id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $validated = $request->validate([
        'specialty' => 'sometimes|string|max:50',
        'description' => 'sometimes|string|max:1000',
    ]);

    if ($request->has('specialty')) {
        $designer->specialty = $request->specialty;
    }
    if ($request->has('description')) {
        $designer->description = $request->description;
    }
    $designer->save();

    return response()->json([
        'success' => true,
        'data' => $designer,
    ]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
