<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        $akunRole = session('role', Auth::user()->role->pluck('nama')->toArray());

        foreach ($roles as $role) {
            if (in_array($role, $akunRole)) {
                return $next($request);
            }
        }

        // Jika tidak memiliki akses
        return abort(403, 'Unauthorized');
    }
}
