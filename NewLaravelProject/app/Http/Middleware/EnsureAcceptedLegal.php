<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAcceptedLegal
{
    /**
     * Routes that are allowed even if legal terms are not accepted.
     */
    protected array $allowedRoutes = [
        'legal.index',
        'legal.accept',
        'logout',
        'login',
        'register',
        'google.redirect',
        'google.callback',
        'password.request',
        'password.email',
        'password.reset',
        'password.store',
        'verification.notice',
        'verification.verify',
        'verification.send',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only check for authenticated users
        if (Auth::check()) {
            $user = Auth::user();
            
            // Check if user has not accepted legal terms
            if (!$user->accepted_legal) {
                // Allow access to legal pages and logout
                $routeName = $request->route()?->getName();
                
                // Also check if the route path matches legal routes (for POST requests)
                $routePath = $request->path();
                $isLegalRoute = in_array($routeName, $this->allowedRoutes) 
                    || str_starts_with($routePath, 'legal');
                
                if (!$isLegalRoute) {
                    // Redirect to legal acceptance page
                    return redirect()->route('legal.accept');
                }
            }
        }

        return $next($request);
    }
}
