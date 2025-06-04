<?php
namespace App\Http\Controllers;

use App\Models\PurchasedDesign;
use App\Models\Design;
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
        return redirect()->route('designs.show', $design->id)
            ->with('info', 'You have already purchased this design.');
    }

    PurchasedDesign::create([
        'user_id' => Auth::id(),
        'design_id' => $design->id,
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
}