<?php

namespace App\Http\Controllers\Api;
use App\Models\Contractor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ContractorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $contractors = Contractor::with(['user' => function($query) {
                $query->select('id', 'name', 'country', 'photo');
            }])
            ->select('user_id', 'specialty')
            ->get()
            ->map(function ($contractor) {
                return [
                    'id' => $contractor->user->id,
                    'name' => $contractor->user->name,
                    'country' => $contractor->user->country,
                    'specialty' => $contractor->specialty,
                    'photo' => $contractor->user->photo ? base64_encode($contractor->user->photo) : null
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
    // GET /api/designers/{id}
    // public function show($id)
    // {
    //     $designer = Designer::findOrFail($id);
    //     return response()->json([
    //         'id' => $designer->id,
    //         'name' => $designer->name,
    //         'country' => $designer->country,
    //         'origin_city' => $designer->origin_city,
    //         'specialty' => $designer->specialty,
    //         'photo' => base64_encode($designer->photo),
    //         'description' => $designer->description
    //     ]);
    // }

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
