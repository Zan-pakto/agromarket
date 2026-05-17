<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $role
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Admin has access to everything
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if role matches
        if ($user->role !== $role) {
            abort(403, 'Unauthorized access.');
        }

        // If the user is a seller, check if they are approved
        if ($role === 'seller' && !$user->isApproved()) {
            if ($user->status === 'pending') {
                return redirect()->route('dashboard')->with('status', 'registration-pending');
            } else {
                return redirect()->route('dashboard')->with('status', 'registration-rejected');
            }
        }

        return $next($request);
    }
}
