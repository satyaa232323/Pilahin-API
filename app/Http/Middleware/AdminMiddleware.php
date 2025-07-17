<?php

namespace App\Http\Middleware;

use App\Helper\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
     public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        
        if (!$user || ($user->role !== 'admin' && $user->role !== 'superadmin')) {
            return ResponseHelper::forbidden('Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}