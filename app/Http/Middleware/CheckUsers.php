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
            return redirect()->route('login')
                ->with('error', 'Accès réservé aux administrateurs.');
        }

        // Vérifier l'accès basé sur le rôle
        $currentRoute = $request->path();

        // Routes Super Admin
        $superAdminRoutes = ['super_admin'];

        // Routes Admin
        $adminRoutes = ['admins'];

        // Routes Reader
        $readerRoutes = ['readers'];

        // Super Admin (rôle 1) - ne peut pas accéder aux routes admin et reader
        if ($role === 1) {
            foreach ($adminRoutes as $route) {
                if (str_starts_with($currentRoute, $route)) {
                    return response()->view('errors.403', [
                        'message' => 'En tant que Super Administrateur, vous n\'avez pas accès à cette interface.',
                    ], 403);
                }
            }
            foreach ($readerRoutes as $route) {
                if (str_starts_with($currentRoute, $route)) {
                    return response()->view('errors.403', [
                        'message' => 'En tant que Super Administrateur, vous n\'avez pas accès à cette interface.',
                    ], 403);
                }
            }
        }

        // Admin (rôle 2) - ne peut pas accéder aux routes super admin et reader
        if ($role === 2) {
            foreach ($superAdminRoutes as $route) {
                if (str_starts_with($currentRoute, $route)) {
                    return response()->view('errors.403', [
                        'message' => 'En tant qu\'Administrateur, vous n\'avez pas accès à cette interface.',
                    ], 403);
                }
            }
            foreach ($readerRoutes as $route) {
                if (str_starts_with($currentRoute, $route)) {
                    return response()->view('errors.403', [
                        'message' => 'En tant qu\'Administrateur, vous n\'avez pas accès à cette interface.',
                    ], 403);
                }
            }
        }

        // Reader (rôle 3) - ne peut pas accéder aux routes super admin et admin
        if ($role === 3) {
            foreach ($superAdminRoutes as $route) {
                if (str_starts_with($currentRoute, $route)) {
                    return response()->view('errors.403', [
                        'message' => 'En tant que Lecteur, vous n\'avez pas accès à cette interface.',
                    ], 403);
                }
            }
            foreach ($adminRoutes as $route) {
                if (str_starts_with($currentRoute, $route)) {
                    return response()->view('errors.403', [
                        'message' => 'En tant que Lecteur, vous n\'avez pas accès à cette interface.',
                    ], 403);
                }
            }
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
