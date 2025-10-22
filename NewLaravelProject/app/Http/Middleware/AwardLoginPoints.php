<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AwardLoginPoints
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Solo aplicar a usuarios autenticados
        if (auth()->check()) {
            $user = auth()->user();
            
            // Solo visitantes pueden ganar puntos por login
            if ($user->isVisitor() && $user->canEarnLoginPoints()) {
                $user->addPoints(1); // 1 punto por login diario
                $user->update(['last_login_at' => now()]);
            }
        }
        
        return $response;
    }
}
