<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            if (!$request->expectsJson()) {
                return redirect()->route('login');
            }

            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated'
            ], 401);
        }

        if (auth()->user()->role !== 'admin') {
            if (!$request->expectsJson()) {
                auth()->logout();

                return redirect()->route('login')->withErrors([
                    'email' => 'Admin access is required to view the dashboard.',
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.'
            ], 403);
        }

        return $next($request);
    }
}
