<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!in_array(auth()->user()->role, $roles)) {
            abort(403, 'Accès refusé. Vous n\'avez pas les permissions nécessaires.');
        }

        if (!auth()->user()->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Votre compte a été désactivé.');
        }

        return $next($request);
    }
}
