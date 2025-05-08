<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'role' => 'required|in:designer,kontraktor',
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        // Verify the user exists and is not an admin
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot update admin roles');
        }

        $user->update([
            'role' => $request->role,
        ]);

        return back()->with('success', 'User role updated successfully');
    }

    public function index()
    {
        $users = User::query()
            ->select(['id', 'name', 'email', 'role', 'status', 'created_at'])
            ->where('role', '!=', 'admin')
            ->latest()
            ->get();
    
        return response()->json($users);
    }
    
    public function ban(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot ban admin users');
        }
    
        $user->update(['status' => 'banned']);
    
        return back()->with('success', 'User has been banned');
    }
    
    public function unban(User $user)
    {
        if ($user->role === 'admin') {
            return back()->with('error', 'Cannot unban admin users');
        }
    
        $user->update(['status' => 'active']);
    
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