<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use App\Models\EndUser;

class CheckUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param Closure(): void $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Session::has('user_id')) {
            return redirect()->route('login');
        }
        // Vérifier le rôle (1 = Super Admin, 2 = Admin)
        $role = Session::get('role');
        if (!in_array($role, [1, 2])) {
            // Ni Super Admin ni Admin → rediriger vers login ou page d'erreur
            return redirect()->route('login')
                ->with('error', 'Accès réservé aux administrateurs.');
        }
        return $next($request);
    }
}
