<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChatAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user1 = (int) $request->route('user1');
        
        $authId = auth()->id();

        // Hanya user yang sedang login yang boleh akses
        if ($authId !== $user1) {
            abort(404);
        }

        return $next($request);
    }
}