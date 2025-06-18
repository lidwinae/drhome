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
    public function index(Request $request)
    {

            $query = User::with('contractor')
            ->where('role', 'contractor')
            ->select('id', 'name', 'origin_city', 'country', 'avatar');

        // Search by name
        if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
            ->orWhere('origin_city', 'like', '%' . $search . '%')
            ->orWhere('country', 'like', '%' . $search . '%')
            ->orWhereHas('contractor', function ($q2) use ($search) {
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

        $contractors = $query->get()->map(function ($contractor) {
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
                $query->select('id', 'name', 'email', 'avatar', 'origin_city', 'country');
            }])
            ->select('user_id', 'specialty', 'portfolio_path')
            ->get()
            ->map(function ($contractor) {
                return [
                    'id' => $contractor->user->id,
                    'name' => $contractor->user->name,
                    'email' => $contractor->user->email,
                    'origin_city' => $contractor->user->origin_city ?? null,
                    'country' => $contractor->user->country ?? null,
                    'avatar_url' => $contractor->user->avatar ? \Storage::url($contractor->user->avatar) : null,
                    'specialty' => $contractor->specialty,
                    'portfolio_url' => $contractor->portfolio_path ? asset('storage/' . $contractor->portfolio_path) : null,
                    'portfolio_filename' => $contractor->portfolio_path ? basename($contractor->portfolio_path) : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $contractors
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
    public function updatePortfolio(Request $request, $id)
    {
        $request->validate([
            'portfolio' => 'required|file|mimes:pdf,jpg,jpeg,png,gif|max:16384',
        ]);

        $contractor = Contractor::where('user_id', $id)->firstOrFail();

        // Hapus file lama jika ada
        if ($contractor->portfolio_path) {
            \Storage::disk('public')->delete($contractor->portfolio_path);
        }

        // Simpan file baru
        $path = $request->file('portfolio')->store('contractors/portfolios', 'public');
        $contractor->portfolio_path = $path;
        $contractor->save();

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
