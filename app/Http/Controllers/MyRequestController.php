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

    public function show($id)
    {
        $user = Auth::user();

        // Cek di request_contractors
        $request = RequestContractor::with([
                'contractor:id,name,avatar',
                'purchasedDesign' // tambahkan ini
            ])
            ->where('id', $id)
            ->where('client_id', $user->id)
            ->first();

        if ($request) {
            $type = 'contractor';
        } else {
            // Cek di request_designers
            $request = RequestDesigner::with([
                    'designer:id,name,avatar',
                    'purchasedDesign' // tambahkan ini
                ])
                ->where('id', $id)
                ->where('client_id', $user->id)
                ->first();

            if ($request) {
                $type = 'designer';
            } else {
                abort(404, 'Request not found or not authorized.');
            }
        }

        return inertia('MyRequestDetail', [
            'request' => $request,
            'type' => $type,
        ]);
    }

    public function showApi($id)
    {
        $user = Auth::user();

        // Cek apakah request milik user di request_contractors
        $request = RequestContractor::with([
                'contractor:id,name,avatar',
                'purchasedDesign'
            ])
            ->where('id', $id)
            ->where('client_id', $user->id)
            ->first();

        if ($request) {
            $type = 'contractor';
        } else {
            // Jika tidak, cek di request_designers
            $request = RequestDesigner::with([
                    'designer:id,name,avatar',
                    'purchasedDesign'
                ])
                ->where('id', $id)
                ->where('client_id', $user->id)
                ->first();

            if ($request) {
                $type = 'designer';
            } else {
                return response()->json([
                    'message' => 'Request not found or not authorized.'
                ], 404);
            }
        }

        return response()->json([
            'request' => $request,
            'type' => $type,
        ]);
    }

    public function pay($type, $id)
    {
        $user = Auth::user();

        if ($type === 'contractor') {
            $request = RequestContractor::where('id', $id)->where('client_id', $user->id)->firstOrFail();
            $nextProgress = 'construction_start';
        } else if ($type === 'designer') {
            $request = RequestDesigner::where('id', $id)->where('client_id', $user->id)->firstOrFail();
            $nextProgress = 'design_start';
        } else {
            abort(404, 'Invalid request type.');
        }

        request()->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        // Simpan payment ke kolom payment dan update progress
        $request->payment = request('amount');
        $request->progress = $nextProgress;
        $request->save();

        return response()->json(['message' => 'Payment successful!']);
    }

    public function openAcc($type, $id)
    {
        $user = Auth::user();

        if ($type === 'contractor') {
            $request = RequestContractor::where('id', $id)->where('client_id', $user->id)->firstOrFail();
        } else if ($type === 'designer') {
            $request = RequestDesigner::where('id', $id)->where('client_id', $user->id)->firstOrFail();
        } else {
            abort(404, 'Invalid request type.');
        }

        $request->open_acc = true;
        $request->save();

        return response()->json(['success' => true]);
    }
}