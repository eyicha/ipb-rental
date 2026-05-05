<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }
        
        if (!auth()->user()->isAdmin()) {
            // For API requests, return JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Admin access required.',
                    'errors' => null,
                ], 403);
            }

            // For web requests, redirect to explore
            return redirect()->route('explore')->with('error', 'Akses ditolak. Hanya admin yang dapat mengakses halaman ini.');
        }
        return $next($request);
    }
}

