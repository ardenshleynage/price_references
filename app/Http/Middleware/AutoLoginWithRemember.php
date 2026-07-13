<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class AutoLoginWithRemember
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     * @param Closure(): void $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Session::has('user_id')) {
            return $next($request);
        }

        // Si pas de session, vérifier via remember token
        if (Auth::check()) {
            $user = Auth::user();

            // Restaurer la session
            Session::put('user_id', $user->id);
            Session::put('role', $user->role);
            Session::put('theme', $user->theme ?? 'light');

            // Rediriger vers le dashboard selon le rôle
            switch ($user->role) {
                case 1: // Super Admin
                    return redirect()->route('super_admin_home');
                case 2: // Admin
                    return redirect()->route('admins_home');
                case 3: // Utilisateur
                    return redirect()->route('readers_home');
                default:
                    return redirect()->route('home');
            }
        }

        // Pas de session et pas de remember token valide
        // Laisser l'accès à la page d'accueil
        return $next($request);
    }
}
