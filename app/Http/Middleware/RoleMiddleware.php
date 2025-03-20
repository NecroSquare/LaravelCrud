<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $roles)
    {
        $user = session('user');

        if (!$user || !in_array($user->role, explode(',', $roles))) {
            // return redirect()->route('loans.index'); // Redirect unauthorized users
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
