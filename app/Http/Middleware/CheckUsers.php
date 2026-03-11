<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class CheckUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  Closure(): void  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier d'abord si l'utilisateur est connecté via Auth (remember me)
        if (! Session::has('user_id')) {
            // Vérifier si l'utilisateur est connecté via le token "remember me"
            if (Auth::check()) {
                $user = Auth::user();
                // Restaurer la session
                Session::put('user_id', $user->id);
                Session::put('role', $user->role);
                Session::put('theme', $user->theme ?? 'light');
            } else {
                return redirect()->route('login');
            }
        }

        // Vérifier le rôle (1 = Super Admin, 2 = Admin, 3 = Reader)
        $role = Session::get('role');
        if (! in_array($role, [1, 2, 3])) {
            // Ni Super Admin ni Admin ni Reader → rediriger vers login ou page d'erreur
            return redirect()->route('login')
                ->with('error', 'Accès réservé aux administrateurs.');
        }

        // Rafraîchir la session à chaque requête
        if (Auth::check()) {
            $user = Auth::user();
            Session::put('user_id', $user->id);
            Session::put('role', $user->role);
            Session::put('theme', $user->theme ?? 'light');
        }

        return $next($request);
    }
}
