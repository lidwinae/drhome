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
}