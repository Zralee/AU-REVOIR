<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminRule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and if they have an 'admin' role or similar
        if (Auth::check() && Auth::user()->usertype == 1) {
            // Allow access to admin routes
            return $next($request);
        }

        // If not admin, redirect to home page or show 403 forbidden
        return redirect('/');
        // or return response()->json(['message' => 'Forbidden'], 403);
    }
}

