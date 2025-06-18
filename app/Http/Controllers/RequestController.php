<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RequestContractor;
use App\Models\RequestDesigner;

class RequestController extends Controller
{
public function index()
{
    $user = Auth::user();
    $requests = [];
    $role = null;

    if ($user->role === 'contractor') {
        $requests = RequestContractor::with(['client' => function($query) {
                $query->select('id', 'name', 'avatar');
            }])
            ->where('contractor_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $role = 'contractor';
    } elseif ($user->role === 'designer') {
        $requests = RequestDesigner::with(['client' => function($query) {
                $query->select('id', 'name', 'avatar');
            }])
            ->where('designer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $role = 'designer';
    }

    return inertia('Request', [
        'requests' => $requests,
        'role' => $role
    ]);
}

public function indexApi()
{
    $user = Auth::user();

    if (!$user) {
        abort(404);
    }

    $requests = [];
    $role = null;

    if ($user->role === 'contractor') {
        $requests = RequestContractor::with(['client:id,name,avatar'])
            ->where('contractor_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $role = 'contractor';
    } elseif ($user->role === 'designer') {
        $requests = RequestDesigner::with(['client:id,name,avatar'])
            ->where('designer_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();
        $role = 'designer';
    }

    return response()->json([
        'requests' => $requests,
        'role' => $role,
    ]);
}

public function show($id)
{
    // Cek apakah request contractor dengan id ini ada
    $request = RequestContractor::with([
            'client:id,name,avatar',
            'contractor:id,name,avatar',
            'purchasedDesign' // tambahkan ini
        ])
        ->find($id);

    if ($request) {
        $type = 'contractor';
    } else {
        // Jika tidak, cek di request designer
        $request = RequestDesigner::with([
                'client:id,name,avatar',
                'designer:id,name,avatar',
                'purchasedDesign' // tambahkan ini
            ])
            ->find($id);
        if ($request) {
            $type = 'designer';
        } else {
            abort(404, 'Request not found');
        }
    }

    return inertia('RequestDetail', [
        'request' => $request,
        'type' => $type,
    ]);
}

    public function showApi($id)
    {
        // Cek di request_contractors
        $request = RequestContractor::with([
                'client:id,name,avatar',
                'contractor:id,name,avatar',
                'purchasedDesign'
            ])->find($id);

        if ($request) {
            $type = 'contractor';
        } else {
            // Cek di request_designers
            $request = RequestDesigner::with([
                    'client:id,name,avatar',
                    'designer:id,name,avatar',
                    'purchasedDesign'
                ])->find($id);

            if ($request) {
                $type = 'designer';
            } else {
                return response()->json([
                    'message' => 'Request not found'
                ], 404);
            }
        }

        return response()->json([
            'request' => $request,
            'type' => $type,
        ]);
    }

public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:accepted,rejected',
        'type' => 'required|in:contractor,designer',
    ]);

    if ($request->type === 'contractor') {
        $model = RequestContractor::findOrFail($id);
    } else {
        $model = RequestDesigner::findOrFail($id);
    }

    // Hanya bisa update jika status masih waiting
    if ($model->status !== 'waiting') {
        return response()->json(['message' => 'Status cannot be changed.'], 400);
    }

    $model->status = $request->status;

    // Set progress sesuai status
    if ($request->status === 'accepted') {
        $model->progress = 'payment';
    } elseif ($request->status === 'rejected') {
        // progress tetap (tidak berubah)
    }

    $model->save();

    return response()->json(['message' => 'Status updated successfully.']);
}

public function finishConstruction(Request $request, $id)
{
    $request->validate([
        'type' => 'required|in:contractor'
    ]);

    $model = RequestContractor::findOrFail($id);

    // Hanya bisa update jika progress masih construction_start
    if ($model->progress !== 'construction_start') {
        return response()->json(['message' => 'Cannot finish construction at this stage.'], 400);
    }

    $model->progress = 'construction_end';
    $model->status = 'finished';
    $model->save();

    return response()->json(['message' => 'Construction finished.']);
}
}