<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ManagerMiddleware
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
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        if (!auth()->user()->isManager()) {
            abort(403, 'Unauthorized. Manager access only.');
        }

        // If manager is pending approval, redirect to pending page
        if (auth()->user()->isPending() && !$request->routeIs('pending.approval')) {
            return redirect()->route('pending.approval');
        }

        // If manager is inactive, deny access
        if (auth()->user()->isInactive()) {
            abort(403, 'Your account has been deactivated. Please contact admin.');
        }

        return $next($request);
    }
}
