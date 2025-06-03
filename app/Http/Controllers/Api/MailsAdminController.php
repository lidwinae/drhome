<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MailsAdmin;
use App\Models\EmailHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomEmail;
use Illuminate\Support\Facades\Storage;

class MailsAdminController extends Controller
{
    /**
     * Menampilkan daftar mail judul dan role
     */
    public function index()
    {
        $mails = MailsAdmin::select('id', 'judul', 'role', 'created_at', 'updated_at','reply' )
            ->latest()
            ->get();

        return response()->json([
            'data' => $mails,
            'message' => 'Successfully retrieved mails list'
        ]);
    }

public function store(Request $request)
{
    $request->validate([
        'judul' => 'required|in:Feedback,Request menjadi designer,Request menjadi kontraktor',
        'message' => 'required|string',
        'portfolio' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
    ]);

    try {
        $user = auth()->user();

        $data = [
            'user_id' => $user->id,
            'judul' => $request->judul,
            'role' => $user->role,
            'message' => $request->message,
        ];

        if ($request->hasFile('portfolio')) {
            $portfolio = $request->file('portfolio');
            $path = $portfolio->store('portfolios', 'public'); // simpan ke storage/app/public/portfolios
            $data['portfolio_path'] = $path;
        }

        $mail = MailsAdmin::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Pesan berhasil dikirim ke admin',
            'data' => $mail
        ], 201);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Gagal mengirim pesan',
            'error' => $e->getMessage()
        ], 500);
    }
}


public function show($id)
{
    $mail = MailsAdmin::with('user:id,name,email')->findOrFail($id);

    $portfolio = null;
    if ($mail->portfolio_path) {
        // Gunakan asset() agar URL absolute, bukan Storage::url()
        $url = asset('storage/' . $mail->portfolio_path);
        $mime = Storage::disk('public')->mimeType($mail->portfolio_path);
        $portfolio = [
            'url' => $url,
            'mime_type' => $mime,
            'filename' => basename($mail->portfolio_path),
        ];
    }

    return response()->json([
        'data' => [
            'id' => $mail->id,
            'judul' => $mail->judul,
            'role' => $mail->role,
            'message' => $mail->message,
            'portfolio' => $portfolio,
            'user' => $mail->user,
            'created_at' => $mail->created_at->format('d M Y H:i'),
            'updated_at' => $mail->updated_at->format('d M Y H:i'),
            'reply' => $mail->reply,
        ],
        'message' => 'Successfully retrieved mail detail'
    ]);
}

public function userHistory()
{
    $user = auth()->user();
    $mails = MailsAdmin::where('user_id', $user->id)
        ->select('id', 'judul', 'role', 'message', 'created_at', 'updated_at', 'reply', 'portfolio_path')
        ->latest()
        ->get()
        ->map(function ($mail) {
            return [
                'id' => $mail->id,
                'judul' => $mail->judul,
                'role' => $mail->role,
                'message' => $mail->message,
                'created_at' => $mail->created_at,
                'updated_at' => $mail->updated_at,
                'reply' => $mail->reply,
                'portfolio' => $mail->portfolio_path ? true : false,
            ];
        });

    return response()->json([
        'data' => $mails,
        'message' => 'Successfully retrieved user mails history'
    ]);
}

    /**
     * Helper untuk mendeteksi tipe file dari binary data
     */
    private function detectMimeType($binaryData)
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        return $finfo->buffer($binaryData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'reply' => 'required|string',
        ]);
    
        $mail = MailsAdmin::findOrFail($id);
        $mail->update(['reply' => $request->reply]);
    
        return back()->with('success', 'Balasan berhasil disimpan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MailsAdmin $admin)
    {
        //
    }

    public function send(Request $request)
    {
        $request->validate([
            'recipient' => 'required|email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // Save to history first
        $emailHistory = EmailHistory::create([
            'recipient' => $request->recipient,
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => 'pending',
        ]);

        try {
            Mail::to($request->recipient)
                ->send(new CustomEmail($request->subject, $request->message));
            
            $emailHistory->update(['status' => 'sent']);
            
            return response()->json([
                'success' => true,
                'message' => 'Email sent successfully'
            ]);
        } catch (\Exception $e) {
            $emailHistory->update([
                'status' => 'failed',
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function riwayat()
    {
        $mails = EmailHistory::select('id', 'recipient', 'subject', 'message', 'created_at' )
            ->latest()
            ->get();

        return response()->json([
            'data' => $mails,
            'message' => 'Successfully retrieved mails list'
        ]);
    }
}
