<?php
namespace App\Http\Controllers;

use App\Models\PurchasedDesign;
use App\Models\Design;
use App\Models\RequestDesigner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PurchasedDesignController extends Controller
{
    // Store a new purchase
    public function store(Request $request)
{
    $request->validate([
        'design_id' => 'required|exists:designs,id',
        'amount' => 'required|numeric|min:0.01'
    ]);

    $design = Design::findOrFail($request->design_id);

    // Cegah pembelian ganda
    $alreadyPurchased = PurchasedDesign::where('user_id', Auth::id())
        ->where('design_id', $design->id)
        ->exists();

    if ($alreadyPurchased) {
        return redirect()->route('designdetail', $design->id)
            ->with('info', 'You have already purchased this design.');
    }

    PurchasedDesign::create([
        'user_id' => Auth::id(),
        'design_id' => $design->id,
        'design_name' => $design->name,
        'design_country' => $design->country,
        'design_specialty' => $design->specialty,
        'design_path' => $design->photo_path,
        'price' => $request->amount
    ]);

    return redirect()->route('designdetail', $design->id)
        ->with('success', 'Design purchased successfully!');
}

public function isPurchased(Request $request, $designId)
{
    $userId = Auth::id();
    $purchased = PurchasedDesign::where('user_id', $userId)
        ->where('design_id', $designId)
        ->exists();

    return response()->json(['purchased' => $purchased]);
}

public function userPurchasedDesigns(Request $request)
{
    $userId = Auth::id();
    $designs = PurchasedDesign::where('user_id', $userId)
        ->select('id', 'design_id', 'design_name', 'design_country', 'design_specialty', 'design_path')
        ->get();

    return response()->json([
        'designs' => $designs
    ]);
}

public function storeFromRequestDesigner(Request $request)
{
    $request->validate([
        'request_designer_id' => 'required|exists:request_designers,id',
        'design_name' => 'required|string|max:100',
        'design_country' => 'required|string|max:50',
        'design_specialty' => 'required|string|max:50',
        'design_path' => 'required|file|mimes:pdf,jpg,jpeg,png|max:16384', // 16MB
        'price' => 'required|numeric|min:0.01',
    ]);

    $reqDesigner = RequestDesigner::findOrFail($request->request_designer_id);

    // Simpan file ke storage/app/public/purchaseddesigner
    $file = $request->file('design_path');
    $path = $file->store('purchaseddesigner', 'public');

    $purchased = PurchasedDesign::create([
        'user_id' => $reqDesigner->client_id,
        'design_id' => null,
        'design_name' => $request->design_name,
        'design_country' => $request->design_country,
        'design_specialty' => $request->design_specialty,
        'design_path' => $path,
        'price' => $request->price,
    ]);

    // Update progress dan status
    $reqDesigner->purchased_design_id = $purchased->id;
    $reqDesigner->progress = 'design_end';
    $reqDesigner->status = 'finished';
    $reqDesigner->save();

    return response()->json(['success' => true, 'purchased_design_id' => $purchased->id]);
}
}