<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRoleIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (str_contains($request->user()->userRole()->role()->get(), 'Admin')) {
            echo 'User is admin';
            return $next($request);
        }

        return redirect()->route('home')->with('error', 'You do not have admin access.');
    }
}
