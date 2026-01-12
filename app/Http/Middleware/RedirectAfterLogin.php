<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectAfterLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && $request->path() === '/') {
            $user = auth()->user();
            
            return match($user->role) {
                'admin' => redirect('/admin/dashboard'),
                'pharmacist' => redirect('/pharmacist/dashboard'),
                'patient' => redirect('/dashboard'),
                default => $next($request),
            };
        }

        return $next($request);
    }
}
