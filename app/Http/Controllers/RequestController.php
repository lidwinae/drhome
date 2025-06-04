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
}