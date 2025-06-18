<?php

namespace App\Http\Controllers;

use App\Models\RequestContractor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestContractorController extends Controller
{
    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'purchased_design_id' => 'required|exists:purchased_designs,id',
            'province' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'land_size' => 'required|string|max:50',
            'land_shape' => 'required|string|max:50',
            'budget' => 'nullable|numeric',
            'deadline' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $data = [
            'client_id' => auth()->id(),
            'contractor_id' => $id, // gunakan id dari URL
            'purchased_design_id' => $validated['purchased_design_id'],
            'province' => $validated['province'],
            'city' => $validated['city'],
            'land_size' => $validated['land_size'],
            'land_shape' => $validated['land_shape'],
            'budget' => $validated['budget'] ?? null,
            'deadline' => $validated['deadline'] ?? null,
            'notes' => $validated['notes'] ?? null,
            'status' => 'waiting',
            'progress' => 'design_submitted',
        ];

        $requestContractor = RequestContractor::create($data);

        return response()->json([
            'success' => true,
            'request_contractor' => $requestContractor,
            'message' => 'Request contractor submitted successfully.'
        ]);
    }
}