<?php
namespace App\Http\Controllers;

use App\Models\PurchasedDesign;
use App\Models\Design;
use App\Models\RequestDesigner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PurchasedDesignController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Ambil semua purchased_designs milik user
        $purchased = PurchasedDesign::where('user_id', $user->id)->get();

        // Ambil semua design_id yang tidak null
        $designIds = $purchased->pluck('design_id')->filter()->unique()->toArray();

        // Ambil data design yang diperlukan
        $designs = [];
        if (!empty($designIds)) {
            $designs = Design::whereIn('id', $designIds)->get()->keyBy('id');
        }

        // Format hasil
        $result = $purchased->map(function ($item) use ($designs) {
            $photo_url = null;
            if ($item->design_id && isset($designs[$item->design_id]) && $designs[$item->design_id]->photo_path) {
                $photo_url = Storage::url($designs[$item->design_id]->photo_path);
            }
            return [
                'id' => $item->id,
                'design_id' => $item->design_id,
                'design_name' => $item->design_name,
                'design_country' => $item->design_country,
                'design_specialty' => $item->design_specialty,
                'design_path' => $item->design_path,
                'price' => $item->price,
                'photo_url' => $photo_url,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }

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
            'design_path' => $design->file_path,
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