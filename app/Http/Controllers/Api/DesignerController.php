<?php

namespace App\Http\Controllers\Api;
use App\Models\Designer;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DesignerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designers = Designer::all()->map(function ($designer) {
            return [
                'id' => $designer->id,
                'name' => $designer->name,
                'country' => $designer->country,
                'origin_city' => $designer->origin_city,
                'specialty' => $designer->specialty,
                'photo' => base64_encode($designer->photo),
                'description' => $designer->description
            ];
        });

        return response()->json($designers);
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
        $designer = Designer::findOrFail($id);
        return response()->json([
            'id' => $designer->id,
            'name' => $designer->name,
            'country' => $designer->country,
            'origin_city' => $designer->origin_city,
            'specialty' => $designer->specialty,
            'photo' => base64_encode($designer->photo),
            'description' => $designer->description
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
