<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RefereeMiddleware
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

        if (!auth()->user()->isReferee() && !auth()->user()->isAdmin()) {
            abort(403, 'Unauthorized. Referee access only.');
        }

        return $next($request);
    }
}
