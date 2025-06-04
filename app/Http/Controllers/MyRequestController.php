<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\RequestContractor;
use App\Models\RequestDesigner;

class MyRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil request yang user sebagai client (request ke contractor)
        $contractorRequests = RequestContractor::with(['contractor' => function($q) {
                $q->select('id', 'name', 'avatar');
            }])
            ->where('client_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                $item->type = 'contractor';
                return $item;
            });

        // Ambil request yang user sebagai client (request ke designer)
        $designerRequests = RequestDesigner::with(['designer' => function($q) {
                $q->select('id', 'name', 'avatar');
            }])
            ->where('client_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($item) {
                $item->type = 'designer';
                return $item;
            });

        // Gabungkan dan urutkan berdasarkan waktu dibuat
        $allRequests = $contractorRequests->concat($designerRequests)
            ->sortByDesc('created_at')
            ->values();

        return inertia('MyRequest', [
            'requests' => $allRequests,
        ]);
    }
}