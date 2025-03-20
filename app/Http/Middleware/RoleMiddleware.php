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
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
