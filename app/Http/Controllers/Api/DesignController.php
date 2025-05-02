<?php

namespace App\Http\Controllers\Api;
use App\Models\Design;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $designs = Design::all()->map(function ($design) {
            return [
                'id' => $design->id,
                'name' => $design->name,
                'country' => $design->country,
                'specialty' => $design->specialty,
                'photo' => base64_encode($design->photo)
            ];
        });

        return response()->json($designs);
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
    // GET /api/designs/{id}
    public function show($id)
    {
        $design = Design::findOrFail($id);
        return response()->json([
            'id' => $design->id,
            'name' => $design->name,
            'country' => $design->country,
            'specialty' => $design->specialty,
            'photo' => base64_encode($design->photo)
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
