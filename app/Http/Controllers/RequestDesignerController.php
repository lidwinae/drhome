<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RequestDesigner;

class RequestDesignerController extends Controller
{
    public function store(Request $request, $designerId)
    {
        $validated = $request->validate([
            'land_size' => 'required|string|max:50',
            'land_shape' => 'required|string|max:50',
            'sun_orientation' => 'required|string|max:100',
            'wind_orientation' => 'required|string|max:100',
            'budget' => 'nullable|numeric',
            'deadline' => 'nullable|date',
            'notes' => 'required|string',
            'design_reference_path' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:16384',
        ]);

        $designReferencePath = null;
        if ($request->hasFile('design_reference_path')) {
            $designReferencePath = $request->file('design_reference_path')->store('design_references', 'public');
        }

        $designerRequest = RequestDesigner::create([
            'client_id' => Auth::id(),
            'designer_id' => $designerId,
            'land_size' => $validated['land_size'],
            'land_shape' => $validated['land_shape'],
            'sun_orientation' => $validated['sun_orientation'],
            'wind_orientation' => $validated['wind_orientation'],
            'budget' => $validated['budget'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
            'notes' => $validated['notes'],
            'design_reference_path' => $designReferencePath,
            // purchased_design_id diisi null, nanti diisi designer setelah design_end
            'purchased_design_id' => null,
            'status' => 'waiting',
            'progress' => 'form_submitted',
        ]);

        return response()->json([
            'message' => 'Request berhasil dikirim.',
            'data' => $designerRequest
        ], 201);
    }
}