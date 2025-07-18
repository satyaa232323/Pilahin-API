<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class WebAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('user');

        if (!$user || ($user['role'] !== 'admin' && $user['role'] !== 'superadmin')) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
