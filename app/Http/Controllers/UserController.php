<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Contractor;
use App\Models\Designer;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:designer,contractor',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot update admin roles');
        }

        $user->updateByAdmin(['role' => $request->role]);

        if ($request->role === 'contractor') {
            Contractor::updateOrCreate(
            ['user_id' => $user->id],
            ['specialty' => null]);
        }

        if ($request->role === 'designer') {
            Designer::updateOrCreate(
            ['user_id' => $user->id],
            ['specialty' => null]);
        }

        return back()->with('success', 'User role updated successfully');
    }

    public function index()
{
    $users = User::query()
        ->select(['id', 'name', 'email', 'role', 'status', 'created_at', 'avatar'])
        ->where('role', '!=', 'admin')
        ->latest()
        ->get()
        ->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'status' => $user->status,
                'created_at' => $user->created_at,
                'avatar_url' => $user->avatar ? asset('storage/' . $user->avatar) : null,
            ];
        });

    return response()->json($users);
}

    public function getClients()
    {
        $clients = User::where('role', 'client')
            ->select(['id', 'name', 'email'])
            ->orderBy('name')
            ->get();
        
        return response()->json($clients);
    }
    
    public function ban(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot ban admin users');
        }

        $user->updateByAdmin(['status' => 'banned']);

        return back()->with('success', 'User has been banned');
    }

    public function unban(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot unban admin users');
        }

        $user->updateByAdmin(['status' => 'active']);

        return back()->with('success', 'User has been unbanned');
    }
    
    public function destroy(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot delete admin users');
        }
        
        $user->delete();
        
        return back()->with('success', 'User has been deleted');
    }

}