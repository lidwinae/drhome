<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
public function send(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'message' => 'nullable|string',
            'file' => 'nullable|file|max:16384', // max 16MB
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('chat_files', 'public');
        }

        $chat = Chat::create([
            'sender_id' => Auth::id(), // Gunakan user yang sedang login
            'recipient_id' => $request->recipient_id,
            'message' => $request->message,
            'file_path' => $filePath,
        ]);

        return response()->json([
            'success' => true,
            'chat' => $chat->load('sender', 'recipient')
        ]);
    }

    public function getChats($userId)
    {
        $currentUserId = Auth::id();
        
        $chats = Chat::where(function($query) use ($currentUserId, $userId) {
                $query->where('sender_id', $currentUserId)
                      ->where('recipient_id', $userId);
            })
            ->orWhere(function($query) use ($currentUserId, $userId) {
                $query->where('sender_id', $userId)
                      ->where('recipient_id', $currentUserId);
            })
            ->with(['sender', 'recipient'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'chats' => $chats,
            'currentUserId' => $currentUserId
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($user1_id, $user2_id)
    {
        // Ambil chat antara dua user (user1 dan user2)
        $chats = Chat::where(function($q) use ($user1_id, $user2_id) {
                $q->where('sender_id', $user1_id)->where('recipient_id', $user2_id);
            })
            ->orWhere(function($q) use ($user1_id, $user2_id) {
                $q->where('sender_id', $user2_id)->where('recipient_id', $user1_id);
            })
            ->orderBy('created_at')
            ->get();

        $user1 = User::findOrFail($user1_id);
        $user2 = User::findOrFail($user2_id);

        // return inertia/vue or blade, contoh inertia:
        return inertia('Chat', [
            'chats' => $chats,
            'user1' => $user1,
            'user2' => $user2,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chat $chat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chat $chat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chat $chat)
    {
        //
    }
}
