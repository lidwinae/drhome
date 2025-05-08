<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MailsAdmin;
use App\Models\EmailHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomEmail;

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Menampilkan detail mail
     */
    public function show($id)
    {
        $mail = MailsAdmin::with('user:id,name,email') // Load relasi user
            ->findOrFail($id);

        // Format portfolio jika ada
        $portfolio = null;
        if ($mail->portfolio) {
            $portfolio = [
                'data' => base64_encode($mail->portfolio),
                'mime_type' => $this->detectMimeType($mail->portfolio)
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
