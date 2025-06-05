<?php

namespace App\Http\Controllers\Api;
use App\Models\Designer;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DesignerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $query = User::with('designer')
        ->where('role', 'designer')
        ->select('id', 'name', 'origin_city', 'country', 'avatar');

    // Search by name
    if ($request->filled('search')) {
    $search = $request->search;
    $query->where(function ($q) use ($search) {
        $q->where('name', 'like', '%' . $search . '%')
          ->orWhere('origin_city', 'like', '%' . $search . '%')
          ->orWhere('country', 'like', '%' . $search . '%')
          ->orWhereHas('designer', function ($q2) use ($search) {
              $q2->where('specialty', 'like', '%' . $search . '%');
          });
    });
    }

    if ($request->filled('sort')) {
        if ($request->sort === 'az') {
            $query->orderBy('name', 'asc');
        } elseif ($request->sort === 'za') {
            $query->orderBy('name', 'desc');
        }
    }

    $designers = $query->get()->map(function ($designer) {
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
public function showPreviewPortfolio()
{
    $designers = Designer::with(['user' => function($query) {
            $query->select('id', 'name', 'email', 'avatar', 'origin_city', 'country');
        }])
        ->select('user_id', 'specialty', 'portfolio_path')
        ->get()
        ->map(function ($designer) {
            return [
                'id' => $designer->user->id,
                'name' => $designer->user->name,
                'email' => $designer->user->email,
                'origin_city' => $designer->user->origin_city ?? null,
                'country' => $designer->user->country ?? null,
                'avatar_url' => $designer->user->avatar ? \Storage::url($designer->user->avatar) : null,
                'specialty' => $designer->specialty,
                'portfolio_url' => $designer->portfolio_path ? asset('storage/' . $designer->portfolio_path) : null,
                'portfolio_filename' => $designer->portfolio_path ? basename($designer->portfolio_path) : null,
            ];
        });

    return response()->json([
        'success' => true,
        'data' => $designers
    ]);
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
public function updatePortfolio(Request $request, $id)
{
    $request->validate([
        'portfolio' => 'required|file|mimes:pdf,jpg,jpeg,png,gif|max:16384',
    ]);

    $designer = Designer::where('user_id', $id)->firstOrFail();

    // Hapus file lama jika ada
    if ($designer->portfolio_path) {
        \Storage::disk('public')->delete($designer->portfolio_path);
    }

    // Simpan file baru
    $path = $request->file('portfolio')->store('designers/portfolios', 'public');
    $designer->portfolio_path = $path;
    $designer->save();

    return response()->json([
        'success' => true,
        'portfolio' => [
            'url' => asset('storage/' . $path),
            'filename' => basename($path),
        ]
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
