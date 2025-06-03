<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreDesignRequest;
use App\Models\Design;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DesignController extends Controller
{
    public function index()
    {
        $designs = Design::all()->map(function ($design) {
            return [
                'id' => $design->id,
                'name' => $design->name,
                'country' => $design->country,
                'specialty' => $design->specialty,
                'description' => $design->description,
                'photo_url' => $design->photo_path ? Storage::url($design->photo_path) : null,
                'preview_url' => $design->preview_path ? Storage::url($design->preview_path) : null
            ];
        });

        return response()->json($designs);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'country' => 'required|string|max:50',
                'specialty' => 'required|string|max:50',
                'description' => 'nullable|string',
                'photo' => 'nullable|image|max:16384', // Max 16MB
                'preview' => 'nullable|mimetypes:video/mp4,video/quicktime|max:51200', // Max 50MB
            ]);

            $data = [
                'name' => $validated['name'],
                'country' => $validated['country'],
                'specialty' => $validated['specialty'],
                'description' => $validated['description'] ?? null,
            ];

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $path = $request->file('photo')->store('designs/photos', 'public');
                $data['photo_path'] = $path;
            }

            // Handle preview video upload
            if ($request->hasFile('preview')) {
                $path = $request->file('preview')->store('designs/previews', 'public');
                $data['preview_path'] = $path;
            }

            $design = Design::create($data);

            return response()->json([
                'success' => true,
                'design' => $design,
                'message' => 'Design created successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating design: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $design = Design::findOrFail($id);
        return response()->json([
            'id' => $design->id,
            'name' => $design->name,
            'country' => $design->country,
            'specialty' => $design->specialty,
            'description' => $design->description,
            'photo_url' => $design->photo_path ? Storage::url($design->photo_path) : null,
            'preview_url' => $design->preview_path ? Storage::url($design->preview_path) : null
        ]);
    }

    public function update(Request $request, $id)
    {
        try {
            $design = Design::findOrFail($id);

            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'country' => 'required|string|max:50',
                'specialty' => 'required|string|max:50',
                'description' => 'nullable|string',
                'photo' => 'nullable|image|max:16384',
                'preview' => 'nullable|mimetypes:video/mp4,video/quicktime|max:51200',
            ]);

            $updateData = [
                'name' => $validated['name'],
                'country' => $validated['country'],
                'specialty' => $validated['specialty'],
                'description' => $validated['description'] ?? $design->description,
            ];

            // Handle photo update
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($design->photo_path) {
                    Storage::disk('public')->delete($design->photo_path);
                }
                
                $path = $request->file('photo')->store('designs/photos', 'public');
                $updateData['photo_path'] = $path;
            }

            // Handle preview update
            if ($request->hasFile('preview')) {
                // Delete old preview if exists
                if ($design->preview_path) {
                    Storage::disk('public')->delete($design->preview_path);
                }
                
                $path = $request->file('preview')->store('designs/previews', 'public');
                $updateData['preview_path'] = $path;
            }

            $design->update($updateData);

            return response()->json([
                'success' => true,
                'design' => $design,
                'message' => 'Design updated successfully'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating design: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $design = Design::findOrFail($id);
            
            // Delete associated files
            if ($design->photo_path) {
                Storage::disk('public')->delete($design->photo_path);
            }
            if ($design->preview_path) {
                Storage::disk('public')->delete($design->preview_path);
            }
            
            $design->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete design: ' . $e->getMessage()
            ], 500);
        }
    }
}