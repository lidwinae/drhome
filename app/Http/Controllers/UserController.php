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
            ['specialty' => null, 'portfolio' => null]);
        }

        if ($request->role === 'designer') {
            Designer::updateOrCreate(
            ['user_id' => $user->id],
            ['specialty' => null, 'portfolio' => null]);
        }

        return back()->with('success', 'User role updated successfully');
    }

public function up(Request $request, $id) // Tambahkan parameter $id
{
    $validated = $request->validate([
        // Hapus validasi user_id karena sudah dari route parameter
        'specialty' => 'sometimes|string|max:50',
        'portfolio' => 'sometimes|file|mimes:pdf|max:16384' // 16MB, optional
    ]);

    $user = User::findOrFail($id); // Gunakan $id dari route parameter

    try {
        $data = ['specialty' => $validated['specialty'] ?? null];
        
        if ($request->hasFile('portfolio')) {
            $data['portfolio'] = file_get_contents($request->file('portfolio')->getRealPath());
        }

        if ($user->role === 'contractor') {
            Contractor::updateOrCreate(
                ['user_id' => $id], // Gunakan $id
                $data
            );
        } 
        elseif ($user->role === 'designer') {
            Designer::updateOrCreate(
                ['user_id' => $id], // Gunakan $id
                $data
            );
        }
        else {
            return response()->json(['error' => 'Hanya contractor atau designer yang bisa diupdate'], 400);
        }

        return response()->json(['success' => 'Data berhasil diupdate']);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Gagal mengupdate data: ' . $e->getMessage()], 500);
    }
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