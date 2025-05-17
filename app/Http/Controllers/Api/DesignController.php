<?php

namespace App\Http\Controllers\Api;
use App\Http\Requests\StoreDesignRequest;
use App\Models\Design;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;

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
    public function store(StoreDesignRequest $request)
    {
        try {
            $data = $request->validated();

            // Handle file upload
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $data['photo'] = file_get_contents($photo->getRealPath());
            }

            Design::create($data);

            return redirect()->route('newd')
                ->with('success', 'Design berhasil dibuat!');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
                ->withInput();
        }
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
    public function update(Request $request, $id)
    {
        $design = Design::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'country' => 'required|string|max:50',
            'specialty' => 'required|string|max:50',
            'photo' => 'nullable|image|max:16384',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'country' => $validated['country'],
            'specialty' => $validated['specialty'],
        ];

        if ($request->hasFile('photo')) {
            $updateData['photo'] = file_get_contents($request->file('photo')->getRealPath());
        }

        $design->update($updateData);

        return response()->json([
            'success' => true,
            'design' => $design,
            'message' => 'Updated design'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $design = Design::findOrFail($id);
            $design->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete design'
            ], 500);
        }
    }
}
