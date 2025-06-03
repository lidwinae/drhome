<?php

namespace App\Http\Controllers\Api;
use App\Models\Contractor;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ContractorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    $contractors = User::with('contractor')
        ->where('role', 'contractor')
        ->select('id', 'name', 'origin_city', 'country', 'avatar')
        ->get()
        ->map(function ($contractor) {
            return [
                'id' => $contractor->id,
                'name' => $contractor->name,
                'origin_city' => $contractor->origin_city,
                'country' => $contractor->country,
                'avatar_url' => $contractor->avatar ? \Storage::url($contractor->avatar) : null,
                'specialty' => $contractor->contractor->specialty ?? null,
            ];
        });

    return response()->json([
        'success' => true,
        'data' => $contractors
    ]);
}

    public function showPreviewPortfolio()
    {
        $contractors = Contractor::with(['user' => function($query) {
                $query->select('id', 'name', 'email');
            }])
            ->select('user_id', 'specialty', 'portfolio')
            ->get()
            ->map(function ($contractor) {
                return [
                    'id' => $contractor->user->id,
                    'name' => $contractor->user->name,
                    'email' => $contractor->user->email,
                    'specialty' => $contractor->specialty,
                    'portfolio' => $contractor->portfolio ? base64_encode($contractor->portfolio) : null
                ];
            });

        return response()->json($contractors);
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
    public function show($id)
{
    // Ambil user dengan relasi contractor
    $contractor = User::with('contractor')->where('role', 'contractor')->findOrFail($id);

    $portfolio = null;
    if ($contractor->contractor && $contractor->contractor->portfolio_path) {
        $portfolio = [
            'url' => $contractor->contractor->portfolio_path ? asset('storage/' . $contractor->contractor->portfolio_path) : null,
            'filename' => basename($contractor->contractor->portfolio_path),
        ];
    }

    return response()->json([
        'success' => true,
        'data' => [
            'id' => $contractor->id,
            'name' => $contractor->name,
            'role' => $contractor->role,
            'country' => $contractor->country,
            'origin_city' => $contractor->origin_city,
            'avatar_url' => $contractor->avatar ? asset('storage/' . $contractor->avatar) : null,
            'background_url' => $contractor->background ? asset('storage/' . $contractor->background) : null,
            'specialty' => $contractor->contractor->specialty ?? null,
            'description' => $contractor->contractor->description ?? null,
            'portfolio' => $portfolio,
            'created_at' => $contractor->contractor->created_at ?? null,
            'updated_at' => $contractor->contractor->updated_at ?? null,
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
